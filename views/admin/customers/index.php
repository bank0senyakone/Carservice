<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Customers</h1>
            <p class="text-gray-500">Manage your customer database</p>
        </div>
        <a href="/car1/public/admin/customers/create" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-4 py-2.5 rounded-lg shadow-lg shadow-primary-500/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Customer
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['total_customers'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">Total Customers</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg></div>
            <div>
                <p class="text-2xl font-bold text-green-600"><?= $stats['new_this_month'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">New This Month</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-4">
        <form action="/car1/public/admin/customers" method="GET" class="relative max-w-md">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search customers..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Phone</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Jobs</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($customers as $customer): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold"><?= strtoupper(substr($customer['name'], 0, 1)) ?></div>
                                <span class="font-medium"><?= htmlspecialchars($customer['name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($customer['phone'] ?: '-') ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($customer['email'] ?: '-') ?></td>
                        <td class="px-6 py-4"><span class="bg-gray-100 text-gray-700 text-sm font-medium px-2.5 py-1 rounded"><?= $customer['job_count'] ?? 0 ?> jobs</span></td>
                        <td class="px-6 py-4 text-center">
                            <a href="/car1/public/admin/customers/<?= $customer['id'] ?>" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-primary-500 inline-block"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg></a>
                            <a href="/car1/public/admin/customers/<?= $customer['id'] ?>/edit" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-500 inline-block"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($customers)): ?><tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No customers found</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>