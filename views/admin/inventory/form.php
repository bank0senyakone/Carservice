<?php $isEdit = isset($item); ?>
<div class="space-y-6">
    <a href="/car1/public/admin/inventory" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Inventory
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-800"><?= $isEdit ? 'Edit Part' : 'Add New Part' ?></h1>
    </div>

    <form action="/car1/public/admin/inventory/<?= $isEdit ? $item['id'] . '/update' : 'store' ?>" method="POST" class="bg-white rounded-xl shadow-sm border p-6 space-y-6 max-w-2xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Part Name *</label>
                <input type="text" name="part_name" value="<?= htmlspecialchars($item['part_name'] ?? '') ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                <input type="text" name="sku" value="<?= htmlspecialchars($item['sku'] ?? '') ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <input type="text" name="category" value="<?= htmlspecialchars($item['category'] ?? '') ?>" required list="categories" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                <datalist id="categories"><?php foreach ($categories ?? [] as $cat): ?><option value="<?= htmlspecialchars($cat) ?>"><?php endforeach; ?></datalist>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Level</label>
                <input type="number" name="stock_level" value="<?= $item['stock_level'] ?? 0 ?>" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price ($)</label>
                <input type="number" name="unit_price" value="<?= $item['unit_price'] ?? 0 ?>" min="0" step="0.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reorder Point</label>
                <input type="number" name="reorder_point" value="<?= $item['reorder_point'] ?? 10 ?>" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
        </div>
        <div class="flex justify-end gap-3">
            <a href="/car1/public/admin/inventory" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg"><?= $isEdit ? 'Update Part' : 'Add Part' ?></button>
        </div>
    </form>
</div>