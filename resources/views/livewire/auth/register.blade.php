<div class="w-full max-w-md mx-auto my-6">
    <div class="bg-white/95 backdrop-blur-xl p-8 sm:p-10 rounded-3xl shadow-card hover:shadow-card-hover border border-slate-200/80 transition-all duration-300">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white shadow-glow mb-4">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Buat Akun Member</h1>
            <p class="text-sm text-slate-600 mt-2">Daftar sekarang untuk mulai mengelola stok inventaris Anda</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-4">
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                <input wire:model="name" type="text" id="name" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all" 
                    placeholder="Nama Anda" required>
                @error('name') <span class="text-xs font-semibold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Username</label>
                <input wire:model="username" type="text" id="username" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all" 
                    placeholder="username_kamu" required>
                @error('username') <span class="text-xs font-semibold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email</label>
                <input wire:model="email" type="email" id="email" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all" 
                    placeholder="nama@email.com" required>
                @error('email') <span class="text-xs font-semibold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
                <input wire:model="password" type="password" id="password" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all" 
                    placeholder="Minimal 8 karakter" required>
                @error('password') <span class="text-xs font-semibold text-rose-600 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Konfirmasi Password</label>
                <input wire:model="password_confirmation" type="password" id="password_confirmation" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all" 
                    placeholder="Ulangi password" required>
            </div>

            <button type="submit" wire:loading.attr="disabled" 
                class="w-full py-3.5 px-4 mt-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 disabled:opacity-75 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-glow active:scale-[0.99] transition-all duration-200 flex items-center justify-center space-x-2 text-sm tracking-wide">
                <svg wire:loading wire:target="register" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span wire:loading.remove wire:target="register">Daftar Akun</span>
                <span wire:loading wire:target="register">Mendaftarkan...</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-600">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition">Masuk di sini</a>
        </div>
    </div>
</div>
