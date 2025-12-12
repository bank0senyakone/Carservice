<div class="space-y-6">
    <a href="/car1/public/admin/quotes" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Invoices
    </a>

    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Invoice #<?= str_pad($quote['id'], 4, '0', STR_PAD_LEFT) ?></h1>
            <p class="text-gray-500"><?= htmlspecialchars($quote['customer_name']) ?> - <?= htmlspecialchars($quote['license_plate']) ?> <?= htmlspecialchars($quote['car_model']) ?></p>
        </div>
        <?php if ($quote['status'] === 'Pending'): ?>
            <form action="/car1/public/admin/quotes/<?= $quote['id'] ?>/approve" method="POST">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-medium px-4 py-2 rounded-lg">Approve Invoice</button>
            </form>
        <?php else: ?>
            <span class="status-badge <?= $quote['status'] === 'Approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>"><?= $quote['status'] ?></span>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-semibold mb-4">Line Items</h3>
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left pb-3 text-sm text-gray-500">Description</th>
                    <th class="text-left pb-3 text-sm text-gray-500">Type</th>
                    <th class="text-right pb-3 text-sm text-gray-500">Qty</th>
                    <th class="text-right pb-3 text-sm text-gray-500">Unit Price</th>
                    <th class="text-right pb-3 text-sm text-gray-500">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($quote['items'] ?? [] as $item): ?>
                    <tr>
                        <td class="py-3"><?= htmlspecialchars($item['description']) ?></td>
                        <td class="py-3"><span class="status-badge <?= $item['item_type'] === 'Labor' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' ?>"><?= $item['item_type'] ?></span></td>
                        <td class="py-3 text-right"><?= $item['quantity'] ?></td>
                        <td class="py-3 text-right">$<?= number_format($item['unit_price'], 2) ?></td>
                        <td class="py-3 text-right font-medium">$<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="border-t mt-4 pt-4 space-y-2 max-w-xs ml-auto">
            <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span>$<?= number_format($quote['subtotal'] ?? 0, 2) ?></span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">Tax (<?= $quote['tax_rate'] ?>%)</span><span>$<?= number_format($quote['tax_amount'] ?? 0, 2) ?></span></div>
            <div class="flex justify-between font-bold text-lg border-t pt-2"><span>Total</span><span class="text-primary-500">$<?= number_format($quote['grand_total'] ?? 0, 2) ?></span></div>
        </div>
    </div>
</div>