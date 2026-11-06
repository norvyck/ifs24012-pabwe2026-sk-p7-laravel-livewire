<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-2.5 mb-1.5">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                Akun Saya
            </span>
            <span class="text-xs text-slate-700 font-semibold">Pengaturan & Keamanan</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Profil</h1>
        <p class="text-sm text-slate-700 mt-1">Kelola identitas diri, foto profil 1:1, dan keamanan akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Foto Profil 1:1 -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-card border border-slate-200 flex flex-col items-center text-center">
            <h2 class="text-base font-bold text-slate-900 mb-5 self-start">Foto Profil (1:1)</h2>

            <div class="relative w-36 h-36 rounded-3xl overflow-hidden border-2 border-white shadow-xl shadow-slate-200/70 mb-5 group bg-slate-100 flex items-center justify-center">
                @if ($user->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo_path))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) }}?v={{ time() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-tr from-blue-700 to-indigo-800 text-white font-black text-3xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
            </div>

            <div class="w-full space-y-2.5">
                <input type="file" wire:model="photo" id="profilePhotoInput" accept="image/*" class="hidden">
                
                <button type="button" onclick="document.getElementById('profilePhotoInput').click()" 
                    class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 hover:shadow-glow transition-all duration-200 flex items-center justify-center space-x-2 active:scale-95">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Upload Foto Baru</span>
                </button>

                <div wire:loading wire:target="photo" class="text-center py-1">
                    <span class="inline-flex items-center text-xs text-blue-700 font-semibold space-x-1.5 animate-pulse">
                        <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>Mengunggah file foto...</span>
                    </span>
                </div>

                @if ($user->profile_photo_path)
                    <button type="button" wire:click="confirmDeletePhoto" wire:loading.attr="disabled" 
                        class="w-full py-2.5 px-4 bg-rose-50 hover:bg-rose-100 text-rose-800 text-xs font-bold rounded-xl border border-rose-300 transition duration-200 flex items-center justify-center space-x-2 active:scale-95 disabled:opacity-75">
                        <svg wire:loading.remove wire:target="confirmDeletePhoto" class="w-4 h-4 text-rose-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <svg wire:loading wire:target="confirmDeletePhoto" class="animate-spin w-4 h-4 text-rose-800" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Hapus Foto Profil</span>
                    </button>
                @endif
            </div>

            <p class="text-[11px] text-slate-700 mt-5 leading-relaxed">
                Mendukung PNG, JPG, GIF hingga 5MB. Foto otomatis dipotong menjadi rasio presisi 1:1.
            </p>
        </div>

        <!-- Kolom Kanan: Identitas Diri & Ubah Password -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Form Identitas Diri -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-card border border-slate-200">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <span class="p-2 bg-blue-50 text-blue-700 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 leading-snug">Ubah Identitas Diri</h2>
                        <p class="text-xs text-slate-700 font-medium">Perbarui nama lengkap, username, dan alamat email Anda</p>
                    </div>
                </div>

                <form wire:submit.prevent="updateProfileInformation" class="space-y-4">
                    <div>
                        <label for="profile-name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input id="profile-name" wire:model="name" type="text" 
                            class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all placeholder:text-slate-600" required>
                        @error('name') <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="profile-username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Username</label>
                            <input id="profile-username" wire:model="username" type="text" 
                                class="w-full px-4 py-2.5 text-sm font-mono font-medium rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all placeholder:text-slate-600" required>
                            @error('username') <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="profile-email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email</label>
                            <input id="profile-email" wire:model="email" type="email" 
                                class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all placeholder:text-slate-600" required>
                            @error('email') <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-3">
                        <button type="submit" wire:loading.attr="disabled" 
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-75 disabled:cursor-not-allowed text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition flex items-center space-x-2 active:scale-95">
                            <svg wire:loading wire:target="updateProfileInformation" class="animate-spin -ml-1 mr-1.5 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="updateProfileInformation">Simpan Perubahan Identitas</span>
                            <span wire:loading wire:target="updateProfileInformation">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form Ubah Password -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-card border border-slate-200">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <span class="p-2 bg-purple-50 text-purple-700 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 leading-snug">Ubah Password Akun</h2>
                        <p class="text-xs text-slate-700 font-medium">Pastikan akun Anda menggunakan kombinasi password yang kuat dan aman</p>
                    </div>
                </div>

                <form wire:submit.prevent="updatePassword" class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password Saat Ini</label>
                        <input id="current_password" wire:model="current_password" type="password" 
                            class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all placeholder:text-slate-600" 
                            placeholder="Password saat ini" required>
                        @error('current_password') <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="profile-password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password Baru</label>
                            <input id="profile-password" wire:model="password" type="password" 
                                class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all placeholder:text-slate-600" 
                                placeholder="Minimal 8 karakter" required>
                            @error('password') <span class="text-xs font-semibold text-rose-800 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" wire:model="password_confirmation" type="password" 
                                class="w-full px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all placeholder:text-slate-600" 
                                placeholder="Ulangi password baru" required>
                        </div>
                    </div>

                    <div class="flex justify-end pt-3">
                        <button type="submit" wire:loading.attr="disabled" 
                            class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 disabled:opacity-75 disabled:cursor-not-allowed text-white text-xs font-bold rounded-xl shadow-md transition flex items-center space-x-2 active:scale-95">
                            <svg wire:loading wire:target="updatePassword" class="animate-spin -ml-1 mr-1.5 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="updatePassword">Perbarui Password</span>
                            <span wire:loading wire:target="updatePassword">Memperbarui...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Crop Foto Profil -->
    @if ($showCropModal && $photo)
        <div class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm"
             wire:ignore.self
             x-data="{
                cropper: null,
                initCropper() {
                    const img = document.getElementById('imageToCrop');
                    if (!img) return;
                    if (this.cropper) {
                        this.cropper.destroy();
                    }
                    this.cropper = new Cropper(img, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 0.9,
                        responsive: true,
                        background: false
                    });
                },
                applyCrop() {
                    if (this.cropper) {
                        let canvas = this.cropper.getCroppedCanvas({
                            width: 400,
                            height: 400
                        });
                        @this.set('croppedPhoto', canvas.toDataURL('image/png'));
                        @this.saveCroppedPhoto();
                    }
                }
             }"
             x-init="$nextTick(() => { initCropper(); })">
            <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl shadow-slate-950/20 ring-1 ring-slate-900/10 overflow-hidden flex flex-col my-auto transition-all">
                
                <!-- Sleek Top Gradient Accent -->
                <div class="h-1.5 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>

                <!-- Unified Header -->
                <div class="px-6 sm:px-7 pt-6 pb-2 flex items-start justify-between gap-4">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/25 flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Potong Foto Profil</h2>
                            <p class="text-xs text-slate-700 mt-0.5 font-medium">Sesuaikan bingkai rasio presisi 1:1</p>
                        </div>
                    </div>
                    <button wire:click="closeCropModal" aria-label="Tutup modal potong foto" class="p-2 -mr-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 sm:p-7">
                    <div class="max-h-80 overflow-hidden flex items-center justify-center bg-slate-900 rounded-2xl shadow-inner">
                        <img id="imageToCrop" src="{{ $photo->temporaryUrl() }}" alt="Preview" class="max-w-full block">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 sm:px-7 py-4 bg-slate-50/80 border-t border-slate-100">
                    <button type="button" wire:click="closeCropModal" class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="button" @click="applyCrop()" wire:loading.attr="disabled" class="px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-75 disabled:cursor-not-allowed rounded-xl shadow-md shadow-blue-500/20 transition flex items-center space-x-1.5 active:scale-95">
                        <svg wire:loading wire:target="saveCroppedPhoto" class="animate-spin -ml-0.5 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveCroppedPhoto">Terapkan & Simpan Foto</span>
                        <span wire:loading wire:target="saveCroppedPhoto">Menyimpan Foto...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi Hapus Foto Profil -->
    @if ($confirmingPhotoDeletion)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-cloak
            @keydown.escape.window="show = false; $wire.cancelDeletePhoto()"
            class="modal-scroll-lock fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overscroll-contain p-4 sm:p-6 bg-slate-950/70 backdrop-blur-sm">
            <div @click.outside="show = false; $wire.cancelDeletePhoto()"
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
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Hapus Foto Profil</h2>
                            <p class="text-xs text-slate-700 mt-0.5 font-medium">Foto profil akan kembali ke avatar inisial</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false; $wire.cancelDeletePhoto()" aria-label="Tutup modal konfirmasi" 
                        class="p-2 -mr-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 sm:p-7">
                    <p class="text-sm font-medium text-slate-700 leading-relaxed">
                        Apakah Anda yakin ingin menghapus foto profil akun Anda?
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 sm:px-7 py-4 bg-slate-50/80 border-t border-slate-100">
                    <button type="button" @click="show = false; $wire.cancelDeletePhoto()" class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="button" wire:click="deletePhoto" wire:loading.attr="disabled" class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 disabled:opacity-75 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-500/20 transition flex items-center space-x-1.5 active:scale-95">
                        <svg wire:loading wire:target="deletePhoto" class="animate-spin -ml-0.5 mr-1 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="deletePhoto">Ya, Hapus Foto</span>
                        <span wire:loading wire:target="deletePhoto">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
