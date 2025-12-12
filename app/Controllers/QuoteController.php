<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\QuoteModel;
use App\Models\JobModel;
use App\Models\InventoryModel;

/**
 * Quote Controller
 * Handles quotes/invoices management
 */
class QuoteController extends Controller
{
    private QuoteModel $quoteModel;
    private JobModel $jobModel;
    private InventoryModel $inventoryModel;

    public function __construct()
    {
        $this->quoteModel = new QuoteModel();
        $this->jobModel = new JobModel();
        $this->inventoryModel = new InventoryModel();
    }

    /**
     * List all quotes
     */
    public function index(): void
    {
        $quotes = $this->quoteModel->getAll();

        $this->view('admin/quotes/index', [
            'pageTitle' => 'Invoices',
            'user' => $this->getCurrentUser(),
            'quotes' => $quotes,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Show quote details
     */
    public function show(string $id): void
    {
        $quote = $this->quoteModel->getQuoteWithItems((int)$id);

        if (!$quote) {
            $this->setFlash('error', 'Quote not found');
            $this->redirect('/car1/public/admin/quotes');
            return;
        }

        $this->view('admin/quotes/show', [
            'pageTitle' => 'Invoice #' . $id,
            'user' => $this->getCurrentUser(),
            'quote' => $quote,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Approve quote
     */
    public function approve(string $id): void
    {
        $quote = $this->quoteModel->getById((int)$id);

        if (!$quote) {
            $this->setFlash('error', 'Quote not found');
            $this->redirect('/car1/public/admin/quotes');
            return;
        }

        if ($this->quoteModel->approve((int)$id)) {
            $this->setFlash('success', 'Quote approved successfully');
        } else {
            $this->setFlash('error', 'Failed to approve quote');
        }

        $this->redirect('/car1/public/admin/quotes/' . $id);
    }

    /**
     * Add item to quote (AJAX)
     */
    public function addItem(string $id): void
    {
        $data = [
            'description' => $this->input('description'),
            'item_type' => $this->input('item_type'),
            'unit_price' => (float)$this->input('unit_price'),
            'quantity' => (float)$this->input('quantity', 1),
            'hours' => $this->input('hours'),
            'inventory_id' => $this->input('inventory_id')
        ];

        $itemId = $this->quoteModel->addItem((int)$id, $data);

        if ($itemId) {
            $this->json(['success' => true, 'item_id' => $itemId]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to add item'], 400);
        }
    }

    /**
     * Remove item from quote (AJAX)
     */
    public function removeItem(string $id, string $itemId): void
    {
        if ($this->quoteModel->removeItem((int)$itemId)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to remove item'], 400);
        }
    }
}
