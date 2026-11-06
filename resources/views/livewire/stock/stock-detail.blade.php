<div>
    <!-- Toast UI Editor CDN Styles & Scripts -->
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css" />
    <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>

    <!-- Modern Breadcrumb & Top Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mb-8">
        <div class="flex items-center space-x-3.5">
            <a href="{{ route('stocks.index') }}" 
               class="p-2.5 rounded-2xl bg-white/90 border border-slate-200/90 text-slate-600 hover:text-blue-600 hover:bg-blue-50 shadow-sm transition-all duration-200 active:scale-95" 
               title="Kembali ke Daftar Stok"
               aria-label="Kembali ke Daftar Stok">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight truncate max-w-md sm:max-w-xl">{{ $stock->name }}</h1>
                    @if($stock->sku)
                        <span class="px-2.5 py-0.5 text-xs font-mono font-bold rounded-lg bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $stock->sku }}
                        </span>
                    @endif
                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full border {{ \App\Features\Stock\Enums\StockStatus::tryFrom($stock->status)?->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
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
                <p class="text-xs text-slate-500 mt-1 font-medium">Dibuat pada {{ $stock->created_at ? $stock->created_at->format('d M Y H:i') : '-' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Edit Stock Button -->
            <button wire:click="openEditModal" wire:loading.attr="disabled" 
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-75 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 hover:shadow-glow transition-all duration-200 active:scale-95">
                <svg wire:loading.remove wire:target="openEditModal" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <svg wire:loading wire:target="openEditModal" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Edit Stok</span>
            </button>

            <!-- Adjust Quantity Stepper Pill -->
            <div class="inline-flex items-center rounded-xl border border-slate-200/90 bg-white/95 p-1 shadow-xs" x-data="{ busy: false }">
                <button type="button"
                    x-bind:disabled="busy || {{ $stock->quantity <= 0 ? 'true' : 'false' }}"
                    x-on:click="
                        if (busy) return;
                        busy = true;
                        $wire.adjustQuantity(-1).finally(() => busy = false);
                    "
                    class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 hover:bg-rose-50 hover:text-rose-600 disabled:opacity-40 text-xs font-bold transition flex items-center justify-center active:scale-95"
                    title="Kurangi 1"
                    aria-label="Kurangi kuantitas stok">
                    &minus;
                </button>
                <span class="px-3 text-xs font-black text-slate-900">
                    {{ $stock->quantity }} {{ $stock->unit }}
                </span>
                <button type="button"
                    x-bind:disabled="busy"
                    x-on:click="
                        if (busy) return;
                        busy = true;
                        $wire.adjustQuantity(1).finally(() => busy = false);
                    "
                    class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 disabled:opacity-40 text-xs font-bold transition flex items-center justify-center active:scale-95"
                    title="Tambah 1"
                    aria-label="Tambah kuantitas stok">
                    &plus;
                </button>
            </div>

            <!-- Delete Button -->
            <button wire:click="confirmDeleteStock" wire:loading.attr="disabled" 
                class="inline-flex items-center space-x-1.5 px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 text-xs font-bold rounded-xl transition duration-200 active:scale-95 disabled:opacity-75">
                <svg wire:loading.remove wire:target="confirmDeleteStock" class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <svg wire:loading wire:target="confirmDeleteStock" class="animate-spin w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Hapus</span>
            </button>
        </div>
    </div>

    <!-- Cover Stock Section -->
    <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-card border border-slate-200/80 overflow-hidden mb-8">
        <input type="file" wire:model="coverPhoto" id="coverPhotoInput" accept="image/*" class="hidden">

        @if ($stock->cover_image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($stock->cover_image_path))
            <div class="relative group h-64 sm:h-96 w-full bg-slate-900 overflow-hidden">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($stock->cover_image_path) }}?v={{ time() }}" alt="Cover Stok Barang" class="w-full h-full object-cover">
                
                <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-3">
                    <button type="button" onclick="document.getElementById('coverPhotoInput').click()" 
                        class="px-4 py-2.5 bg-white text-slate-800 text-xs font-bold rounded-xl shadow-lg hover:bg-slate-50 transition flex items-center space-x-2 active:scale-95">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Ganti Cover</span>
                    </button>
                    <button type="button" wire:click="confirmDeleteCover" wire:loading.attr="disabled" 
                        class="px-4 py-2.5 bg-rose-600 text-white text-xs font-bold rounded-xl shadow-lg hover:bg-rose-700 disabled:opacity-75 transition flex items-center space-x-2 active:scale-95">
                        <svg wire:loading.remove wire:target="confirmDeleteCover" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <svg wire:loading wire:target="confirmDeleteCover" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Hapus Cover</span>
                    </button>
                </div>
            </div>
        @else
            <div class="p-10 sm:p-14 text-center bg-gradient-to-b from-slate-50 to-white flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-3xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 mb-4 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">Tambahkan Cover Gambar untuk Stok Barang Ini</h2>
                <p class="text-xs text-slate-600 mt-1 max-w-sm">Mendukung format gambar PNG, JPG, GIF hingga ukuran 5MB untuk katalog produk.</p>
                
                <button type="button" onclick="document.getElementById('coverPhotoInput').click()" 
                    class="mt-5 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition flex items-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Upload Cover Gambar</span>
                </button>
            </div>
        @endif

        <div wire:loading wire:target="coverPhoto" class="p-3 bg-blue-50 border-t border-blue-100 text-center text-xs text-blue-700 font-semibold animate-pulse">
            Sedang mengunggah dan memproses gambar cover...
        </div>
        @error('coverPhoto')
            <div class="p-3 bg-rose-50 border-t border-rose-100 text-center text-xs text-rose-600 font-semibold">
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Stock Details Overview Cards -->
    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white/95 backdrop-blur-md p-5 rounded-2xl shadow-card border border-slate-200/80">
                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1.5">Status Ketersediaan</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ \App\Features\Stock\Enums\StockStatus::tryFrom($stock->status)?->badgeClass() ?? 'bg-slate-100 text-slate-700' }}">
                    {{ \App\Features\Stock\Enums\StockStatus::tryFrom($stock->status)?->label() ?? ucfirst($stock->status) }}
                </span>
            </div>

            <div class="bg-white/95 backdrop-blur-md p-5 rounded-2xl shadow-card border border-slate-200/80">
                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1.5">Jumlah Stok Saat Ini</span>
                <div class="text-xl font-black text-slate-900">
                    {{ $stock->quantity }} <span class="text-sm font-semibold text-slate-600">{{ $stock->unit }}</span>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-md p-5 rounded-2xl shadow-card border border-slate-200/80">
                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1.5">Ambang Batas Minimum</span>
                <div class="text-xl font-bold text-slate-800">
                    {{ $stock->min_stock }} <span class="text-sm font-semibold text-slate-600">{{ $stock->unit }}</span>
                </div>
            </div>

            <div class="bg-white/95 backdrop-blur-md p-5 rounded-2xl shadow-card border border-slate-200/80">
                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1.5">Harga Satuan</span>
                <div class="text-xl font-black text-slate-900">
                    {{ $stock->price !== null ? 'Rp ' . number_format($stock->price, 0, ',', '.') : '-' }}
                </div>
            </div>
        </div>

        <!-- Description Viewer Section with Toast UI -->
        <div class="bg-white/95 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-card border border-slate-200/80">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Deskripsi / Spesifikasi Produk</h2>
                    <p class="text-xs text-slate-600 mt-0.5">Format Markdown & Rich Text</p>
                </div>
            </div>

            @if (!empty($stock->description))
                <div wire:ignore
                     x-data="{
                         init() {
                             toastui.Editor.factory({
                                 el: this.$refs.viewerContainer,
                                 viewer: true,
                                 initialValue: @js($stock->description)
                             });
                         }
                     }"
                     class="prose prose-slate max-w-none text-sm">
                    <div x-ref="viewerContainer"></div>
                </div>
            @else
                <div class="py-8 text-center text-slate-600 text-sm font-medium">
                    Belum ada deskripsi yang ditambahkan untuk stok barang ini.
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Stock Modal -->
    @if ($showEditModal)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-cloak
            @keydown.escape.window="show = false; $wire.closeEditModal()"
            class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm">
            <div @click.outside="show = false; $wire.closeEditModal()"
                class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl shadow-slate-950/20 ring-1 ring-slate-900/10 max-h-[92vh] overflow-hidden flex flex-col my-auto transition-all">
                
                <!-- Sleek Top Gradient Accent -->
                <div class="h-1.5 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>

                <!-- Unified Header -->
                <div class="px-6 sm:px-8 pt-6 pb-2 flex items-start justify-between gap-4">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/25 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Edit Stok Barang</h2>
                            <p class="text-xs text-slate-600 mt-0.5 font-medium">Perbarui atribut dan data inventaris barang Anda</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false; $wire.closeEditModal()" aria-label="Tutup form edit stok" 
                        class="p-2 -mr-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveStock" class="flex flex-col flex-1 min-h-0">
                    <div class="space-y-4 px-6 py-5 overflow-y-auto flex-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label for="stock-edit-name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Barang</label>
                                <input id="stock-edit-name" wire:model="name" type="text"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all"
                                    required>
                                @error('name') <span class="text-xs font-semibold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="stock-edit-sku" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Kode SKU</label>
                                <input id="stock-edit-sku" wire:model="sku" type="text"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-mono transition-all">
                                @error('sku') <span class="text-xs font-semibold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="stock-edit-unit" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Satuan</label>
                                <input id="stock-edit-unit" wire:model="unit" type="text"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all"
                                    required>
                                @error('unit') <span class="text-xs font-semibold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="stock-edit-quantity" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Jumlah Stok</label>
                                <input id="stock-edit-quantity" wire:model.live="quantity" type="number" min="0"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all"
                                    required>
                                @error('quantity') <span class="text-xs font-semibold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="stock-edit-minStock" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Ambang Stok Menipis (Min. Stock)</label>
                                <input id="stock-edit-minStock" wire:model.live="minStock" type="number" min="0"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all"
                                    required>
                                @error('minStock') <span class="text-xs font-semibold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="stock-edit-price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Harga Satuan (Rp, Opsional)</label>
                                <input id="stock-edit-price" wire:model="price" type="number" min="0" step="0.01"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200/90 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-sm font-medium transition-all">
                                @error('price') <span class="text-xs font-semibold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Status (Otomatis)</label>
                                <div class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold flex items-center">
                                    <span class="{{ $status === 'available' ? 'text-emerald-700' : ($status === 'low_stock' ? 'text-amber-700' : 'text-rose-700') }}">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi / Spesifikasi Barang (Toast UI)</label>
                                <span class="text-[11px] font-semibold text-slate-400">Markdown & WYSIWYG</span>
                            </div>

                            <div wire:ignore
                                 x-data="{
                                     desc: @entangle('description'),
                                     editor: null,
                                     init() {
                                         this.editor = new toastui.Editor({
                                             el: this.$refs.editEditorContainer,
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
                                <div x-ref="editEditorContainer"></div>
                            </div>
                            @error('description') <span class="text-xs font-semibold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 sm:px-8 py-4 bg-slate-50/80 border-t border-slate-100">
                        <button type="button" @click="show = false; $wire.closeEditModal()"
                            class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-75 disabled:cursor-not-allowed rounded-xl shadow-md shadow-blue-500/20 transition flex items-center space-x-2">
                            <svg wire:loading wire:target="saveStock" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveStock">Simpan Perubahan</span>
                            <span wire:loading wire:target="saveStock">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Cover Confirmation Modal -->
    @if ($confirmingCoverDeletion)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-cloak
            @keydown.escape.window="show = false; $wire.cancelDeleteCover()"
            class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm">
            <div @click.outside="show = false; $wire.cancelDeleteCover()"
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
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Hapus Cover</h2>
                            <p class="text-xs text-slate-600 mt-0.5 font-medium">Berkas gambar cover akan dihapus permanen</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false; $wire.cancelDeleteCover()" aria-label="Tutup modal konfirmasi" 
                        class="p-2 -mr-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 sm:p-7">
                    <p class="text-sm font-medium text-slate-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus gambar cover untuk stok barang ini?
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 sm:px-7 py-4 bg-slate-50/80 border-t border-slate-100">
                    <button type="button" @click="show = false; $wire.cancelDeleteCover()" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="button" wire:click="deleteCoverPhoto" wire:loading.attr="disabled" class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 disabled:opacity-75 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 transition flex items-center space-x-1.5 active:scale-95">
                        <svg wire:loading wire:target="deleteCoverPhoto" class="animate-spin -ml-0.5 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="deleteCoverPhoto">Ya, Hapus Cover</span>
                        <span wire:loading wire:target="deleteCoverPhoto">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Stock Confirmation Modal -->
    @if ($confirmingStockDeletion)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-cloak
            @keydown.escape.window="show = false; $wire.cancelDeleteStock()"
            class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm">
            <div @click.outside="show = false; $wire.cancelDeleteStock()"
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
                            <p class="text-xs text-slate-600 mt-0.5 font-medium">Barang ini dan cover gambarnya akan dihapus</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false; $wire.cancelDeleteStock()" aria-label="Tutup modal konfirmasi" 
                        class="p-2 -mr-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 sm:p-7">
                    <p class="text-sm font-medium text-slate-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus barang <strong>{{ $stock->name }}</strong> secara permanen?
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 sm:px-7 py-4 bg-slate-50/80 border-t border-slate-100">
                    <button type="button" @click="show = false; $wire.cancelDeleteStock()" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="button" wire:click="deleteStock" wire:loading.attr="disabled" class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 disabled:opacity-75 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 transition flex items-center space-x-1.5 active:scale-95">
                        <svg wire:loading wire:target="deleteStock" class="animate-spin -ml-0.5 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="deleteStock">Ya, Hapus Barang</span>
                        <span wire:loading wire:target="deleteStock">Menghapus...</span>
                    </button>
                </div>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
