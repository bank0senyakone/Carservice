<?php
$hour = (int)date('H');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
?>

<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Workshop Command Center</h1>
        <p class="text-gray-500"><?= $greeting ?>! Here's your shop overview for today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Active Repairs -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Repairs</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $jobStats['active_jobs'] ?? 0 ?></p>
                    <p class="text-sm text-gray-500 mt-1">Cars in shop</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Today's Revenue</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">$<?= number_format($todayRevenue ?? 0, 2) ?></p>
                    <p class="text-sm text-gray-500 mt-1">From completed jobs</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Avg Repair Time -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Avg. Repair Time</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">3.2h</p>
                    <p class="text-sm text-gray-500 mt-1">Per vehicle</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed Today -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Completed Today</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $jobStats['completed_today'] ?? 0 ?></p>
                    <p class="text-sm text-gray-500 mt-1">Jobs finished</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Repair Workflow -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Repair Workflow</h2>
            <p class="text-sm text-gray-500">Track and manage ongoing repairs</p>
        </div>

        <!-- Workflow Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php
            $workflowColumns = [
                'Queue' => ['icon' => '⏳', 'color' => 'gray'],
                'In Progress' => ['icon' => '🔧', 'color' => 'yellow'],
                'Waiting for Parts' => ['icon' => '📦', 'color' => 'orange'],
                'Quality Check' => ['icon' => '✓', 'color' => 'green']
            ];

            foreach ($workflowColumns as $status => $config):
                $jobs = $workflowJobs[$status] ?? [];
            ?>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-lg"><?= $config['icon'] ?></span>
                        <h3 class="font-medium text-gray-700"><?= $status ?></h3>
                        <span class="bg-gray-200 text-gray-600 text-xs font-medium px-2 py-0.5 rounded-full"><?= count($jobs) ?> jobs</span>
                    </div>

                    <div class="space-y-3">
                        <?php foreach ($jobs as $job):
                            $priorityColors = [
                                'Low' => 'bg-blue-100 text-blue-700',
                                'Medium' => 'bg-yellow-100 text-yellow-700',
                                'High' => 'bg-red-100 text-red-700'
                            ];
                        ?>
                            <a href="/car1/public/admin/jobs/<?= $job['id'] ?>" class="block bg-white rounded-lg p-4 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-slate-800 text-white text-xs font-medium px-2 py-1 rounded"><?= htmlspecialchars($job['license_plate']) ?></span>
                                    <span class="status-badge <?= $priorityColors[$job['priority']] ?? 'bg-gray-100 text-gray-700' ?>"><?= $job['priority'] ?></span>
                                </div>
                                <h4 class="font-semibold text-gray-800"><?= htmlspecialchars($job['car_model']) ?><?= $job['car_year'] ? ' ' . $job['car_year'] : '' ?></h4>
                                <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($job['description'] ?: 'General Service') ?></p>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                                    <span class="text-sm text-gray-500"><?= htmlspecialchars($job['customer_name']) ?></span>
                                    <?php if ($job['estimated_completion']): ?>
                                        <div class="flex items-center gap-1 text-xs text-gray-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <?= date('g:ia', strtotime($job['estimated_completion'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>

                        <?php if (empty($jobs)): ?>
                            <div class="text-center py-4 text-gray-400 text-sm">
                                No jobs in this stage
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Quick Actions & Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock Alert -->
        <?php if (!empty($lowStockItems)): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Low Stock Alert</h2>
                    <a href="/car1/public/admin/inventory?filter=low_stock" class="text-sm text-primary-500 hover:text-primary-600">View All</a>
                </div>
                <div class="space-y-3">
                    <?php foreach (array_slice($lowStockItems, 0, 5) as $item): ?>
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                            <div>
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($item['part_name']) ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($item['sku']) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-red-600"><?= $item['stock_level'] ?> left</p>
                                <p class="text-xs text-gray-500">Reorder: <?= $item['reorder_point'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="/car1/public/admin/jobs/create" class="flex items-center gap-3 p-4 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                    <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="font-medium text-gray-700">New Job</span>
                </a>
                <a href="/car1/public/admin/customers/create" class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <span class="font-medium text-gray-700">Add Customer</span>
                </a>
                <a href="/car1/public/admin/inventory/create" class="flex items-center gap-3 p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="font-medium text-gray-700">Add Part</span>
                </a>
                <a href="/car1/public/admin/quotes" class="flex items-center gap-3 p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="font-medium text-gray-700">View Invoices</span>
                </a>
            </div>
        </div>
    </div>
</div>