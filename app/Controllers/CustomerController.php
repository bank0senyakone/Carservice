<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CustomerModel;

/**
 * Customer Controller
 * Handles customer management
 */
class CustomerController extends Controller
{
    private CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    /**
     * List all customers
     */
    public function index(): void
    {
        $search = $this->query('search');
        $customers = $this->customerModel->getAll($search);
        $stats = $this->customerModel->getStats();

        $this->view('admin/customers/index', [
            'pageTitle' => 'Customers',
            'user' => $this->getCurrentUser(),
            'customers' => $customers,
            'stats' => $stats,
            'search' => $search,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Show customer details with jobs
     */
    public function show(string $id): void
    {
        $customer = $this->customerModel->getCustomerWithJobs((int)$id);

        if (!$customer) {
            $this->setFlash('error', 'Customer not found');
            $this->redirect('/car1/public/admin/customers');
            return;
        }

        $this->view('admin/customers/show', [
            'pageTitle' => $customer['name'],
            'user' => $this->getCurrentUser(),
            'customer' => $customer,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Show create customer form
     */
    public function create(): void
    {
        $this->view('admin/customers/form', [
            'pageTitle' => 'New Customer',
            'user' => $this->getCurrentUser(),
            'customer' => null,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Store new customer
     */
    public function store(): void
    {
        $data = [
            'name' => $this->input('name'),
            'phone' => $this->input('phone'),
            'email' => $this->input('email'),
            'address' => $this->input('address')
        ];

        // Validate required fields
        if (empty($data['name'])) {
            $this->setFlash('error', 'Customer name is required');
            $this->redirect('/car1/public/admin/customers/create');
            return;
        }

        $customerId = $this->customerModel->create($data);

        if ($customerId) {
            $this->setFlash('success', 'Customer created successfully');
            $this->redirect('/car1/public/admin/customers/' . $customerId);
        } else {
            $this->setFlash('error', 'Failed to create customer');
            $this->redirect('/car1/public/admin/customers/create');
        }
    }

    /**
     * Show edit customer form
     */
    public function edit(string $id): void
    {
        $customer = $this->customerModel->getById((int)$id);

        if (!$customer) {
            $this->setFlash('error', 'Customer not found');
            $this->redirect('/car1/public/admin/customers');
            return;
        }

        $this->view('admin/customers/form', [
            'pageTitle' => 'Edit Customer',
            'user' => $this->getCurrentUser(),
            'customer' => $customer,
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Update customer
     */
    public function update(string $id): void
    {
        $customer = $this->customerModel->getById((int)$id);

        if (!$customer) {
            $this->setFlash('error', 'Customer not found');
            $this->redirect('/car1/public/admin/customers');
            return;
        }

        $data = [
            'name' => $this->input('name'),
            'phone' => $this->input('phone'),
            'email' => $this->input('email'),
            'address' => $this->input('address')
        ];

        if ($this->customerModel->update((int)$id, $data)) {
            $this->setFlash('success', 'Customer updated successfully');
            $this->redirect('/car1/public/admin/customers/' . $id);
        } else {
            $this->setFlash('error', 'Failed to update customer');
            $this->redirect('/car1/public/admin/customers/' . $id . '/edit');
        }
    }

    /**
     * Delete customer
     */
    public function delete(string $id): void
    {
        if ($this->customerModel->delete((int)$id)) {
            $this->setFlash('success', 'Customer deleted successfully');
        } else {
            $this->setFlash('error', 'Failed to delete customer');
        }
        $this->redirect('/car1/public/admin/customers');
    }
}
