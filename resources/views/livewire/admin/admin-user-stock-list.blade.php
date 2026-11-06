<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mb-8">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users') }}" 
                   class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-700 hover:text-blue-700 hover:bg-blue-50 shadow-sm transition-all duration-200 active:scale-95" 
                   title="Kembali ke Manajemen Pengguna"
                   aria-label="Kembali ke Manajemen Pengguna">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <div class="flex items-center space-x-2.5">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Stok Barang Pengguna: {{ $user->name }}</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            Read-Only Admin
                        </span>
                    </div>
                    <p class="text-xs text-slate-700 mt-1 font-medium">{{ $user->email }} &bull; Mode Lihat Saja (Read-Only Admin)</p>
                </div>
            </div>
        </div>

        <div>
            <button wire:click="exportExcel" wire:loading.attr="disabled" 
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 disabled:opacity-75 text-white text-xs font-bold rounded-xl shadow-md transition active:scale-95">
                <svg wire:loading.remove wire:target="exportExcel" class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <svg wire:loading wire:target="exportExcel" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span wire:loading.remove wire:target="exportExcel">Export Excel Stok Ini</span>
                <span wire:loading wire:target="exportExcel">Mengekspor...</span>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl shadow-card border border-slate-200 mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="w-full sm:w-80 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, SKU, atau deskripsi..." 
                aria-label="Cari nama, SKU, atau deskripsi barang"
                class="w-full pl-10 pr-9 py-2.5 text-sm font-medium rounded-xl border border-slate-200 bg-white placeholder:text-slate-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all shadow-sm">
            <div wire:loading wire:target="search,statusFilter" class="absolute right-3 top-3">
                <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </div>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <label for="admin-stock-status-filter" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status:</label>
            <select id="admin-stock-status-filter" wire:model.live="statusFilter" aria-label="Filter status stok barang"
                class="px-3.5 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-white transition-all">
                <option value="">Semua Status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table of user's stocks -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-700">
                        <th class="py-4 px-6">No</th>
                        <th class="py-4 px-6">Nama Barang</th>
                        <th class="py-4 px-6">SKU</th>
                        <th class="py-4 px-6 text-center">Jumlah Stok</th>
                        <th class="py-4 px-6">Harga Satuan</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Dibuat Pada</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($stocks as $index => $stock)
                        <tr class="hover:bg-slate-50/80 transition-all duration-150">
                            <td class="py-4 px-6 text-slate-700 text-xs font-medium">
                                {{ $stocks->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $stock->name }}
                            </td>
                            <td class="py-4 px-6 font-mono text-xs text-slate-700 font-bold">
                                {{ $stock->sku ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center font-black text-slate-900">
                                {{ $stock->quantity }} <span class="text-xs font-semibold text-slate-700">{{ $stock->unit }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-700 font-semibold">
                                {{ $stock->price !== null ? 'Rp ' . number_format($stock->price, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full border {{ \App\Features\Stock\Enums\StockStatus::tryFrom($stock->status)?->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
                                    @if($stock->status === 'available')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1.5"></span>
                                    @elseif($stock->status === 'low_stock')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 mr-1.5 animate-pulse"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 mr-1.5 animate-ping"></span>
                                    @endif
                                    {{ \App\Features\Stock\Enums\StockStatus::tryFrom($stock->status)?->label() ?? ucfirst($stock->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-700 text-xs font-medium">
                                {{ $stock->created_at ? $stock->created_at->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-700 font-medium">
                                Pengguna ini belum memiliki stok barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $stocks->links() }}
        </div>
    </div>
</div>
