<?php

namespace App\Models;

use App\Core\DB;

/**
 * Customer Model
 * Handles customer data access
 */
class CustomerModel
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = DB::getInstance()->getConnection();
    }

    /**
     * Get all customers
     * 
     * @param string|null $search Search query
     * @return array
     */
    public function getAll(?string $search = null): array
    {
        $sql = "SELECT c.*, COUNT(j.id) as job_count 
                FROM customers c 
                LEFT JOIN jobs j ON c.id = j.customer_id 
                WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (c.name LIKE :search OR c.email LIKE :search OR c.phone LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $sql .= " GROUP BY c.id ORDER BY c.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get customer by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get customer with their jobs
     * 
     * @param int $id
     * @return array|false
     */
    public function getCustomerWithJobs(int $id): array|false
    {
        $customer = $this->getById($id);

        if ($customer) {
            $stmt = $this->db->prepare("
                SELECT * FROM jobs 
                WHERE customer_id = :id 
                ORDER BY created_at DESC
            ");
            $stmt->execute(['id' => $id]);
            $customer['jobs'] = $stmt->fetchAll();
        }

        return $customer;
    }

    /**
     * Create new customer
     * 
     * @param array $data
     * @return int|false
     */
    public function create(array $data): int|false
    {
        $stmt = $this->db->prepare("
            INSERT INTO customers (name, phone, email, address) 
            VALUES (:name, :phone, :email, :address)
        ");

        $result = $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? '',
            'email' => $data['email'] ?? '',
            'address' => $data['address'] ?? ''
        ]);

        return $result ? (int)$this->db->lastInsertId() : false;
    }

    /**
     * Update customer
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE customers SET 
                name = :name,
                phone = :phone,
                email = :email,
                address = :address
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? '',
            'email' => $data['email'] ?? '',
            'address' => $data['address'] ?? ''
        ]);
    }

    /**
     * Delete customer
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get customer statistics
     * 
     * @return array
     */
    public function getStats(): array
    {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_customers,
                (SELECT COUNT(*) FROM customers WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as new_this_month
            FROM customers
        ");
        return $stmt->fetch();
    }
}
