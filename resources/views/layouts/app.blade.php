<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

        <style>
            html, body {
                background-color: #f3f4f6;
                color: #18181b;
                transition: background-color 0.2s, color 0.2s;
            }
            .dark-mode html, .dark-mode body {
                background-color: #18181b !important;
                color: #e5e7eb !important;
            }
            .dark-mode .bg-gray-100, .dark-mode .bg-white {
                background-color: #23232a !important;
                color: #e5e7eb !important;
            }
            .btn-dark {
                background-color: #e5e7eb;
                color: #18181b;
                border: none;
            }
            .btn-dark.active, .btn-dark:focus, .btn-dark:hover {
                background-color: #3b82f6;
                color: #fff;
            }
            .dark-mode .btn-dark {
                background-color: #27272a !important;
                color: #e5e7eb !important;
            }
            .dark-mode .btn-dark.active, .dark-mode .btn-dark:focus, .dark-mode .btn-dark:hover {
                background-color: #3b82f6 !important;
                color: #fff !important;
            }
            select, option {
                background-color: #fff;
                color: #18181b;
                border: 1px solid #3b82f6;
            }
            .dark-mode select, .dark-mode option {
                background-color: #23232a !important;
                color: #e5e7eb !important;
                border: 1px solid #3b82f6 !important;
            }
            label {
                color: #18181b;
            }
            .dark-mode label {
                color: #e5e7eb !important;
            }
            canvas {
                background-color: #fff;
                border-radius: 0.5rem;
            }
            .dark-mode canvas {
                background-color: #18181b !important;
                border-radius: 0.5rem;
            }
            .dark-toggle-btn {
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 50;
                background: #23232a;
                color: #e5e7eb;
                border: 1px solid #3b82f6;
                border-radius: 9999px;
                padding: 0.5rem 1rem;
                cursor: pointer;
                transition: background 0.2s, color 0.2s;
            }
            .dark-toggle-btn:hover {
                background: #3b82f6;
                color: #fff;
            }
            .dark-mode input,
            .dark-mode textarea,
            .dark-mode select {
                background-color: #23232a !important;
                color: #e5e7eb !important;
                border: 1px solid #3b82f6 !important;
            }

            .dark-mode input::placeholder,
            .dark-mode textarea::placeholder {
                color: #a1a1aa !important;
            }

            .dark-mode .form-label,
            .dark-mode label {
                color: #e5e7eb !important;
            }

            .dark-mode .btn,
            .dark-mode button,
            .dark-mode .btn-dark {
                background-color: #27272a !important;
                color: #e5e7eb !important;
                border: 1px solid #3b82f6 !important;
            }

            .dark-mode .btn-primary,
            .dark-mode .btn-dark.active,
            .dark-mode .btn-dark:focus,
            .dark-mode .btn-dark:hover {
                background-color: #3b82f6 !important;
                color: #fff !important;
                border: 1px solid #3b82f6 !important;
            }
            .dark-mode body,
            .dark-mode .bg-gray-100,
            .dark-mode .bg-white,
            .dark-mode label,
            .dark-mode .form-label,
            .dark-mode .text-gray-800,
            .dark-mode .text-gray-600,
            .dark-mode .text-sm,
            .dark-mode .text-lg,
            .dark-mode .text-xl,
            .dark-mode .font-semibold,
            .dark-mode .font-bold,
            .dark-mode .font-medium {
                color: #f3f4f6 !important;
            }

            /* Remove outline and border for small action buttons in dark mode */
            .dark-mode .text-blue-600,
            .dark-mode .text-red-600 {
                background: none !important;
                color: #3b82f6 !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0.25rem 0.5rem;
                border-radius: 0.25rem;
                transition: background 0.2s, color 0.2s;
            }

            .dark-mode .text-blue-600:hover,
            .dark-mode .text-red-600:hover {
                background: #3b82f6 !important;
                color: #fff !important;
                text-decoration: none !important;
            }

            /* Optional: Remove default browser outline on focus */
            .dark-mode .text-blue-600:focus,
            .dark-mode .text-red-600:focus {
                outline: none !important;
                box-shadow: 0 0 0 2px #3b82f6;
            }
        </style>
        <script>
            // Apply dark mode instantly if preference is set
            if (localStorage.getItem('dark-mode') === 'on') {
                document.documentElement.classList.add('dark-mode');
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <button id="dark-toggle" class="dark-toggle-btn">🌙 Dark Mode</button>
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Dark mode toggle logic
            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('dark-toggle');
                const html = document.documentElement;
                // Load preference
                if(localStorage.getItem('dark-mode') === 'on') {
                    html.classList.add('dark-mode');
                    btn.textContent = '☀️ Light Mode';
                }
                btn.addEventListener('click', function() {
                    if(html.classList.contains('dark-mode')) {
                        html.classList.remove('dark-mode');
                        btn.textContent = '🌙 Dark Mode';
                        localStorage.setItem('dark-mode', 'off');
                    } else {
                        html.classList.add('dark-mode');
                        btn.textContent = '☀️ Light Mode';
                        localStorage.setItem('dark-mode', 'on');
                    }
                });

                // Delete confirmation modal logic
                let deleteFormToSubmit = null;
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        deleteFormToSubmit = form;
                        document.getElementById('delete-modal').style.display = '';
                    });
                });
                document.getElementById('cancel-delete').addEventListener('click', function() {
                    document.getElementById('delete-modal').style.display = 'none';
                    deleteFormToSubmit = null;
                });
                document.getElementById('confirm-delete').addEventListener('click', function() {
                    if (deleteFormToSubmit) {
                        deleteFormToSubmit.submit();
                    }
                    document.getElementById('delete-modal').style.display = 'none';
                });
            });
        </script>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" style="display:none; position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.5);">
            <div style="background:#23232a; color:#fff; max-width:350px; margin:10% auto; padding:2rem; border-radius:1rem; box-shadow:0 2px 8px #000;">
                <h2 style="font-size:1.25rem; margin-bottom:1rem;">Confirm Delete</h2>
                <p>Are you sure you want to delete this workout?</p>
                <div style="margin-top:2rem; display:flex; gap:1rem; justify-content:flex-end;">
                    <button id="cancel-delete" class="btn-dark">Cancel</button>
                    <button id="confirm-delete" class="btn-dark active">Delete</button>
                </div>
            </div>
        </div>
    </body>
</html>
