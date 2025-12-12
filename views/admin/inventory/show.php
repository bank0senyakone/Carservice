<div class="space-y-6">
    <a href="/car1/public/admin/inventory" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Inventory
    </a>
    <?php $isLow = $item['stock_level'] < $item['reorder_point']; ?>
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($item['part_name']) ?></h1>
            <p class="text-gray-500">SKU: <?= htmlspecialchars($item['sku']) ?></p>
        </div>
        <a href="/car1/public/admin/inventory/<?= $item['id'] ?>/edit" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Edit Part</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Category</p>
            <p class="font-semibold"><?= htmlspecialchars($item['category']) ?></p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Stock Level</p>
            <p class="font-semibold <?= $isLow ? 'text-red-600' : 'text-green-600' ?>"><?= $item['stock_level'] ?> units</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Reorder Point</p>
            <p class="font-semibold"><?= $item['reorder_point'] ?> units</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Unit Price</p>
            <p class="font-semibold text-primary-500">$<?= number_format($item['unit_price'], 2) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-semibold mb-2">Description</h3>
        <p class="text-gray-600"><?= htmlspecialchars($item['description'] ?: 'No description provided.') ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-semibold mb-4">Stock Status</h3>
        <div class="flex items-center gap-4">
            <div class="flex-1 h-4 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full <?= $isLow ? 'bg-red-500' : 'bg-green-500' ?>" style="width: <?= min(100, ($item['stock_level'] / max(1, $item['reorder_point'])) * 50) ?>%"></div>
            </div>
            <span class="status-badge <?= $isLow ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>"><?= $isLow ? '⚠ Low Stock' : '✓ In Stock' ?></span>
        </div>
    </div>
</div>