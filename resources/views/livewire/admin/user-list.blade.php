<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mb-8">
        <div>
            <div class="flex items-center space-x-2.5 mb-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-700 border border-purple-200">
                    Administrator
                </span>
                <span class="text-xs text-slate-400 font-medium">Panel Kendali Pengguna</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar semua pengguna terdaftar dan pantau stok barang mereka</p>
        </div>

        <div class="w-full sm:w-80 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..." 
                aria-label="Cari nama atau email pengguna"
                class="w-full pl-10 pr-9 py-2.5 text-sm font-medium rounded-xl border border-slate-200/90 bg-white/90 backdrop-blur-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all shadow-sm">
            <div wire:loading wire:target="search" class="absolute right-3 top-3">
                <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-card border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-4 px-6">Nama Pengguna</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Role</th>
                        <th class="py-4 px-6 text-center">Jumlah Stok</th>
                        <th class="py-4 px-6">Tanggal Bergabung</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-all duration-150">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl {{ $user->isAdmin() ? 'bg-purple-600 text-white' : 'bg-blue-600 text-white' }} flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 leading-snug">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400 font-mono">&#64;{{ $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $user->isAdmin() ? 'bg-purple-100/90 text-purple-700 border border-purple-200' : 'bg-emerald-100/90 text-emerald-700 border border-emerald-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->isAdmin() ? 'bg-purple-500' : 'bg-emerald-500' }}"></span>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-black bg-slate-100 text-slate-800 border border-slate-200/60">
                                    {{ $user->stocks_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-500 text-xs font-medium">
                                {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.users.stocks', $user->id) }}" 
                                    class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 text-xs font-bold text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-200 hover:border-blue-600 rounded-xl shadow-xs transition-all duration-200 active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Lihat Stok</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-slate-400 font-medium">
                                Tidak ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }}
        </div>
    </div>
</div>
