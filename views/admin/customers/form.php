<?php $isEdit = isset($customer); ?>
<div class="space-y-6">
    <a href="/car1/public/admin/customers" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Customers
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-800"><?= $isEdit ? 'Edit Customer' : 'New Customer' ?></h1>
    </div>

    <form action="/car1/public/admin/customers/<?= $isEdit ? $customer['id'] . '/update' : 'store' ?>" method="POST" class="bg-white rounded-xl shadow-sm border p-6 space-y-6 max-w-2xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($customer['name'] ?? '') ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
        </div>
        <div class="flex justify-end gap-3">
            <a href="/car1/public/admin/customers" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg"><?= $isEdit ? 'Update Customer' : 'Add Customer' ?></button>
        </div>
    </form>
</div>