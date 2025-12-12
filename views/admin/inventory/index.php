<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Inventory Management</h1>
            <p class="text-gray-500">Track and manage your spare parts inventory</p>
        </div>
        <a href="/car1/public/admin/inventory/create" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-4 py-2.5 rounded-lg shadow-lg shadow-primary-500/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Part
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center"><svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg></div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['total_items'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">Total Items</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg></div>
            <div>
                <p class="text-2xl font-bold text-red-600"><?= $stats['low_stock_count'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">Low Stock Items</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg></div>
            <div>
                <p class="text-2xl font-bold text-green-600"><?= $stats['in_stock_count'] ?? 0 ?></p>
                <p class="text-sm text-gray-500">Well Stocked</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-4 flex flex-wrap items-center gap-4">
        <form action="/car1/public/admin/inventory" method="GET" class="flex-1 min-w-[200px] relative">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search by name or SKU..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </form>
        <div class="flex gap-2">
            <a href="/car1/public/admin/inventory" class="px-4 py-2 rounded-lg text-sm font-medium <?= !$filter ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">All Items</a>
            <a href="/car1/public/admin/inventory?filter=low_stock" class="px-4 py-2 rounded-lg text-sm font-medium <?= $filter === 'low_stock' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">Low Stock</a>
            <a href="/car1/public/admin/inventory?filter=in_stock" class="px-4 py-2 rounded-lg text-sm font-medium <?= $filter === 'in_stock' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">In Stock</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Part Name</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">SKU</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Category</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stock Level</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Unit Price</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($items as $item): $isLow = $item['stock_level'] < $item['reorder_point']; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium"><?= htmlspecialchars($item['part_name']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($item['sku']) ?></td>
                        <td class="px-6 py-4 text-sm"><?= htmlspecialchars($item['category']) ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full <?= $isLow ? 'bg-red-500' : 'bg-green-500' ?>" style="width: <?= min(100, ($item['stock_level'] / max(1, $item['reorder_point'])) * 50) ?>%"></div>
                                </div>
                                <span class="text-sm"><?= $item['stock_level'] ?>/<?= $item['reorder_point'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="status-badge <?= $isLow ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>"><?= $isLow ? '⚠ Low Stock' : '✓ In Stock' ?></span>
                        </td>
                        <td class="px-6 py-4 font-medium">$<?= number_format($item['unit_price'], 2) ?></td>
                        <td class="px-6 py-4 text-center">
                            <a href="/car1/public/admin/inventory/<?= $item['id'] ?>/edit" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-500 inline-block"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?><tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">No items found</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>