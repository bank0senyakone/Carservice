<?php $isEdit = isset($job); ?>
<div class="space-y-6">
    <a href="/car1/public/admin/jobs" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Jobs
    </a>

    <div>
        <h1 class="text-2xl font-bold text-gray-800"><?= $isEdit ? 'Edit Job' : 'New Job' ?></h1>
    </div>

    <form action="/car1/public/admin/jobs/<?= $isEdit ? $job['id'] . '/update' : 'store' ?>" method="POST" class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                <select name="customer_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select Customer</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= $customer['id'] ?>" <?= ($job['customer_id'] ?? '') == $customer['id'] ? 'selected' : '' ?>><?= htmlspecialchars($customer['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">License Plate *</label>
                <input type="text" name="license_plate" value="<?= htmlspecialchars($job['license_plate'] ?? '') ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Car Model *</label>
                <input type="text" name="car_model" value="<?= htmlspecialchars($job['car_model'] ?? '') ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Car Year</label>
                <input type="number" name="car_year" value="<?= $job['car_year'] ?? '' ?>" min="1900" max="2030" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">VIN</label>
                <input type="text" name="vin" value="<?= htmlspecialchars($job['vin'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <?php foreach (['Low', 'Medium', 'High'] as $p): ?>
                        <option value="<?= $p ?>" <?= ($job['priority'] ?? 'Medium') === $p ? 'selected' : '' ?>><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if ($isEdit): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <?php foreach (['Queue', 'In Progress', 'Waiting for Parts', 'Quality Check', 'Ready', 'Completed'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($job['status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Est. Completion</label>
                <input type="datetime-local" name="estimated_completion" value="<?= $job['estimated_completion'] ? date('Y-m-d\TH:i', strtotime($job['estimated_completion'])) : '' ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"><?= htmlspecialchars($job['description'] ?? '') ?></textarea>
        </div>
        <div class="flex justify-end gap-3">
            <a href="/car1/public/admin/jobs" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg"><?= $isEdit ? 'Update Job' : 'Create Job' ?></button>
        </div>
    </form>
</div>