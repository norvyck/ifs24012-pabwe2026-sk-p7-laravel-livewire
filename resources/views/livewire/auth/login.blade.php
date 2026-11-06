<div class="w-full max-w-md mx-auto my-6">
    <div class="bg-white/95 backdrop-blur-xl p-8 sm:p-10 rounded-3xl shadow-card hover:shadow-card-hover border border-slate-200/80 transition-all duration-300">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white shadow-glow mb-4">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Masuk ke Akun Anda</h2>
            <p class="text-sm text-slate-500 mt-2">Kelola stok inventaris barang Anda secara efisien dan rapi</p>
        </div>

        <form wire:submit.prevent="authenticate" class="space-y-5">
            <div>
                <label for="login-username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Username atau Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input wire:model="login" type="text" id="login-username" 
                        class="w-full pl-10 pr-3.5 py-3 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all" 
                        placeholder="username atau nama@email.com" required>
                </div>
                @error('login') <span class="text-xs font-semibold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="login-password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input wire:model="password" type="password" id="login-password" 
                        class="w-full pl-10 pr-3.5 py-3 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all" 
                        placeholder="••••••••" required>
                </div>
                @error('password') <span class="text-xs font-semibold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center space-x-2.5 text-sm text-slate-600 cursor-pointer select-none">
                    <input wire:model="remember" type="checkbox" class="w-4 h-4 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="font-medium text-xs">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" id="login-submit" wire:loading.attr="disabled" 
                class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 disabled:opacity-75 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-glow active:scale-[0.99] transition-all duration-200 flex items-center justify-center space-x-2 text-sm tracking-wide">
                <svg wire:loading wire:target="authenticate" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span wire:loading.remove wire:target="authenticate">Masuk</span>
                <span wire:loading wire:target="authenticate">Memverifikasi...</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition">Daftar sekarang</a>
        </div>
    </div>
</div>
