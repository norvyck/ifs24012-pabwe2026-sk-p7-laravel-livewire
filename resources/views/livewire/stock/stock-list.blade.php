<div>
    <!-- Toast UI Editor CDN Styles & Scripts -->
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />
    <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>

    <!-- Top Action Bar & Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">
        <div>
            <div class="flex items-center space-x-2.5 mb-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                    Inventory System
                </span>
                <span class="text-xs text-slate-700 font-semibold">Real-time Stock Control</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Stok Barang Saya</h1>
            <p class="text-sm text-slate-700 mt-1">Kelola stok inventaris barang Anda secara rapi dan akurat</p>
        </div>

        <!-- Desktop & Mobile Action Group -->
        <div class="flex items-center flex-wrap gap-2.5">
            <!-- Direct Primary Buttons for Desktop -->
            <button type="button" wire:click="openCreateModal"
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/20 hover:shadow-glow transition-all duration-200 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                <span>Tambah Stok</span>
            </button>

            <button type="button" wire:click="openAiModal"
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 via-fuchsia-600 to-indigo-600 hover:from-purple-700 hover:via-fuchsia-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-purple-500/20 hover:shadow-glow-purple transition-all duration-200 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                <span>Generate AI</span>
            </button>

            <!-- Kebab / Overflow Menu for Import & Export -->
            <div class="relative" x-data="{ open: false, loading: null }" @click.outside="open = false" @keydown.escape.window="open = false">
                <button type="button" @click="open = !open" :disabled="!!loading"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 shadow-sm transition disabled:opacity-60"
                    title="Menu opsi spreadsheet & tindakan lainnya"
                    aria-label="Menu opsi spreadsheet & tindakan lainnya">
                    <svg x-show="!loading" class="w-5 h-5 text-slate-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                    <svg x-show="loading" x-cloak class="animate-spin w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 z-30 mt-2 w-56 origin-top-right rounded-2xl border border-slate-200 bg-white py-2 shadow-2xl shadow-slate-200/80">
                    <div class="px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-700">Spreadsheet</div>

                    <button type="button" :disabled="!!loading"
                        @click="
                            loading = 'import'; open = false;
                            $wire.openImportModal().finally(() => loading = null);
                        "
                        class="flex w-full items-center gap-2.5 px-3.5 py-2.5 text-sm font-semibold text-emerald-800 hover:bg-emerald-50 transition disabled:opacity-60">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-800">
                            <svg x-show="loading !== 'import'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                            <svg x-show="loading === 'import'" x-cloak class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </span>
                        Import Excel
                    </button>

                    <button type="button" :disabled="!!loading"
                        @click="
                            loading = 'export'; open = false;
                            $wire.exportExcel().finally(() => loading = null);
                        "
                        class="flex w-full items-center gap-2.5 px-3.5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition disabled:opacity-60">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                            <svg x-show="loading !== 'export'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            <svg x-show="loading === 'export'" x-cloak class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </span>
                        <span x-text="loading === 'export' ? 'Mengekspor...' : 'Export Excel'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white p-4 rounded-2xl shadow-card border border-slate-200 mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="w-full sm:w-80 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, SKU, deskripsi..."
                aria-label="Cari nama, SKU, atau deskripsi barang"
                class="w-full pl-10 pr-9 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium placeholder:text-slate-600">
            <div wire:loading wire:target="search,statusFilter" class="absolute right-3 top-3">
                <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </div>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <label for="stock-status-filter" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status:</label>
            <select id="stock-status-filter" wire:model.live="statusFilter" aria-label="Filter status stok barang"
                class="px-3.5 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-white transition-all text-slate-800">
                <option value="">Semua Status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Stock List Container -->
    <div class="bg-white rounded-3xl shadow-card border border-slate-200 overflow-hidden">
        @if ($stocks->isEmpty())
            <div class="py-20 text-center px-4">
                <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 border border-blue-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-800">Belum ada stok barang ditemukan</h2>
                <p class="text-sm text-slate-700 mt-1 max-w-sm mx-auto">Tambahkan barang baru secara manual atau gunakan fitur AI untuk rekomendasi cepat</p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <button type="button" wire:click="openCreateModal" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                        Tambah Manual
                    </button>
                    <button type="button" wire:click="openAiModal" class="px-4 py-2 bg-purple-50 text-purple-800 hover:bg-purple-100 font-semibold text-xs rounded-xl border border-purple-200 transition">
                        Gunakan AI
                    </button>
                </div>
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach ($stocks as $stock)
                    <li wire:key="stock-{{ $stock->id }}" class="p-4 sm:p-5 hover:bg-slate-50 transition-all duration-150 flex items-center justify-between gap-4">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <!-- Quantity Adjuster Controls (+ / -) -->
                            <div class="flex items-center space-x-1.5 bg-slate-100 p-1 rounded-2xl flex-shrink-0 border border-slate-200 shadow-xs" x-data="{ busy: false }">
                                <button type="button"
                                    x-bind:disabled="busy || {{ $stock->quantity <= 0 ? 'true' : 'false' }}"
                                    x-on:click="
                                        if (busy) return;
                                        busy = true;
                                        $wire.adjustQuantity({{ $stock->id }}, -1).finally(() => busy = false);
                                    "
                                    class="w-8 h-8 rounded-xl bg-white text-slate-800 hover:bg-rose-50 hover:text-rose-800 disabled:opacity-40 disabled:hover:bg-white disabled:hover:text-slate-800 font-bold text-base shadow-xs flex items-center justify-center transition active:scale-95"
                                    title="Kurangi Stok (-1)"
                                    aria-label="Kurangi Stok {{ $stock->name }}">
                                    &minus;
                                </button>
                                <span class="w-12 text-center font-black text-sm text-slate-900 truncate">
                                    {{ $stock->quantity }}
                                </span>
                                <button type="button"
                                    x-bind:disabled="busy"
                                    x-on:click="
                                        if (busy) return;
                                        busy = true;
                                        $wire.adjustQuantity({{ $stock->id }}, 1).finally(() => busy = false);
                                    "
                                    class="w-8 h-8 rounded-xl bg-white text-slate-800 hover:bg-emerald-50 hover:text-emerald-800 disabled:opacity-40 font-bold text-base shadow-xs flex items-center justify-center transition active:scale-95"
                                    title="Tambah Stok (+1)"
                                    aria-label="Tambah Stok {{ $stock->name }}">
                                    &plus;
                                </button>
                            </div>

                            <!-- Thumbnail Image or Fallback -->
                            <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 hidden sm:flex items-center justify-center">
                                @if ($stock->coverImageUrl())
                                    <img src="{{ $stock->coverImageUrl() }}" alt="{{ $stock->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs font-bold text-slate-700">
                                        {{ strtoupper(substr($stock->name, 0, 2)) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Stock Information -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('stocks.show', $stock->id) }}"
                                        class="text-base font-bold text-slate-900 hover:text-blue-700 transition truncate">
                                        {{ $stock->name }}
                                    </a>
                                    @if($stock->sku)
                                        <span class="px-2 py-0.5 text-[11px] font-mono font-bold rounded-lg bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $stock->sku }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-[11px] font-bold rounded-full border {{ \App\Features\Stock\Enums\StockStatus::tryFrom($stock->status)?->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
                                        @if($stock->status === 'available')
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        @elseif($stock->status === 'low_stock')
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 animate-ping"></span>
                                        @endif
                                        {{ \App\Features\Stock\Enums\StockStatus::tryFrom($stock->status)?->label() ?? ucfirst($stock->status) }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-4 mt-1.5 text-xs text-slate-700">
                                    <span>Satuan: <strong class="text-slate-800">{{ $stock->unit }}</strong></span>
                                    @if($stock->price !== null)
                                        <span>Harga: <strong class="text-slate-900 font-semibold">Rp {{ number_format($stock->price, 0, ',', '.') }}</strong></span>
                                    @endif
                                    <span>Min. Stok: <strong class="text-slate-800">{{ $stock->min_stock }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Direct Action Buttons -->
                        <div class="flex items-center space-x-1 flex-shrink-0">
                            <a href="{{ route('stocks.show', $stock->id) }}"
                               class="p-2 rounded-xl text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition" title="Lihat Detail" aria-label="Lihat Detail {{ $stock->name }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <button type="button" wire:click="openEditModal({{ $stock->id }})"
                               class="p-2 rounded-xl text-slate-700 hover:text-amber-800 hover:bg-amber-50 transition" title="Edit Stok" aria-label="Edit Stok {{ $stock->name }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button type="button" wire:click="confirmDelete({{ $stock->id }})"
                               class="p-2 rounded-xl text-slate-700 hover:text-rose-800 hover:bg-rose-50 transition" title="Hapus Stok" aria-label="Hapus Stok {{ $stock->name }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $stocks->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Form Modal -->
    @if ($showFormModal)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-cloak
            @keydown.escape.window="show = false; $wire.closeFormModal()"
            class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm">
            <div @click.outside="show = false; $wire.closeFormModal()"
                class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl shadow-slate-950/20 ring-1 ring-slate-900/10 max-h-[92vh] overflow-hidden flex flex-col my-auto transition-all">
                
                <!-- Sleek Top Gradient Accent -->
                <div class="h-1.5 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>

                <!-- Unified Header -->
                <div class="px-6 sm:px-8 pt-6 pb-2 flex items-start justify-between gap-4">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/25 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                                {{ $editingStockId ? 'Edit Stok Barang' : 'Tambah Stok Barang Baru' }}
                            </h2>
                            <p class="text-xs text-slate-700 mt-0.5 font-medium">Lengkapi rincian data inventaris barang Anda</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false; $wire.closeFormModal()" aria-label="Tutup form stok barang" 
                        class="p-2 -mr-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveStock" class="flex flex-col flex-1 min-h-0">
                    <div class="space-y-4 px-6 py-5 overflow-y-auto flex-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label for="formName" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Barang</label>
                                <input id="formName" wire:model="formName" type="text"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all placeholder:text-slate-600"
                                    placeholder="Contoh: Mouse Wireless Logitech M330" required>
                                @error('formName')
                                    <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="formSku" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Kode SKU (Opsional)</label>
                                <input id="formSku" wire:model="formSku" type="text"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-mono transition-all placeholder:text-slate-600"
                                    placeholder="ACC-LOGI-001">
                                @error('formSku')
                                    <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="formUnit" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Satuan</label>
                                <input id="formUnit" wire:model="formUnit" type="text"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all placeholder:text-slate-600"
                                    placeholder="pcs, unit, box..." required>
                                @error('formUnit')
                                    <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="formQuantity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Stok</label>
                                <input id="formQuantity" wire:model.live="formQuantity" type="number" min="0"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all"
                                    required>
                                @error('formQuantity')
                                    <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="formMinStock" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Ambang Stok Menipis (Min. Stock)</label>
                                <input id="formMinStock" wire:model.live="formMinStock" type="number" min="0"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all"
                                    required>
                                @error('formMinStock')
                                    <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="formPrice" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Harga Satuan (Rp, Opsional)</label>
                                <input id="formPrice" wire:model="formPrice" type="number" min="0" step="0.01"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all placeholder:text-slate-600"
                                    placeholder="150000">
                                @error('formPrice')
                                    <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Status (Otomatis)</label>
                                <div class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold flex items-center">
                                    <span class="{{ $formStatus === 'available' ? 'text-emerald-800' : ($formStatus === 'low_stock' ? 'text-amber-800' : 'text-rose-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $formStatus)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi / Spesifikasi Barang (Toast UI)</label>
                                <span class="text-[11px] font-semibold text-slate-600">Markdown & WYSIWYG</span>
                            </div>

                            <div wire:ignore
                                 x-data="{
                                     desc: @entangle('formDescription'),
                                     editor: null,
                                     init() {
                                         this.editor = new toastui.Editor({
                                             el: this.$refs.formEditorContainer,
                                             height: '220px',
                                             initialEditType: 'wysiwyg',
                                             previewStyle: 'vertical',
                                             initialValue: this.desc || '',
                                             placeholder: 'Spesifikasi produk atau catatan stok...',
                                             events: {
                                                 change: () => {
                                                     this.desc = this.editor.getMarkdown();
                                                 }
                                             }
                                         });

                                         this.$watch('desc', value => {
                                             if (this.editor && value !== this.editor.getMarkdown()) {
                                                 this.editor.setMarkdown(value || '', false);
                                             }
                                         });
                                     }
                                 }"
                                 class="rounded-2xl border border-slate-300 overflow-hidden bg-white shadow-xs">
                                <div x-ref="formEditorContainer"></div>
                            </div>
                            @error('formDescription')
                                <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 sm:px-8 py-4 bg-slate-50/80 border-t border-slate-100">
                        <button type="button" @click="show = false; $wire.closeFormModal()"
                            class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-75 disabled:cursor-not-allowed rounded-xl shadow-md shadow-blue-500/20 transition flex items-center space-x-2">
                            <svg wire:loading wire:target="saveStock" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveStock">Simpan</span>
                            <span wire:loading wire:target="saveStock">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Import Excel Modal -->
    @if ($showImportModal)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-cloak
            @keydown.escape.window="show = false; $wire.closeImportModal()"
            class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm">
            <div @click.outside="show = false; $wire.closeImportModal()"
                class="bg-white rounded-3xl max-w-md w-full shadow-2xl shadow-slate-950/20 ring-1 ring-slate-900/10 overflow-hidden flex flex-col my-auto transition-all">
                
                <!-- Sleek Top Gradient Accent -->
                <div class="h-1.5 w-full bg-gradient-to-r from-emerald-600 to-teal-600"></div>

                <!-- Unified Header -->
                <div class="px-6 sm:px-7 pt-6 pb-2 flex items-start justify-between gap-4">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-emerald-500/25 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Import Spreadsheet</h2>
                            <p class="text-xs text-slate-700 mt-0.5 font-medium">Unggah berkas Excel/CSV untuk tambah stok</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false; $wire.closeImportModal()" aria-label="Tutup modal import excel" 
                        class="p-2 -mr-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="importExcel" class="flex flex-col">
                    <div class="space-y-5 p-6 sm:p-7">
                        <div>
                            <label for="importFile" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Pilih File (.xlsx, .xls, .csv)</label>
                            <input id="importFile" wire:model="importFile" type="file" accept=".xlsx,.xls,.csv"
                                class="w-full text-xs text-slate-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 transition"
                                required>
                            @error('importFile')
                                <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-[11px] text-slate-700 space-y-1">
                            <p class="font-bold text-slate-800">Format Kolom Spreadsheet:</p>
                            <p>Kolom A: No | Kolom B: Nama | Kolom C: SKU | Kolom D: Deskripsi | Kolom E: Quantity | Kolom F: Unit | Kolom G: Price | Kolom H: Min Stock | Kolom I: Status</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 sm:px-7 py-4 bg-slate-50/80 border-t border-slate-100">
                        <button type="button" @click="show = false; $wire.closeImportModal()"
                            class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 disabled:opacity-75 disabled:cursor-not-allowed rounded-xl shadow-md shadow-emerald-500/20 transition flex items-center space-x-2">
                            <svg wire:loading wire:target="importExcel,importFile" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="importExcel,importFile">Upload & Import</span>
                            <span wire:loading wire:target="importExcel,importFile">Mengupload & Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- AI Stock Generator Modal -->
    @if ($showAiModal)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-cloak
            @keydown.escape.window="show = false; $wire.closeAiModal()"
            class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm">
            <div @click.outside="show = false; $wire.closeAiModal()"
                class="bg-white rounded-3xl max-w-xl w-full shadow-2xl shadow-slate-950/20 ring-1 ring-slate-900/10 overflow-hidden flex flex-col my-auto transition-all">
                
                <!-- Sleek Top Gradient Accent -->
                <div class="h-1.5 w-full bg-gradient-to-r from-purple-600 via-fuchsia-600 to-indigo-600"></div>

                <!-- Unified Header -->
                <div class="px-6 sm:px-8 pt-6 pb-2 flex items-start justify-between gap-4">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-purple-600 via-fuchsia-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-purple-500/25 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Generate Stok Barang AI</h2>
                            <p class="text-xs text-slate-700 mt-0.5 font-medium">Buat item inventaris terstruktur otomatis berbasis AI</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false; $wire.closeAiModal()" aria-label="Tutup modal generate AI" 
                        class="p-2 -mr-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 sm:p-8 space-y-6">
                    @if ($aiErrorMessage)
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs font-semibold flex items-start space-x-2.5">
                            <svg class="w-4 h-4 text-rose-700 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span class="leading-relaxed">{{ $aiErrorMessage }}</span>
                        </div>
                    @endif

                    <!-- Prompt Field -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="aiPrompt" class="text-xs font-bold uppercase tracking-wider text-slate-700">Permintaan / Prompt Anda</label>
                            <span class="text-[11px] font-medium text-slate-600">Deskripsikan barang inventaris</span>
                        </div>
                        <textarea id="aiPrompt" wire:model="aiPrompt" rows="3"
                            class="w-full px-4 py-3.5 text-sm rounded-2xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all font-medium placeholder-slate-600 resize-none shadow-xs"
                            placeholder="Contoh: Buatkan 3 stok aksesoris komputer (mouse, keyboard, headset) lengkap dengan SKU dan harga"></textarea>
                        @error('aiPrompt')
                            <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Clean Control Bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-2xl bg-slate-50/80 border border-slate-200/70">
                        <div class="flex items-center space-x-3">
                            <label for="aiCount" class="text-xs font-bold uppercase tracking-wider text-slate-700">Jumlah Item:</label>
                            <div class="inline-flex items-center rounded-xl bg-white border border-slate-200/90 p-1 shadow-xs">
                                <button type="button" 
                                    wire:click="$set('aiCount', {{ max(1, $aiCount - 1) }})" 
                                    class="w-7 h-7 rounded-lg text-slate-700 hover:bg-slate-100 font-bold flex items-center justify-center transition active:scale-95"
                                    aria-label="Kurangi jumlah item">&minus;</button>
                                <input id="aiCount" wire:model.live="aiCount" type="number" min="1" max="10" 
                                    class="w-10 text-center text-xs font-black text-slate-900 border-0 focus:ring-0 p-0" readonly>
                                <button type="button" 
                                    wire:click="$set('aiCount', {{ min(10, $aiCount + 1) }})" 
                                    class="w-7 h-7 rounded-lg text-slate-700 hover:bg-slate-100 font-bold flex items-center justify-center transition active:scale-95"
                                    aria-label="Tambah jumlah item">&plus;</button>
                            </div>
                            <span class="text-[11px] text-slate-700 font-medium">(1-10 item)</span>
                        </div>

                        <button type="button" wire:click="generateWithAi" wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-gradient-to-r from-purple-600 via-fuchsia-600 to-indigo-600 hover:from-purple-700 hover:via-fuchsia-700 hover:to-indigo-700 disabled:opacity-75 text-white text-xs font-bold rounded-xl shadow-md shadow-purple-500/25 hover:shadow-glow-purple transition-all duration-200 flex items-center justify-center space-x-2 active:scale-95">
                            <svg wire:loading wire:target="generateWithAi" class="animate-spin -ml-0.5 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <svg wire:loading.remove wire:target="generateWithAi" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span wire:loading.remove wire:target="generateWithAi">Mulai Generate</span>
                            <span wire:loading wire:target="generateWithAi">Sedang Berpikir...</span>
                        </button>
                    </div>

                    <!-- AI Generated Items Result -->
                    @if (!empty($aiGeneratedItems))
                        <div class="border-t border-slate-100 pt-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Hasil Rekomendasi AI:</h3>
                                <span class="text-xs text-purple-800 font-bold bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200/80">
                                    {{ count($aiSelectedIndices) }} dari {{ count($aiGeneratedItems) }} dipilih
                                </span>
                            </div>
                            
                            <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                                @foreach ($aiGeneratedItems as $idx => $item)
                                    <label class="flex items-start space-x-3.5 p-3.5 rounded-2xl border transition-all cursor-pointer {{ in_array($idx, $aiSelectedIndices) ? 'border-purple-300 bg-purple-50/30 shadow-xs' : 'border-slate-200 bg-white hover:bg-slate-50' }}">
                                        <input type="checkbox" wire:model="aiSelectedIndices" value="{{ $idx }}"
                                            class="mt-1 w-4 h-4 rounded-md border-slate-300 text-purple-600 focus:ring-purple-500">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 flex-wrap">
                                                <span class="text-sm font-bold text-slate-900">{{ $item['name'] }}</span>
                                                @if(!empty($item['sku']))
                                                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                                                        {{ $item['sku'] }}
                                                    </span>
                                                @endif
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full border {{ \App\Features\Stock\Enums\StockStatus::tryFrom($item['status'])?->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $item['status'])) }}
                                                </span>
                                            </div>
                                            @if(!empty($item['description']))
                                                <p class="text-xs text-slate-700 mt-1 line-clamp-2 leading-relaxed">{{ $item['description'] }}</p>
                                            @endif
                                            <div class="flex items-center gap-4 mt-2 text-xs text-slate-700">
                                                <span>Qty: <strong class="text-slate-800">{{ $item['quantity'] }} {{ $item['unit'] }}</strong></span>
                                                @if(!empty($item['price']))
                                                    <span>Harga: <strong class="text-slate-900">Rp {{ number_format($item['price'], 0, ',', '.') }}</strong></span>
                                                @endif
                                                <span>Min: <strong class="text-slate-800">{{ $item['min_stock'] }}</strong></span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="pt-2 flex items-center justify-end gap-3">
                                <button type="button" @click="show = false; $wire.closeAiModal()"
                                    class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                                    Batal
                                </button>
                                <button type="button" wire:click="saveAiGenerated" wire:loading.attr="disabled"
                                    class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 disabled:opacity-75 text-white text-xs font-bold rounded-xl shadow-md shadow-purple-500/20 transition flex items-center space-x-2 active:scale-95">
                                    <svg wire:loading wire:target="saveAiGenerated" class="animate-spin -ml-0.5 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>Simpan Item Terpilih ({{ count($aiSelectedIndices) }})</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmingStockDeletionId)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-cloak
            @keydown.escape.window="show = false; $wire.cancelDelete()"
            class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm">
            <div @click.outside="show = false; $wire.cancelDelete()"
                class="bg-white rounded-3xl max-w-md w-full shadow-2xl shadow-slate-950/20 ring-1 ring-slate-900/10 overflow-hidden my-auto transition-all">
                
                <!-- Sleek Top Gradient Accent -->
                <div class="h-1.5 w-full bg-gradient-to-r from-rose-600 to-red-600"></div>

                <!-- Unified Header -->
                <div class="px-6 sm:px-7 pt-6 pb-2 flex items-start justify-between gap-4">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-rose-600 to-red-600 text-white flex items-center justify-center shadow-lg shadow-rose-500/25 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Konfirmasi Hapus</h2>
                            <p class="text-xs text-slate-700 mt-0.5 font-medium">Tindakan ini permanen dan tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false; $wire.cancelDelete()" aria-label="Tutup modal konfirmasi" 
                        class="p-2 -mr-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 sm:p-7">
                    <p class="text-sm font-medium text-slate-700 leading-relaxed">
                        Apakah Anda yakin ingin menghapus data stok barang ini dari database secara permanen?
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 sm:px-7 py-4 bg-slate-50/80 border-t border-slate-100">
                    <button type="button" @click="show = false; $wire.cancelDelete()" class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="button" wire:click="deleteStock" wire:loading.attr="disabled" class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 disabled:opacity-75 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 transition flex items-center space-x-1.5 active:scale-95">
                        <svg wire:loading wire:target="deleteStock" class="animate-spin -ml-0.5 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="deleteStock">Ya, Hapus</span>
                        <span wire:loading wire:target="deleteStock">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
