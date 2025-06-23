<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($header) ? strip_tags($header) . ' - ' : '' }}E-Purchasing</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/online-shop.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ open: true }" class="flex min-h-screen">
        <!-- Sidebar -->
        <aside 
            :class="{'w-64': open, 'w-20': !open}" 
            class="bg-gray-800 text-white flex flex-col transition-all duration-300 ease-in-out"
        >
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-gray-700 px-4">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/online-shop.png') }}" alt="Logo Perusahaan" class="w-auto h-10 transition-all duration-300" :class="{'h-8': !open, 'h-10': open}"
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-4 space-y-2">
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <i class="fas fa-tachometer-alt fa-fw mr-3"></i>
                    <span :class="{'hidden': !open}">Dashboard</span>
                </x-sidebar-link>
                
                @can('manage-suppliers')
                <p class="px-4 py-2 text-xs text-gray-400 uppercase" :class="{'hidden': !open}">Master Data</p>
                <x-sidebar-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                    <i class="fas fa-truck fa-fw mr-3"></i><span :class="{'hidden': !open}">Supplier</span>
                </x-sidebar-link>
                @endcan
                @can('manage-products')
                <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    <i class="fas fa-box-open fa-fw mr-3"></i><span :class="{'hidden': !open}">Produk</span>
                </x-sidebar-link>
                @endcan

                @can('view-stock')
                <x-sidebar-link :href="route('stocks.index')" :active="request()->routeIs('stocks.*')">
                <i class="fas fa-warehouse fa-fw mr-3"></i><span :class="{'hidden': !open}">Manajemen Stok</span>
                </x-sidebar-link>
                @endcan

                @can('adjust-stock')
                <x-sidebar-link :href="route('stock-adjustments.create')" :active="request()->routeIs('stock-adjustments.*')">
                    <i class="fas fa-exchange-alt fa-fw mr-3"></i><span :class="{'hidden': !open}">Penyesuaian Stok</span>
                </x-sidebar-link>
                @endcan

                <p class="px-4 py-2 text-xs text-gray-400 uppercase" :class="{'hidden': !open}">Transaksi</p>
                @can('view-pr')
                <x-sidebar-link :href="route('purchase-requests.index')" :active="request()->routeIs('purchase-requests.*')">
                    <i class="fas fa-file-alt fa-fw mr-3"></i><span :class="{'hidden': !open}">Purchase Request</span>
                </x-sidebar-link>
                @endcan
                @can('view-po')
                <x-sidebar-link :href="route('purchase-orders.index')" :active="request()->routeIs('purchase-orders.*')">
                    <i class="fas fa-file-invoice-dollar fa-fw mr-3"></i><span :class="{'hidden': !open}">Purchase Order</span>
                </x-sidebar-link>
                @endcan
                @can('view-grn')
                <x-sidebar-link :href="route('goods-receipt-notes.index')" :active="request()->routeIs('goods-receipt-notes.*')">
                    <i class="fas fa-dolly-flatbed fa-fw mr-3"></i><span :class="{'hidden': !open}">Penerimaan Barang</span>
                </x-sidebar-link>
                @endcan
                <x-sidebar-link :href="route('stock-requisitions.index')" :active="request()->routeIs('stock-requisitions.*')">
                 <i class="fas fa-people-carry fa-fw mr-3"></i><span :class="{'hidden': !open}">Permintaan Internal</span>
                </x-sidebar-link>

                @can('manage-invoices')
                <x-sidebar-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
                    <i class="fas fa-receipt fa-fw mr-3"></i><span :class="{'hidden': !open}">Faktur (Invoice)</span>
                </x-sidebar-link>
                @endcan
                
                @canany(['view-po-report', 'view-pr-report', 'view-grn-report'])
                <p class="px-4 py-2 text-xs text-gray-400 uppercase" :class="{'hidden': !open}">Laporan</p>
                <x-sidebar-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    <i class="fas fa-chart-line fa-fw mr-3"></i>
                    <span :class="{'hidden': !open}">Laporan</span>
                </x-sidebar-link>
                @endcanany

                @can('manage-users')
                <p class="px-4 py-2 text-xs text-gray-400 uppercase" :class="{'hidden': !open}">Administrasi</p>
                <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    <i class="fas fa-users-cog fa-fw mr-3"></i><span :class="{'hidden': !open}">Manajemen User</span>
                </x-sidebar-link>
                @endcan
            </nav>
            
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-md h-16 flex items-center justify-between px-6">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                     <i class="fas fa-bars text-xl"></i>
                </button>
                
                <div class="flex-1 ml-6">
                    @if (isset($header))
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $header }}
                        </h2>
                    @endif
                </div>
                <div class="flex items-center space-x-6">
                    <!-- Notifications Dropdown -->
                    <div x-data="{ notificationOpen: false, notifications: [], unreadCount: 0 }" x-init="
                        fetchNotifications = () => {
                            fetch('{{ route('notifications.unread') }}')
                                .then(response => response.json())
                                .then(data => {
                                    notifications = data;
                                    unreadCount = data.length;
                                });
                        };
                        fetchNotifications();
                        setInterval(fetchNotifications, 15000); // Cek notifikasi baru setiap 15 detik
                    " class="relative">
                        <button @click="notificationOpen = !notificationOpen" class="relative text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell fa-lg"></i>
                            <template x-if="unreadCount > 0">
                                <span x-text="unreadCount" class="absolute -top-1 -right-1 flex items-center justify-center h-5 w-5 bg-red-500 text-white text-xs rounded-full"></span>
                            </template>
                        </button>

                        <div x-show="notificationOpen" @click.away="notificationOpen = false" class="absolute right-0 w-80 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20">
                            <div class="py-2 px-4 text-sm font-semibold text-gray-700 border-b">Notifikasi</div>
                            <div class="max-h-80 overflow-y-auto">
                                <template x-for="notification in notifications" :key="notification.id">
                                <a :href="'/notifications/' + notification.id + '/read'" class="flex items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2">
                            <p class="text-gray-600 text-sm mx-2" x-text="notification.data.message"></p>
                        </a>
                                </template>
                                <template x-if="notifications.length === 0">
                                    <p class="text-center text-gray-500 py-4">Tidak ada notifikasi baru.</p>
                                </template>
                            </div>
                        </div>
                    </div>
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 relative focus:outline-none">
                        <span class="text-right text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg z-50">
                        <div class="py-1 rounded-md bg-white shadow-xs">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>

            <footer class="text-center py-4">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} E-Purchasing. Dibuat oleh fla1r.
                </p>
            </footer>
        </div>
    </div>
</body>
</html>