<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aplikasi Manajemen Stok Barang. Pantau dan kelola inventaris barang secara efisien dan akurat.">
    <meta name="theme-color" content="#2563eb">
    <title>{{ $title ?? 'Stock Application' }} - StockApp</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- Modern Typography: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'glow': '0 0 25px -5px rgba(59, 130, 246, 0.3)',
                        'glow-purple': '0 0 25px -5px rgba(168, 85, 247, 0.3)',
                        'card': '0 2px 10px -2px rgba(15, 23, 42, 0.05), 0 8px 24px -4px rgba(15, 23, 42, 0.04)',
                        'card-hover': '0 12px 30px -4px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.03)',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html:has(.modal-scroll-lock),
        body:has(.modal-scroll-lock) {
            overflow: hidden !important;
            overscroll-behavior: none;
        }
    </style>
    @livewireStyles
</head>
<body class="h-full text-slate-800 antialiased bg-slate-50 selection:bg-blue-600 selection:text-white" x-data="{ mobileMenuOpen: false }">
    @auth
        <!-- Modern Top Navbar (Seamless & Full-Width) -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-20 flex items-center justify-between gap-4">
                    
                    <!-- Brand / Logo & Desktop Nav Links -->
                    <div class="flex items-center space-x-8">
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.users') : route('stocks.index') }}" class="flex items-center space-x-3 group flex-shrink-0">
                            <span class="w-10 h-10 bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white rounded-2xl shadow-glow flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </span>
                            <div class="flex flex-col">
                                <span class="text-xl font-black tracking-tight text-slate-900 leading-none">Stock<span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">App</span></span>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest mt-1">Inventory System</span>
                            </div>
                        </a>

                        <!-- Horizontal Desktop Navbar Items -->
                        <nav class="hidden md:flex items-center space-x-1.5">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.users') }}" 
                                   class="flex items-center space-x-2 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <svg class="w-4 h-4 {{ request()->routeIs('admin.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>User Management</span>
                                </a>
                            @endif

                            <a href="{{ route('stocks.index') }}" 
                               class="flex items-center space-x-2 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('stocks.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('stocks.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span>My Stocks</span>
                            </a>

                            <a href="{{ route('profile') }}" 
                               class="flex items-center space-x-2 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('profile') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('profile') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profil Saya</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Right Desktop Actions: User Info & Logout -->
                    <div class="hidden md:flex items-center space-x-3.5">
                        <a href="{{ route('profile') }}" class="flex items-center space-x-3 p-1.5 pl-3 pr-2.5 rounded-2xl hover:bg-slate-100 transition group border border-transparent hover:border-slate-200/80">
                            <div class="flex flex-col text-right">
                                <span class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition leading-tight truncate max-w-[150px]">{{ auth()->user()->name }}</span>
                                <span class="inline-flex items-center justify-end text-[10px] font-bold {{ auth()->user()->isAdmin() ? 'text-purple-600' : 'text-emerald-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1 {{ auth()->user()->isAdmin() ? 'bg-purple-500' : 'bg-emerald-500' }}"></span>
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </div>
                            @if (auth()->user()->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-xl object-cover shadow-xs border border-slate-200 flex-shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-xl {{ auth()->user()->isAdmin() ? 'bg-gradient-to-tr from-purple-600 to-indigo-600 text-white' : 'bg-gradient-to-tr from-blue-600 to-cyan-600 text-white' }} flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </a>

                        <div class="h-6 w-px bg-slate-200"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" aria-label="Keluar Logout" title="Keluar (Logout)"
                                class="flex items-center space-x-1.5 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition active:scale-95">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Hamburger Button -->
                    <div class="flex md:hidden items-center space-x-2">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Buka Menu Navigasi" 
                            class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Dropdown Navigation -->
                <div x-show="mobileMenuOpen" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-xl px-2 pt-3 pb-4 space-y-2 shadow-lg">
                    
                    <!-- Mobile User Profile Preview -->
                    <div class="flex items-center space-x-3 p-3 rounded-2xl bg-slate-50 mb-3 border border-slate-200/60">
                        @if (auth()->user()->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-xl object-cover shadow-xs border border-slate-200 flex-shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-xl {{ auth()->user()->isAdmin() ? 'bg-gradient-to-tr from-purple-600 to-indigo-600 text-white' : 'bg-gradient-to-tr from-blue-600 to-cyan-600 text-white' }} flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-600 font-mono font-semibold truncate">&#64;{{ auth()->user()->username }} &bull; {{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            <span>User Management</span>
                        </a>
                    @endif

                    <a href="{{ route('stocks.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('stocks.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('stocks.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        <span>My Stocks</span>
                    </a>

                    <a href="{{ route('profile') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('profile') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('profile') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span>Profil Saya</span>
                    </a>

                    <div class="pt-2 border-t border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area (Spacious Full Width) -->
        <div class="flex flex-col min-h-[calc(100vh-80px)]">
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                @if (session()->has('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200/80 text-emerald-900 flex items-center justify-between shadow-sm animate-fade-in">
                        <div class="flex items-center space-x-3">
                            <span class="p-1 bg-emerald-500 text-white rounded-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="text-sm font-semibold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-rose-50 via-red-50 to-rose-50 border border-rose-200/80 text-rose-900 flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <span class="p-1 bg-rose-500 text-white rounded-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="text-sm font-semibold">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white/80 backdrop-blur-sm border-t border-slate-200/80 py-6 text-center text-xs text-slate-600 mt-auto">
                &copy; Delcom Systems. Designed for Modern Inventory Management.
            </footer>
        </div>
    @else
        <!-- Guest Layout (Login & Register) with modern Mesh Glow -->
        <div class="min-h-screen flex flex-col justify-between bg-gradient-to-br from-slate-50 via-indigo-50/20 to-blue-50/30">
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                    <a href="{{ route('login') }}" class="flex items-center space-x-3 group">
                        <span class="w-10 h-10 bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white rounded-2xl shadow-glow flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </span>
                        <div class="flex flex-col">
                            <span class="text-xl font-black tracking-tight text-slate-900 leading-none">Stock<span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">App</span></span>
                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest mt-1">Inventory System</span>
                        </div>
                    </a>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-md shadow-blue-500/20 hover:shadow-glow transition-all duration-200 active:scale-95">
                            Register
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1 flex items-center justify-center p-4 py-12">
                {{ $slot }}
            </main>

            <footer class="bg-white/80 backdrop-blur-sm border-t border-slate-200/80 py-6 text-center text-xs text-slate-600">
                &copy; Delcom Systems. All rights reserved.
            </footer>
        </div>
    @endauth

    @livewireScripts
</body>
</html>
