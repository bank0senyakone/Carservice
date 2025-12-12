<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Workshop Portal') ?> - AutoFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                        sidebar: {
                            dark: '#1e293b',
                            darker: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(249, 115, 22, 0.1);
        }

        .sidebar-link.active {
            background: rgba(249, 115, 22, 0.15);
            border-left: 3px solid #f97316;
        }

        .card-hover {
            transition: all 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include dirname(__DIR__) . '/partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <?php include dirname(__DIR__) . '/partials/header.php'; ?>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-auto">
                <?php if (isset($flash) && $flash): ?>
                    <div class="mb-4 p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
                        <?= htmlspecialchars($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </main>
        </div>
    </div>

    <script>
        // Global JavaScript for the application
        function confirmDelete(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        }

        // Update job status via AJAX
        async function updateJobStatus(jobId, status) {
            try {
                const response = await fetch(`/car1/public/admin/jobs/${jobId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `status=${encodeURIComponent(status)}`
                });
                const data = await response.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update status');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update status');
            }
        }
    </script>
</body>

</html>