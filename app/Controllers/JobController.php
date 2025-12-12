<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\JobModel;
use App\Models\CustomerModel;
use App\Models\QuoteModel;

/**
 * Job Controller
 * Handles job/repair order management
 */
class JobController extends Controller
{
    private JobModel $jobModel;
    private CustomerModel $customerModel;
    private QuoteModel $quoteModel;

    public function __construct()
    {
        $this->jobModel = new JobModel();
        $this->customerModel = new CustomerModel();
        $this->quoteModel = new QuoteModel();
    }

    /**
     * List all jobs
     */
    public function index(): void
    {
        $status = $this->query('status');
        $jobs = $this->jobModel->getAll($status);
        $jobStats = $this->jobModel->getStats();

        $this->view('admin/jobs/index', [
            'pageTitle' => 'Jobs',
            'user' => $this->getCurrentUser(),
            'jobs' => $jobs,
            'jobStats' => $jobStats,
            'currentStatus' => $status,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Show job details
     */
    public function show(string $id): void
    {
        $job = $this->jobModel->getJobWithCustomer((int)$id);

        if (!$job) {
            $this->setFlash('error', 'Job not found');
            $this->redirect('/car1/public/admin/jobs');
            return;
        }

        // Get quote data if exists
        $quote = $this->quoteModel->getQuoteItemsForJob((int)$id);

        $this->view('admin/jobs/show', [
            'pageTitle' => $job['license_plate'] . ' - ' . $job['car_model'],
            'user' => $this->getCurrentUser(),
            'job' => $job,
            'quote' => $quote,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Show create job form
     */
    public function create(): void
    {
        $customers = $this->customerModel->getAll();

        $this->view('admin/jobs/form', [
            'pageTitle' => 'New Job',
            'user' => $this->getCurrentUser(),
            'job' => null,
            'customers' => $customers,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Store new job
     */
    public function store(): void
    {
        $data = [
            'customer_id' => $this->input('customer_id'),
            'license_plate' => $this->input('license_plate'),
            'car_model' => $this->input('car_model'),
            'car_year' => $this->input('car_year'),
            'vin' => $this->input('vin'),
            'description' => $this->input('description'),
            'status' => $this->input('status', 'Queue'),
            'priority' => $this->input('priority', 'Medium'),
            'estimated_completion' => $this->input('estimated_completion')
        ];

        // Validate required fields
        if (empty($data['customer_id']) || empty($data['license_plate']) || empty($data['car_model'])) {
            $this->setFlash('error', 'Please fill in all required fields');
            $this->redirect('/car1/public/admin/jobs/create');
            return;
        }

        $jobId = $this->jobModel->create($data);

        if ($jobId) {
            // Create an empty quote for the job
            $this->quoteModel->create(['job_id' => $jobId]);

            $this->setFlash('success', 'Job created successfully');
            $this->redirect('/car1/public/admin/jobs/' . $jobId);
        } else {
            $this->setFlash('error', 'Failed to create job');
            $this->redirect('/car1/public/admin/jobs/create');
        }
    }

    /**
     * Show edit job form
     */
    public function edit(string $id): void
    {
        $job = $this->jobModel->getById((int)$id);

        if (!$job) {
            $this->setFlash('error', 'Job not found');
            $this->redirect('/car1/public/admin/jobs');
            return;
        }

        $customers = $this->customerModel->getAll();

        $this->view('admin/jobs/form', [
            'pageTitle' => 'Edit Job',
            'user' => $this->getCurrentUser(),
            'job' => $job,
            'customers' => $customers,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Update job
     */
    public function update(string $id): void
    {
        $job = $this->jobModel->getById((int)$id);

        if (!$job) {
            $this->setFlash('error', 'Job not found');
            $this->redirect('/car1/public/admin/jobs');
            return;
        }

        $data = [
            'customer_id' => $this->input('customer_id'),
            'license_plate' => $this->input('license_plate'),
            'car_model' => $this->input('car_model'),
            'car_year' => $this->input('car_year'),
            'vin' => $this->input('vin'),
            'description' => $this->input('description'),
            'status' => $this->input('status'),
            'priority' => $this->input('priority'),
            'estimated_completion' => $this->input('estimated_completion')
        ];

        if ($this->jobModel->update((int)$id, $data)) {
            $this->setFlash('success', 'Job updated successfully');
            $this->redirect('/car1/public/admin/jobs/' . $id);
        } else {
            $this->setFlash('error', 'Failed to update job');
            $this->redirect('/car1/public/admin/jobs/' . $id . '/edit');
        }
    }

    /**
     * Update job status (AJAX)
     */
    public function updateStatus(string $id): void
    {
        $status = $this->input('status');

        if ($this->jobModel->updateStatus((int)$id, $status)) {
            $this->json(['success' => true, 'message' => 'Status updated']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to update status'], 400);
        }
    }

    /**
     * Delete job
     */
    public function delete(string $id): void
    {
        if ($this->jobModel->delete((int)$id)) {
            $this->setFlash('success', 'Job deleted successfully');
        } else {
            $this->setFlash('error', 'Failed to delete job');
        }
        $this->redirect('/car1/public/admin/jobs');
    }
}
