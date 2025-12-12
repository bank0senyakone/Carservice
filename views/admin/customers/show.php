<div class="space-y-6">
    <a href="/car1/public/admin/customers" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Customers
    </a>

    <div class="flex items-start justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-2xl font-bold"><?= strtoupper(substr($customer['name'], 0, 1)) ?></div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($customer['name']) ?></h1>
                <p class="text-gray-500">Customer since <?= date('M Y', strtotime($customer['created_at'])) ?></p>
            </div>
        </div>
        <a href="/car1/public/admin/customers/<?= $customer['id'] ?>/edit" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Edit Customer</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Phone</p>
            <p class="font-semibold"><?= htmlspecialchars($customer['phone'] ?: 'Not provided') ?></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Email</p>
            <p class="font-semibold"><?= htmlspecialchars($customer['email'] ?: 'Not provided') ?></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Address</p>
            <p class="font-semibold"><?= htmlspecialchars($customer['address'] ?: 'Not provided') ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="font-semibold">Job History</h2>
            <a href="/car1/public/admin/jobs/create" class="text-sm text-primary-500 hover:text-primary-600">+ New Job</a>
        </div>
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Vehicle</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Description</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($customer['jobs'] ?? [] as $job): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><a href="/car1/public/admin/jobs/<?= $job['id'] ?>" class="font-medium text-primary-500 hover:underline"><?= htmlspecialchars($job['license_plate']) ?></a><span class="text-sm text-gray-500 ml-2"><?= htmlspecialchars($job['car_model']) ?></span></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($job['description'] ?: '-') ?></td>
                        <td class="px-6 py-4"><span class="status-badge bg-gray-100 text-gray-700"><?= $job['status'] ?></span></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= date('M j, Y', strtotime($job['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($customer['jobs'])): ?><tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No jobs yet</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>