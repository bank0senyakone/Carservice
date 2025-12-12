<?php

namespace App\Models;

use App\Core\DB;

/**
 * Job Model
 * Handles job/repair order data access
 */
class JobModel
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = DB::getInstance()->getConnection();
    }

    /**
     * Get all jobs with customer info
     * 
     * @param string|null $status Filter by status
     * @return array
     */
    public function getAll(?string $status = null): array
    {
        $sql = "SELECT j.*, c.name as customer_name, c.phone as customer_phone 
                FROM jobs j 
                LEFT JOIN customers c ON j.customer_id = c.id 
                WHERE 1=1";
        $params = [];

        if ($status) {
            $sql .= " AND j.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY j.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get jobs by status
     * 
     * @param string $status
     * @return array
     */
    public function getByStatus(string $status): array
    {
        return $this->getAll($status);
    }

    /**
     * Get jobs grouped by status for workflow board
     * 
     * @return array
     */
    public function getJobsByStatusGroups(): array
    {
        $statuses = ['Queue', 'In Progress', 'Waiting for Parts', 'Quality Check'];
        $result = [];

        foreach ($statuses as $status) {
            $result[$status] = $this->getByStatus($status);
        }

        return $result;
    }

    /**
     * Get job by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM jobs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get job with customer data
     * 
     * @param int $id
     * @return array|false
     */
    public function getJobWithCustomer(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT j.*, c.name as customer_name, c.phone as customer_phone, c.email as customer_email 
            FROM jobs j 
            LEFT JOIN customers c ON j.customer_id = c.id 
            WHERE j.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Create new job
     * 
     * @param array $data
     * @return int|false
     */
    public function create(array $data): int|false
    {
        $stmt = $this->db->prepare("
            INSERT INTO jobs (customer_id, license_plate, car_model, car_year, vin, description, status, priority, estimated_completion) 
            VALUES (:customer_id, :license_plate, :car_model, :car_year, :vin, :description, :status, :priority, :estimated_completion)
        ");

        $result = $stmt->execute([
            'customer_id' => $data['customer_id'],
            'license_plate' => $data['license_plate'],
            'car_model' => $data['car_model'],
            'car_year' => $data['car_year'] ?? null,
            'vin' => $data['vin'] ?? '',
            'description' => $data['description'] ?? '',
            'status' => $data['status'] ?? 'Queue',
            'priority' => $data['priority'] ?? 'Medium',
            'estimated_completion' => $data['estimated_completion'] ?? null
        ]);

        return $result ? (int)$this->db->lastInsertId() : false;
    }

    /**
     * Update job
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE jobs SET 
                customer_id = :customer_id,
                license_plate = :license_plate,
                car_model = :car_model,
                car_year = :car_year,
                vin = :vin,
                description = :description,
                status = :status,
                priority = :priority,
                estimated_completion = :estimated_completion
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'customer_id' => $data['customer_id'],
            'license_plate' => $data['license_plate'],
            'car_model' => $data['car_model'],
            'car_year' => $data['car_year'] ?? null,
            'vin' => $data['vin'] ?? '',
            'description' => $data['description'] ?? '',
            'status' => $data['status'] ?? 'Queue',
            'priority' => $data['priority'] ?? 'Medium',
            'estimated_completion' => $data['estimated_completion'] ?? null
        ]);
    }

    /**
     * Update job status
     * 
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE jobs SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    /**
     * Delete job
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM jobs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get job statistics
     * 
     * @return array
     */
    public function getStats(): array
    {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_jobs,
                SUM(CASE WHEN status NOT IN ('Completed', 'Ready') THEN 1 ELSE 0 END) as active_jobs,
                SUM(CASE WHEN status = 'Completed' AND DATE(updated_at) = CURDATE() THEN 1 ELSE 0 END) as completed_today,
                SUM(CASE WHEN status = 'Queue' THEN 1 ELSE 0 END) as queue_count,
                SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress_count,
                SUM(CASE WHEN status = 'Waiting for Parts' THEN 1 ELSE 0 END) as waiting_parts_count,
                SUM(CASE WHEN status = 'Quality Check' THEN 1 ELSE 0 END) as quality_check_count
            FROM jobs
        ");
        return $stmt->fetch();
    }

    /**
     * Get today's revenue (from completed quotes)
     * 
     * @return float
     */
    public function getTodayRevenue(): float
    {
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(q.total_amount * (1 + q.tax_rate / 100)), 0) as revenue
            FROM quotes q
            JOIN jobs j ON q.job_id = j.id
            WHERE j.status = 'Completed' 
            AND DATE(j.updated_at) = CURDATE()
            AND q.status = 'Approved'
        ");
        $result = $stmt->fetch();
        return (float)($result['revenue'] ?? 0);
    }
}
