<?php

namespace App\Models;

use App\Core\DB;

/**
 * Quote Model
 * Handles quotes and quote items data access
 */
class QuoteModel
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = DB::getInstance()->getConnection();
    }

    /**
     * Get all quotes
     * 
     * @return array
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT q.*, j.license_plate, j.car_model, c.name as customer_name
            FROM quotes q
            JOIN jobs j ON q.job_id = j.id
            JOIN customers c ON j.customer_id = c.id
            ORDER BY q.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Get quote by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT q.*, j.license_plate, j.car_model, j.vin, c.name as customer_name
            FROM quotes q
            JOIN jobs j ON q.job_id = j.id
            JOIN customers c ON j.customer_id = c.id
            WHERE q.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get quote by job ID
     * 
     * @param int $jobId
     * @return array|false
     */
    public function getByJob(int $jobId): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM quotes WHERE job_id = :job_id");
        $stmt->execute(['job_id' => $jobId]);
        return $stmt->fetch();
    }

    /**
     * Get quote with items
     * 
     * @param int $id
     * @return array|false
     */
    public function getQuoteWithItems(int $id): array|false
    {
        $quote = $this->getById($id);

        if ($quote) {
            $stmt = $this->db->prepare("
                SELECT qi.*, i.part_name, i.sku
                FROM quote_items qi
                LEFT JOIN inventory i ON qi.inventory_id = i.id
                WHERE qi.quote_id = :id
                ORDER BY qi.item_type ASC, qi.id ASC
            ");
            $stmt->execute(['id' => $id]);
            $quote['items'] = $stmt->fetchAll();

            // Calculate totals
            $laborTotal = 0;
            $partsTotal = 0;
            foreach ($quote['items'] as $item) {
                if ($item['item_type'] === 'Labor') {
                    $laborTotal += $item['unit_price'] * $item['quantity'];
                } else {
                    $partsTotal += $item['unit_price'] * $item['quantity'];
                }
            }
            $quote['labor_total'] = $laborTotal;
            $quote['parts_total'] = $partsTotal;
            $quote['subtotal'] = $laborTotal + $partsTotal;
            $quote['tax_amount'] = $quote['subtotal'] * ($quote['tax_rate'] / 100);
            $quote['grand_total'] = $quote['subtotal'] + $quote['tax_amount'];
        }

        return $quote;
    }

    /**
     * Get quote items for a job
     * 
     * @param int $jobId
     * @return array
     */
    public function getQuoteItemsForJob(int $jobId): array
    {
        $quote = $this->getByJob($jobId);

        if ($quote) {
            return $this->getQuoteWithItems($quote['id']);
        }

        return [];
    }

    /**
     * Create new quote
     * 
     * @param array $data
     * @return int|false
     */
    public function create(array $data): int|false
    {
        $stmt = $this->db->prepare("
            INSERT INTO quotes (job_id, total_amount, tax_rate, status, notes) 
            VALUES (:job_id, :total_amount, :tax_rate, :status, :notes)
        ");

        $result = $stmt->execute([
            'job_id' => $data['job_id'],
            'total_amount' => $data['total_amount'] ?? 0,
            'tax_rate' => $data['tax_rate'] ?? 8,
            'status' => $data['status'] ?? 'Pending',
            'notes' => $data['notes'] ?? ''
        ]);

        return $result ? (int)$this->db->lastInsertId() : false;
    }

    /**
     * Add item to quote
     * 
     * @param int $quoteId
     * @param array $data
     * @return int|false
     */
    public function addItem(int $quoteId, array $data): int|false
    {
        $stmt = $this->db->prepare("
            INSERT INTO quote_items (quote_id, inventory_id, description, item_type, unit_price, quantity, hours) 
            VALUES (:quote_id, :inventory_id, :description, :item_type, :unit_price, :quantity, :hours)
        ");

        $result = $stmt->execute([
            'quote_id' => $quoteId,
            'inventory_id' => $data['inventory_id'] ?? null,
            'description' => $data['description'],
            'item_type' => $data['item_type'],
            'unit_price' => $data['unit_price'],
            'quantity' => $data['quantity'] ?? 1,
            'hours' => $data['hours'] ?? null
        ]);

        if ($result) {
            $this->calculateTotal($quoteId);
        }

        return $result ? (int)$this->db->lastInsertId() : false;
    }

    /**
     * Remove item from quote
     * 
     * @param int $itemId
     * @return bool
     */
    public function removeItem(int $itemId): bool
    {
        // Get quote ID first
        $stmt = $this->db->prepare("SELECT quote_id FROM quote_items WHERE id = :id");
        $stmt->execute(['id' => $itemId]);
        $item = $stmt->fetch();

        if (!$item) return false;

        $stmt = $this->db->prepare("DELETE FROM quote_items WHERE id = :id");
        $result = $stmt->execute(['id' => $itemId]);

        if ($result) {
            $this->calculateTotal($item['quote_id']);
        }

        return $result;
    }

    /**
     * Calculate and update quote total
     * 
     * @param int $quoteId
     * @return float
     */
    public function calculateTotal(int $quoteId): float
    {
        $stmt = $this->db->prepare("
            SELECT SUM(unit_price * quantity) as total
            FROM quote_items
            WHERE quote_id = :id
        ");
        $stmt->execute(['id' => $quoteId]);
        $result = $stmt->fetch();
        $total = (float)($result['total'] ?? 0);

        $stmt = $this->db->prepare("UPDATE quotes SET total_amount = :total WHERE id = :id");
        $stmt->execute(['total' => $total, 'id' => $quoteId]);

        return $total;
    }

    /**
     * Approve quote
     * 
     * @param int $id
     * @return bool
     */
    public function approve(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE quotes SET 
                status = 'Approved', 
                approved_at = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Reject quote
     * 
     * @param int $id
     * @return bool
     */
    public function reject(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE quotes SET status = 'Rejected' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Update quote
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE quotes SET 
                tax_rate = :tax_rate,
                notes = :notes
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'tax_rate' => $data['tax_rate'] ?? 8,
            'notes' => $data['notes'] ?? ''
        ]);
    }

    /**
     * Delete quote
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM quotes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
