<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\InventoryModel;

/**
 * Inventory Controller
 * Handles inventory/parts management
 */
class InventoryController extends Controller
{
    private InventoryModel $inventoryModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
    }

    /**
     * List all inventory items
     */
    public function index(): void
    {
        $search = $this->query('search');
        $filter = $this->query('filter');

        $items = $this->inventoryModel->getAll($search, $filter);
        $stats = $this->inventoryModel->getStats();
        $categories = $this->inventoryModel->getCategories();

        $this->view('admin/inventory/index', [
            'pageTitle' => 'Inventory',
            'user' => $this->getCurrentUser(),
            'items' => $items,
            'stats' => $stats,
            'categories' => $categories,
            'search' => $search,
            'filter' => $filter,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Show inventory item details
     */
    public function show(string $id): void
    {
        $item = $this->inventoryModel->getById((int)$id);

        if (!$item) {
            $this->setFlash('error', 'Item not found');
            $this->redirect('/car1/public/admin/inventory');
            return;
        }

        $this->view('admin/inventory/show', [
            'pageTitle' => $item['part_name'],
            'user' => $this->getCurrentUser(),
            'item' => $item,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Show create inventory form
     */
    public function create(): void
    {
        $categories = $this->inventoryModel->getCategories();

        $this->view('admin/inventory/form', [
            'pageTitle' => 'Add New Part',
            'user' => $this->getCurrentUser(),
            'item' => null,
            'categories' => $categories,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Store new inventory item
     */
    public function store(): void
    {
        $data = [
            'part_name' => $this->input('part_name'),
            'sku' => $this->input('sku'),
            'category' => $this->input('category'),
            'description' => $this->input('description'),
            'stock_level' => (int)$this->input('stock_level', 0),
            'unit_price' => (float)$this->input('unit_price', 0),
            'reorder_point' => (int)$this->input('reorder_point', 10)
        ];

        // Validate required fields
        if (empty($data['part_name']) || empty($data['sku']) || empty($data['category'])) {
            $this->setFlash('error', 'Please fill in all required fields');
            $this->redirect('/car1/public/admin/inventory/create');
            return;
        }

        // Check if SKU already exists
        if ($this->inventoryModel->getBySku($data['sku'])) {
            $this->setFlash('error', 'SKU already exists');
            $this->redirect('/car1/public/admin/inventory/create');
            return;
        }

        $itemId = $this->inventoryModel->create($data);

        if ($itemId) {
            $this->setFlash('success', 'Item added successfully');
            $this->redirect('/car1/public/admin/inventory');
        } else {
            $this->setFlash('error', 'Failed to add item');
            $this->redirect('/car1/public/admin/inventory/create');
        }
    }

    /**
     * Show edit inventory form
     */
    public function edit(string $id): void
    {
        $item = $this->inventoryModel->getById((int)$id);

        if (!$item) {
            $this->setFlash('error', 'Item not found');
            $this->redirect('/car1/public/admin/inventory');
            return;
        }

        $categories = $this->inventoryModel->getCategories();

        $this->view('admin/inventory/form', [
            'pageTitle' => 'Edit Part',
            'user' => $this->getCurrentUser(),
            'item' => $item,
            'categories' => $categories,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Update inventory item
     */
    public function update(string $id): void
    {
        $item = $this->inventoryModel->getById((int)$id);

        if (!$item) {
            $this->setFlash('error', 'Item not found');
            $this->redirect('/car1/public/admin/inventory');
            return;
        }

        $data = [
            'part_name' => $this->input('part_name'),
            'sku' => $this->input('sku'),
            'category' => $this->input('category'),
            'description' => $this->input('description'),
            'stock_level' => (int)$this->input('stock_level', 0),
            'unit_price' => (float)$this->input('unit_price', 0),
            'reorder_point' => (int)$this->input('reorder_point', 10)
        ];

        // Check if SKU is being changed and if new SKU exists
        if ($data['sku'] !== $item['sku']) {
            $existing = $this->inventoryModel->getBySku($data['sku']);
            if ($existing) {
                $this->setFlash('error', 'SKU already exists');
                $this->redirect('/car1/public/admin/inventory/' . $id . '/edit');
                return;
            }
        }

        if ($this->inventoryModel->update((int)$id, $data)) {
            $this->setFlash('success', 'Item updated successfully');
            $this->redirect('/car1/public/admin/inventory');
        } else {
            $this->setFlash('error', 'Failed to update item');
            $this->redirect('/car1/public/admin/inventory/' . $id . '/edit');
        }
    }

    /**
     * Delete inventory item
     */
    public function delete(string $id): void
    {
        if ($this->inventoryModel->delete((int)$id)) {
            $this->setFlash('success', 'Item deleted successfully');
        } else {
            $this->setFlash('error', 'Failed to delete item');
        }
        $this->redirect('/car1/public/admin/inventory');
    }

    /**
     * Show low stock items only
     */
    public function lowStock(): void
    {
        $items = $this->inventoryModel->getLowStock();
        $stats = $this->inventoryModel->getStats();

        $this->view('admin/inventory/index', [
            'pageTitle' => 'Low Stock Items',
            'user' => $this->getCurrentUser(),
            'items' => $items,
            'stats' => $stats,
            'categories' => [],
            'search' => null,
            'filter' => 'low_stock',
            'flash' => $this->getFlash()
        ]);
    }
}
