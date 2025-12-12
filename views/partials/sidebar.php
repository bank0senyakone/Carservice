<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/car1/public';

function isActive($path, $currentPath, $basePath)
{
    $fullPath = $basePath . $path;
    return strpos($currentPath, $fullPath) === 0 ? 'active' : '';
}
?>
<aside class="w-64 bg-sidebar-darker text-white flex flex-col">
    <!-- Logo -->
    <div class="p-4 flex items-center gap-3 border-b border-gray-700">
        <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        <div>
            <h1 class="font-bold text-lg">AutoFlow</h1>
            <p class="text-xs text-gray-400">Workshop Manager</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4">
        <a href="<?= $basePath ?>/admin/dashboard"
            class="sidebar-link <?= isActive('/admin/dashboard', $currentPath, $basePath) ?> flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="<?= $basePath ?>/admin/jobs"
            class="sidebar-link <?= isActive('/admin/jobs', $currentPath, $basePath) ?> flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>Jobs</span>
        </a>

        <a href="<?= $basePath ?>/admin/inventory"
            class="sidebar-link <?= isActive('/admin/inventory', $currentPath, $basePath) ?> flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span>Inventory</span>
        </a>

        <a href="<?= $basePath ?>/admin/customers"
            class="sidebar-link <?= isActive('/admin/customers', $currentPath, $basePath) ?> flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <span>Customers</span>
        </a>

        <a href="<?= $basePath ?>/admin/quotes"
            class="sidebar-link <?= isActive('/admin/quotes', $currentPath, $basePath) ?> flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Invoices</span>
        </a>

        <div class="border-t border-gray-700 mt-4 pt-4">
            <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Settings</span>
            </a>
        </div>
    </nav>

    <!-- Collapse Button -->
    <div class="p-4 border-t border-gray-700">
        <button class="flex items-center gap-2 text-gray-400 hover:text-white text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <span>Collapse</span>
        </button>
    </div>
</aside>