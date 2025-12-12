<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\JobModel;
use App\Models\InventoryModel;
use App\Models\CustomerModel;

/**
 * Dashboard Controller
 * Handles the main admin dashboard
 */
class DashboardController extends Controller
{
    private JobModel $jobModel;
    private InventoryModel $inventoryModel;
    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->jobModel = new JobModel();
        $this->inventoryModel = new InventoryModel();
        $this->customerModel = new CustomerModel();
    }

    /**
     * Display dashboard with stats and workflow
     */
    public function index(): void
    {
        // Get job statistics
        $jobStats = $this->jobModel->getStats();
        $todayRevenue = $this->jobModel->getTodayRevenue();

        // Get jobs grouped by status for workflow board
        $workflowJobs = $this->jobModel->getJobsByStatusGroups();

        // Get inventory stats for low stock alerts
        $inventoryStats = $this->inventoryModel->getStats();
        $lowStockItems = $this->inventoryModel->getLowStock();

        // Get customer stats
        $customerStats = $this->customerModel->getStats();

        $this->view('admin/dashboard', [
            'pageTitle' => 'Dashboard',
            'user' => $this->getCurrentUser(),
            'jobStats' => $jobStats,
            'todayRevenue' => $todayRevenue,
            'workflowJobs' => $workflowJobs,
            'inventoryStats' => $inventoryStats,
            'lowStockItems' => $lowStockItems,
            'customerStats' => $customerStats,
            'flash' => $this->getFlash()
        ]);
    }
}
