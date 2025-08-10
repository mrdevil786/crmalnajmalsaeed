<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Al Najm Al Saeed ERP - Employee Login">
    <meta name="author" content="Al Najm Al Saeed Co. Ltd.">
    <meta name="keywords" content="ERP, login, employee portal, Al Najm Al Saeed">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('../assets/images/brand/favicon.ico') }}" />

    <!-- TITLE -->
    <title>{{ env('APP_NAME') }} – Employee Login</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #3d9970 0%, #2d7a5a 100%);
        }
        .brand-green {
            color: #3d9970;
        }
        .bg-brand-green {
            background-color: #3d9970;
        }
        .hover-bg-brand-green:hover {
            background-color: #2d7a5a;
        }
        .border-brand-green {
            border-color: #3d9970;
        }
        .text-brand-green {
            color: #3d9970;
        }
        
        /* Icon visibility fixes */
        .fas, .fab, .far {
            display: inline-block !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            -webkit-font-smoothing: antialiased !important;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }
        
        /* Card hover effects */
        .info-card {
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="{{ asset('/assets/images/brand/logo-black.svg') }}" alt="Al Najm Al Saeed Logo" class="w-32 object-contain">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Auth()->Check())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="bg-brand-green hover-bg-brand-green text-white px-6 py-2.5 rounded-lg font-medium transition-all text-sm sm:text-base shadow-md hover:shadow-lg">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('admin.view.login') }}" 
                           class="bg-brand-green hover-bg-brand-green text-white px-6 py-2.5 rounded-lg font-medium transition-all text-sm sm:text-base shadow-md hover:shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="bg-white min-h-screen flex items-center justify-center pt-20 pb-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Company Logo -->
            <div class="mb-12">
                <div class="w-32 h-32 bg-brand-green bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-8">
                    <img src="{{ asset('/assets/images/brand/logo-1.svg') }}" alt="Al Najm Al Saeed Logo" class="w-20 h-20 object-contain">
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                    <span class="text-brand-green">Al Najm</span> Al Saeed
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 font-medium">Employee Portal</p>
            </div>

            <!-- Welcome Message -->
            <div class="mb-12">
                <h2 class="text-3xl font-semibold text-gray-900 mb-6">Welcome to Your ERP System</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Access your dashboard to manage invoices, customers, projects, and business operations efficiently.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center mb-16">
                @if (Auth()->Check())
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-brand-green hover-bg-brand-green text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-tachometer-alt mr-3 text-xl"></i>Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('admin.view.login') }}" 
                       class="bg-brand-green hover-bg-brand-green text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-3 text-xl"></i>Employee Login
                    </a>
                @endif
            </div>

            <!-- Quick Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                <div class="info-card text-center p-6 rounded-2xl bg-gray-50 hover:bg-white border border-gray-100">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-invoice text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Invoice Management</h3>
                    <p class="text-gray-600 leading-relaxed">Create, manage, and track invoices with ease</p>
                </div>
                
                <div class="info-card text-center p-6 rounded-2xl bg-gray-50 hover:bg-white border border-gray-100">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Customer Management</h3>
                    <p class="text-gray-600 leading-relaxed">Manage customer data and relationships</p>
                </div>
                
                <div class="info-card text-center p-6 rounded-2xl bg-gray-50 hover:bg-white border border-gray-100">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Reports & Analytics</h3>
                    <p class="text-gray-600 leading-relaxed">View comprehensive business insights</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('/assets/images/brand/logo-white.svg') }}" alt="Al Najm Al Saeed Logo" class="w-48 object-contain">
            </div>
            <p class="text-gray-400 text-lg">
                © <span id="year"></span> Al Najm Al Saeed Co. Ltd. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Set current year
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
