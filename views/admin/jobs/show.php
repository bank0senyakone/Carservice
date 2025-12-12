<?php
$statusColors = [
    'Queue' => 'bg-gray-100 text-gray-700',
    'In Progress' => 'bg-yellow-100 text-yellow-700',
    'Waiting for Parts' => 'bg-orange-100 text-orange-700',
    'Quality Check' => 'bg-blue-100 text-blue-700',
    'Ready' => 'bg-green-100 text-green-700',
    'Completed' => 'bg-green-100 text-green-700'
];
?>
<div class="space-y-6">
    <a href="/car1/public/admin/dashboard" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Dashboard
    </a>

    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="bg-primary-500 text-white text-sm font-bold px-3 py-1.5 rounded-lg"><?= htmlspecialchars($job['license_plate']) ?></span>
                <span class="status-badge <?= $statusColors[$job['status']] ?>"><?= $job['status'] ?></span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($job['car_model']) ?> <?= $job['car_year'] ?></h1>
        </div>
        <a href="/car1/public/admin/jobs/<?= $job['id'] ?>/edit" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Edit Job</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Customer</p>
            <p class="font-semibold"><?= htmlspecialchars($job['customer_name']) ?></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">VIN</p>
            <p class="font-semibold"><?= htmlspecialchars($job['vin'] ?: 'N/A') ?></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Est. Completion</p>
            <p class="font-semibold"><?= $job['estimated_completion'] ? date('M j, g:i A', strtotime($job['estimated_completion'])) : 'Not set' ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border">
        <h3 class="font-semibold mb-4">Update Status</h3>
        <div class="flex flex-wrap gap-2">
            <?php foreach (['Queue', 'In Progress', 'Waiting for Parts', 'Quality Check', 'Ready', 'Completed'] as $status): ?>
                <button onclick="updateJobStatus(<?= $job['id'] ?>, '<?= $status ?>')" class="px-4 py-2 rounded-lg text-sm font-medium <?= $job['status'] === $status ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>"><?= $status ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">Service Items</h3>
                        <p class="text-sm text-gray-500">Labor charges</p>
                    </div>
                    <button class="bg-primary-500 text-white text-sm px-3 py-1.5 rounded-lg">+ Add Service</button>
                </div>
                <div class="divide-y">
                    <?php $laborItems = array_filter($quote['items'] ?? [], fn($i) => $i['item_type'] === 'Labor');
                    foreach ($laborItems as $item): ?>
                        <div class="px-6 py-4 flex justify-between"><span><?= htmlspecialchars($item['description']) ?></span><span class="font-semibold">$<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></span></div>
                    <?php endforeach;
                    if (empty($laborItems)): ?><div class="px-6 py-8 text-center text-gray-500">No services</div><?php endif; ?>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">Spare Parts</h3>
                        <p class="text-sm text-gray-500">Materials used</p>
                    </div>
                    <button class="bg-green-500 text-white text-sm px-3 py-1.5 rounded-lg">+ Add Part</button>
                </div>
                <div class="divide-y">
                    <?php $partItems = array_filter($quote['items'] ?? [], fn($i) => $i['item_type'] === 'Part');
                    foreach ($partItems as $item): ?>
                        <div class="px-6 py-4 flex justify-between"><span><?= htmlspecialchars($item['description']) ?></span><span class="font-semibold">$<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></span></div>
                    <?php endforeach;
                    if (empty($partItems)): ?><div class="px-6 py-8 text-center text-gray-500">No parts</div><?php endif; ?>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-6">
                <h3 class="font-semibold mb-4">Cost Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Labor</span><span>$<?= number_format($quote['labor_total'] ?? 0, 2) ?></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Parts</span><span>$<?= number_format($quote['parts_total'] ?? 0, 2) ?></span></div>
                    <div class="border-t pt-3 flex justify-between"><span class="text-gray-500">Subtotal</span><span>$<?= number_format($quote['subtotal'] ?? 0, 2) ?></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Tax (<?= $quote['tax_rate'] ?? 8 ?>%)</span><span>$<?= number_format($quote['tax_amount'] ?? 0, 2) ?></span></div>
                    <div class="border-t pt-3 flex justify-between"><span class="font-medium">Total</span><span class="text-2xl font-bold text-primary-500">$<?= number_format($quote['grand_total'] ?? 0, 2) ?></span></div>
                </div>
                <button class="w-full mt-6 bg-primary-500 text-white font-semibold py-3 rounded-lg">Complete & Generate Invoice</button>
            </div>
        </div>
    </div>
</div>