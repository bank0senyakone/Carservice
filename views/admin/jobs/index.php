<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jobs</h1>
            <p class="text-gray-500">Manage all repair jobs and work orders</p>
        </div>
        <a href="/car1/public/admin/jobs/create" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-4 py-2.5 rounded-lg transition-colors shadow-lg shadow-primary-500/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Job
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $jobStats['total_jobs'] ?? 0 ?></p>
                    <p class="text-sm text-gray-500">Total Jobs</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $jobStats['queue_count'] ?? 0 ?></p>
                    <p class="text-sm text-gray-500">In Queue</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $jobStats['in_progress_count'] ?? 0 ?></p>
                    <p class="text-sm text-gray-500">In Progress</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $jobStats['completed_today'] ?? 0 ?></p>
                    <p class="text-sm text-gray-500">Completed Today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 bg-white rounded-xl p-2 shadow-sm border border-gray-100 w-fit">
        <a href="/car1/public/admin/jobs" class="px-4 py-2 rounded-lg text-sm font-medium <?= !$currentStatus ? 'bg-primary-500 text-white' : 'text-gray-600 hover:bg-gray-100' ?> transition-colors">
            All Jobs
        </a>
        <a href="/car1/public/admin/jobs?status=Queue" class="px-4 py-2 rounded-lg text-sm font-medium <?= $currentStatus === 'Queue' ? 'bg-primary-500 text-white' : 'text-gray-600 hover:bg-gray-100' ?> transition-colors">
            Queue
        </a>
        <a href="/car1/public/admin/jobs?status=In Progress" class="px-4 py-2 rounded-lg text-sm font-medium <?= $currentStatus === 'In Progress' ? 'bg-primary-500 text-white' : 'text-gray-600 hover:bg-gray-100' ?> transition-colors">
            In Progress
        </a>
        <a href="/car1/public/admin/jobs?status=Completed" class="px-4 py-2 rounded-lg text-sm font-medium <?= $currentStatus === 'Completed' ? 'bg-primary-500 text-white' : 'text-gray-600 hover:bg-gray-100' ?> transition-colors">
            Completed
        </a>
    </div>

    <!-- Jobs Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Vehicle</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($jobs as $job):
                    $statusColors = [
                        'Queue' => 'bg-gray-100 text-gray-700',
                        'In Progress' => 'bg-yellow-100 text-yellow-700',
                        'Waiting for Parts' => 'bg-orange-100 text-orange-700',
                        'Quality Check' => 'bg-blue-100 text-blue-700',
                        'Ready' => 'bg-green-100 text-green-700',
                        'Completed' => 'bg-green-100 text-green-700'
                    ];
                    $priorityColors = [
                        'Low' => 'bg-blue-100 text-blue-700',
                        'Medium' => 'bg-yellow-100 text-yellow-700',
                        'High' => 'bg-red-100 text-red-700'
                    ];
                ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center bg-slate-800 text-white text-xs font-medium px-2 py-1 rounded">
                                    <?= htmlspecialchars($job['license_plate']) ?>
                                </span>
                                <div>
                                    <p class="font-medium text-gray-800"><?= htmlspecialchars($job['car_model']) ?></p>
                                    <p class="text-sm text-gray-500"><?= $job['car_year'] ?? '' ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800"><?= htmlspecialchars($job['customer_name']) ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($job['customer_phone']) ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="status-badge <?= $statusColors[$job['status']] ?? 'bg-gray-100 text-gray-700' ?>">
                                <?= htmlspecialchars($job['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="status-badge <?= $priorityColors[$job['priority']] ?? 'bg-gray-100 text-gray-700' ?>">
                                <?= htmlspecialchars($job['priority']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= date('M j, Y', strtotime($job['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="/car1/public/admin/jobs/<?= $job['id'] ?>" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-primary-500 transition-colors" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="/car1/public/admin/jobs/<?= $job['id'] ?>/edit" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-blue-500 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($jobs)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p class="text-lg font-medium">No jobs found</p>
                            <p class="mt-1">Get started by creating a new job</p>
                            <a href="/car1/public/admin/jobs/create" class="inline-flex items-center gap-2 mt-4 text-primary-500 hover:text-primary-600 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create New Job
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>