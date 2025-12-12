<?php

namespace App\Models;

use App\Core\DB;

/**
 * Inventory Model
 * Handles inventory/parts data access
 */
class InventoryModel
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = DB::getInstance()->getConnection();
    }

    /**
     * Get all inventory items
     * 
     * @param string|null $search Search query
     * @param string|null $filter Filter type (low_stock, in_stock)
     * @return array
     */
    public function getAll(?string $search = null, ?string $filter = null): array
    {
        $sql = "SELECT * FROM inventory WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (part_name LIKE :search OR sku LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        if ($filter === 'low_stock') {
            $sql .= " AND stock_level < reorder_point";
        } elseif ($filter === 'in_stock') {
            $sql .= " AND stock_level >= reorder_point";
        }

        $sql .= " ORDER BY part_name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get inventory item by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get inventory item by SKU
     * 
     * @param string $sku
     * @return array|false
     */
    public function getBySku(string $sku): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory WHERE sku = :sku");
        $stmt->execute(['sku' => $sku]);
        return $stmt->fetch();
    }

    /**
     * Create new inventory item
     * 
     * @param array $data
     * @return int|false
     */
    public function create(array $data): int|false
    {
        $stmt = $this->db->prepare("
            INSERT INTO inventory (part_name, sku, category, description, stock_level, unit_price, reorder_point) 
            VALUES (:part_name, :sku, :category, :description, :stock_level, :unit_price, :reorder_point)
        ");

        $result = $stmt->execute([
            'part_name' => $data['part_name'],
            'sku' => $data['sku'],
            'category' => $data['category'],
            'description' => $data['description'] ?? '',
            'stock_level' => $data['stock_level'] ?? 0,
            'unit_price' => $data['unit_price'] ?? 0,
            'reorder_point' => $data['reorder_point'] ?? 10
        ]);

        return $result ? (int)$this->db->lastInsertId() : false;
    }

    /**
     * Update inventory item
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE inventory SET 
                part_name = :part_name,
                sku = :sku,
                category = :category,
                description = :description,
                stock_level = :stock_level,
                unit_price = :unit_price,
                reorder_point = :reorder_point
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'part_name' => $data['part_name'],
            'sku' => $data['sku'],
            'category' => $data['category'],
            'description' => $data['description'] ?? '',
            'stock_level' => $data['stock_level'] ?? 0,
            'unit_price' => $data['unit_price'] ?? 0,
            'reorder_point' => $data['reorder_point'] ?? 10
        ]);
    }

    /**
     * Delete inventory item
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM inventory WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get low stock items
     * 
     * @return array
     */
    public function getLowStock(): array
    {
        $stmt = $this->db->query("SELECT * FROM inventory WHERE stock_level < reorder_point ORDER BY stock_level ASC");
        return $stmt->fetchAll();
    }

    /**
     * Update stock level
     * 
     * @param int $id
     * @param int $quantity Amount to add (negative to subtract)
     * @return bool
     */
    public function updateStock(int $id, int $quantity): bool
    {
        $stmt = $this->db->prepare("UPDATE inventory SET stock_level = stock_level + :quantity WHERE id = :id");
        return $stmt->execute(['id' => $id, 'quantity' => $quantity]);
    }

    /**
     * Get inventory statistics
     * 
     * @return array
     */
    public function getStats(): array
    {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_items,
                SUM(CASE WHEN stock_level < reorder_point THEN 1 ELSE 0 END) as low_stock_count,
                SUM(CASE WHEN stock_level >= reorder_point THEN 1 ELSE 0 END) as in_stock_count,
                SUM(stock_level * unit_price) as total_value
            FROM inventory
        ");
        return $stmt->fetch();
    }

    /**
     * Get distinct categories
     * 
     * @return array
     */
    public function getCategories(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT category FROM inventory ORDER BY category ASC");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
