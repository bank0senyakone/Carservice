<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Invoices</h1>
            <p class="text-gray-500">Manage quotes and invoices</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Invoice #</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Vehicle</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($quotes as $quote): $statusColors = ['Pending' => 'bg-yellow-100 text-yellow-700', 'Approved' => 'bg-green-100 text-green-700', 'Rejected' => 'bg-red-100 text-red-700']; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">#<?= str_pad($quote['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($quote['customer_name']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($quote['license_plate']) ?> - <?= htmlspecialchars($quote['car_model']) ?></td>
                        <td class="px-6 py-4 font-semibold">$<?= number_format($quote['total_amount'] * (1 + $quote['tax_rate'] / 100), 2) ?></td>
                        <td class="px-6 py-4"><span class="status-badge <?= $statusColors[$quote['status']] ?? 'bg-gray-100 text-gray-700' ?>"><?= $quote['status'] ?></span></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= date('M j, Y', strtotime($quote['created_at'])) ?></td>
                        <td class="px-6 py-4 text-center">
                            <a href="/car1/public/admin/quotes/<?= $quote['id'] ?>" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-primary-500 inline-block"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($quotes)): ?><tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">No invoices found</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>