<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] flex font-sans text-gray-800">
    <!-- SIDEBAR KIRI -->
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>
            
            <!-- Menu Sidebar -->
            <nav class="px-4 space-y-1">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>  Dashboard
                </a>
                
                <!-- Dropdown Kelola Arsip -->
                <div class="pt-1">
                    <!-- Tambahin onclick sama id ikon-panah di sini -->
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i> Kelola Arsip</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Tambahin id submenu-arsip sama class max-h-0 overflow-hidden di sini -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-1 pt-1 pb-2">
                            <a href="{{ route('superadmin.arsip.penghapusan') }}" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Pengapusan Arsip</a>
                            <a href="{{ route('superadmin.arsip.laporan') }}" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Laporan Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('superadmin.peminjaman.validasi') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check w-5"></i> Validasi Peminjaman
                </a>

                <!-- Menu Kelola User (Aktif / Biru Solid) -->
                <a href="{{ route('superadmin.users') }}" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-1">
                    <i class="fa-solid fa-users w-5"></i> Kelola User
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines w-5"></i> Panduan Penggunaan
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-solid fa-gear w-5"></i> Pengaturan Sistem
                </a>
            </nav>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:text-red-400 transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
    </aside>

    <main class="flex-1 ml-64 min-h-screen">
        <header class="px-8 py-6 flex justify-end font-bold">
            <span class="mr-3">{{ auth()->user()->name }}</span>
            <i class="fa-solid fa-circle-user text-2xl ml-1"></i>
        </header>

        <div class="px-8 pb-10 max-w-7xl w-full">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-black">Kelola User</h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.users', array_merge(request()->query(), ['delete_mode' => $deleteMode ? 0 : 1])) }}" class="rounded-lg {{ $deleteMode ? 'bg-red-600 text-white' : 'bg-[#e9edf7] text-gray-700' }} px-5 py-2.5 text-sm font-bold shadow-sm hover:opacity-95 transition">
                        {{ $deleteMode ? 'Batal Hapus' : 'Hapus User' }}
                    </a>
                    <button type="button" onclick="toggleModal('modal-tambah-user')" class="bg-blue-600 text-white rounded-lg px-5 py-2.5 text-sm font-bold hover:bg-blue-700 shadow-sm transition">Tambahkan User</button>
                </div>
            </div>

            <form method="GET" action="{{ route('superadmin.users') }}" class="mb-6 space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <input name="search" value="{{ $filters['search'] }}" placeholder="Nama atau Username" class="min-w-[220px] flex-1 rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-500 focus:outline-none">
                    <select name="role_id" class="min-w-[180px] flex-1 rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-blue-500 focus:outline-none">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ (string) $filters['roleId'] === (string) $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <select name="pokja_id" class="min-w-[180px] flex-1 rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-blue-500 focus:outline-none">
                        <option value="">Semua Kelompok Kerja</option>
                        @foreach ($pokjas as $pokja)
                            <option value="{{ $pokja->id }}" {{ (string) $filters['pokjaId'] === (string) $pokja->id ? 'selected' : '' }}>{{ $pokja->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-md bg-blue-600 px-8 py-2.5 text-sm font-bold text-white hover:bg-blue-700">Filter</button>
                    <a href="{{ route('superadmin.users') }}" class="rounded-md border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50">Reset</a>
                </div>
                @if (request()->has('delete_mode'))
                    <input type="hidden" name="delete_mode" value="{{ $deleteMode ? 1 : 0 }}">
                @endif
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[860px] text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-4 text-sm font-bold text-indigo-900">Nama User</th>
                                <th class="px-4 py-4 text-sm font-bold text-indigo-900">Username</th>
                                <th class="px-4 py-4 text-sm font-bold text-indigo-900">Role</th>
                                <th class="px-4 py-4 text-sm font-bold text-indigo-900">Kelompok Kerja</th>
                                <th class="px-4 py-4 text-sm font-bold text-indigo-900">Status Akun</th>
                                @if ($deleteMode)
                                    <th class="px-4 py-4 text-sm font-bold text-indigo-900 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition" data-user-id="{{ $user->id }}" data-username="{{ $user->username }}">
                                    <td class="px-4 py-4 text-sm font-semibold text-gray-800">{{ $user->name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-700">{{ $user->username }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-700">{{ $user->role?->name ?? '-' }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-700">{{ $user->pokja?->name ?? 'Tidak terkait Pokja' }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        <span class="inline-flex items-center gap-2 rounded-full px-2.5 py-1 {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $user->status === 'active' ? 'Active' : 'Inactive' }}
                                            <span class="h-1.5 w-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-500' : 'bg-slate-500' }}"></span>
                                        </span>
                                    </td>
                                    @if ($deleteMode)
                                        <td class="px-4 py-4 text-center">
                                            @if ($user->id !== auth()->id())
                                                <button type="button" data-delete-action="{{ route('superadmin.users.delete', $user) }}" class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-bold text-white transition hover:scale-[1.02] hover:bg-red-700">Hapus</button>
                                            @else
                                                <span class="text-xs text-gray-400">Akun aktif</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $deleteMode ? 6 : 5 }}" class="p-10 text-center text-sm text-gray-500">Belum ada user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </main>

    <div id="modal-tambah-user" class="{{ $errors->any() ? 'flex' : 'hidden' }} fixed inset-0 z-50 items-center justify-center bg-black/40 px-4 transition-all duration-300 ease-out">
        <div class="modal-panel w-full max-w-md rounded-[28px] bg-white p-6 shadow-[0_25px_80px_rgba(15,23,42,0.18)] scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Tambah User</h2>
                <button type="button" class="close-modal-btn flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200 hover:text-gray-800" data-close="modal-tambah-user" aria-label="Tutup popup">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="add-user-form" action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <input name="username" value="{{ old('username') }}" required placeholder="Isi Username" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 transition-all duration-200 ease-in-out hover:border-blue-400 focus:border-blue-500 focus:outline-none {{ $errors->has('username') ? 'border-red-400' : '' }}">
                    @error('username')
                        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="password" name="password" value="{{ old('password') }}" required placeholder="Password" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 transition-all duration-200 ease-in-out hover:border-blue-400 focus:border-blue-500 focus:outline-none {{ $errors->has('password') ? 'border-red-400' : '' }}">
                    @error('password')
                        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative custom-select" data-select="role">
                    <input type="hidden" name="role_id" value="{{ old('role_id') }}">
                    <button type="button" class="select-trigger flex w-full items-center justify-between rounded-xl border border-gray-300 bg-white px-4 py-3 text-left text-sm text-gray-700 transition-all duration-200 ease-in-out hover:border-blue-400 focus:border-blue-500 focus:outline-none {{ $errors->has('role_id') ? 'border-red-400' : '' }}">
                        <span class="select-label">Role</span>
                        <i class="select-icon fa-solid fa-chevron-down text-xs text-gray-500 transition-transform duration-200"></i>
                    </button>
                    <div class="select-menu absolute left-0 right-0 z-10 mt-2 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg opacity-0 translate-y-[-8px] transition-all duration-200 ease-out pointer-events-none hidden">
                        <button type="button" class="select-option block w-full border-0 bg-transparent px-4 py-2.5 text-left text-sm text-gray-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-600" data-value="" data-label="Role">Role</button>
                        @foreach ($roles as $role)
                            <button type="button" class="select-option block w-full border-0 bg-transparent px-4 py-2.5 text-left text-sm text-gray-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-600" data-value="{{ $role->id }}" data-label="{{ $role->name }}">{{ $role->name }}</button>
                        @endforeach
                    </div>
                    @error('role_id')
                        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative custom-select" data-select="pokja">
                    <input type="hidden" name="pokja_id" value="{{ old('pokja_id') }}">
                    <button type="button" class="select-trigger flex w-full items-center justify-between rounded-xl border border-gray-300 bg-white px-4 py-3 text-left text-sm text-gray-700 transition-all duration-200 ease-in-out hover:border-blue-400 focus:border-blue-500 focus:outline-none {{ $errors->has('pokja_id') ? 'border-red-400' : '' }}">
                        <span class="select-label">Kelompok Kerja</span>
                        <i class="select-icon fa-solid fa-chevron-down text-xs text-gray-500 transition-transform duration-200"></i>
                    </button>
                    <div class="select-menu absolute left-0 right-0 z-10 mt-2 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg opacity-0 translate-y-[-8px] transition-all duration-200 ease-out pointer-events-none hidden">
                        <button type="button" class="select-option block w-full border-0 bg-transparent px-4 py-2.5 text-left text-sm text-gray-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-600" data-value="" data-label="Kelompok Kerja">Kelompok Kerja</button>
                        @foreach ($pokjas as $pokja)
                            <button type="button" class="select-option block w-full border-0 bg-transparent px-4 py-2.5 text-left text-sm text-gray-700 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-600" data-value="{{ $pokja->id }}" data-label="{{ $pokja->name }}">{{ $pokja->name }}</button>
                        @endforeach
                    </div>
                    @error('pokja_id')
                        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="button" class="close-modal-btn flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-700 transition hover:bg-gray-50" data-close="modal-tambah-user">Batal</button>
                    <button type="submit" class="submit-btn flex-1 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-700">Tambahkan User</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-konfirmasi-hapus" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 px-4 transition-all duration-300 ease-out">
        <div class="modal-panel w-full max-w-md rounded-[28px] bg-white px-8 py-7 shadow-[0_25px_80px_rgba(15,23,42,0.18)] scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="mb-4 flex justify-end">
                <button type="button" class="close-modal-btn flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200 hover:text-gray-800" data-close="modal-konfirmasi-hapus" aria-label="Tutup popup">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <p class="text-center text-xl font-semibold leading-relaxed text-gray-800">Apakah anda yakin untuk menghapus user <span id="delete-user-name" class="font-bold text-gray-900">ini</span>?</p>
            <form id="delete-user-form" method="POST" class="mt-7">
                @csrf
                @method('DELETE')
                <div class="flex items-center justify-center gap-4">
                    <button type="button" class="close-modal-btn min-w-[110px] rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-700 transition hover:bg-gray-50" data-close="modal-konfirmasi-hapus">Batal</button>
                    <button type="submit" class="confirm-delete-btn min-w-[110px] rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-red-700">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-sukses" class="{{ session('success') ? 'flex' : 'hidden' }} fixed inset-0 z-50 items-center justify-center bg-black/40 px-4 transition-all duration-300 ease-out">
        <div class="modal-panel w-full max-w-md rounded-[28px] bg-white px-8 py-7 text-center shadow-[0_25px_80px_rgba(15,23,42,0.18)] scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-3xl text-emerald-600">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 id="success-modal-message" class="mt-6 text-2xl font-bold text-gray-800">{{ session('success') ?? 'User berhasil ditambahkan.' }}</h3>
            <button type="button" class="close-modal-btn mt-8 w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-700" data-close="modal-sukses">OK</button>
        </div>
    </div>

    <div id="modal-error" class="{{ session('error') ? 'flex' : 'hidden' }} fixed inset-0 z-50 items-center justify-center bg-black/40 px-4 transition-all duration-300 ease-out">
        <div class="modal-panel w-full max-w-md rounded-[28px] bg-white px-8 py-7 text-center shadow-[0_25px_80px_rgba(15,23,42,0.18)] scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-3xl text-red-600">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <h3 id="error-modal-message" class="mt-6 text-2xl font-bold text-gray-800">{{ session('error') ?? 'Gagal menghapus user.' }}</h3>
            <button type="button" class="close-modal-btn mt-8 w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-700" data-close="modal-error">OK</button>
        </div>
    </div>

    <script>
        function toggleMenuArsip() {
            const submenu = document.getElementById('submenu-arsip');
            const ikonPanah = document.getElementById('ikon-panah');

            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                ikonPanah.classList.add('rotate-90');
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikonPanah.classList.remove('rotate-90');
            }
        }

        function setCustomSelectState(selectWrapper, open) {
            const trigger = selectWrapper.querySelector('.select-trigger');
            const menu = selectWrapper.querySelector('.select-menu');
            const icon = selectWrapper.querySelector('.select-icon');

            if (!trigger || !menu || !icon) {
                return;
            }

            menu.classList.toggle('hidden', !open);
            menu.classList.toggle('pointer-events-none', !open);
            menu.classList.toggle('opacity-0', !open);
            menu.classList.toggle('translate-y-[-8px]', !open);
            menu.classList.toggle('opacity-100', open);
            menu.classList.toggle('translate-y-0', open);
            icon.classList.toggle('rotate-180', open);
        }

        function syncCustomSelectValue(wrapper) {
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const triggerLabel = wrapper.querySelector('.select-label');
            const options = wrapper.querySelectorAll('.select-option');

            if (!hiddenInput || !triggerLabel) {
                return;
            }

            const selected = Array.from(options).find(option => option.dataset.value === hiddenInput.value);
            const text = selected ? selected.dataset.label : (wrapper.dataset.select === 'role' ? 'Role' : 'Kelompok Kerja');

            triggerLabel.textContent = text;
        }

        function closeAllCustomSelects() {
            document.querySelectorAll('.custom-select').forEach((selectWrapper) => setCustomSelectState(selectWrapper, false));
        }

        function setModalState(modalId, visible) {
            const modal = document.getElementById(modalId);
            if (!modal) {
                return;
            }

            const panel = modal.querySelector('.modal-panel');
            if (visible) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                requestAnimationFrame(() => {
                    panel.classList.remove('scale-95', 'opacity-0');
                    panel.classList.add('scale-100', 'opacity-100');
                });
                return;
            }

            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function openModal(modalId) {
            closeAllCustomSelects();
            const modal = document.getElementById(modalId);
            if (!modal) {
                return;
            }

            document.querySelectorAll('.modal-panel').forEach((panel) => {
                panel.classList.remove('scale-100', 'opacity-100');
                panel.classList.add('scale-95', 'opacity-0');
            });

            setModalState(modalId, true);
        }

        function closeModal(modalId) {
            setModalState(modalId, false);
        }

        function resetAddUserForm() {
            const form = document.getElementById('add-user-form');
            if (!form) {
                return;
            }

            form.reset();
            form.querySelectorAll('.custom-select').forEach((selectWrapper) => {
                const hiddenInput = selectWrapper.querySelector('input[type="hidden"]');
                if (hiddenInput) hiddenInput.value = '';
                syncCustomSelectValue(selectWrapper);
            });
        }

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) {
                return;
            }

            if (modal.classList.contains('hidden')) {
                openModal(modalId);
                return;
            }

            closeModal(modalId);
        }

        function openDeleteModal(action, username, row) {
            const form = document.getElementById('delete-user-form');
            const nameText = document.getElementById('delete-user-name');
            if (!form || !nameText) {
                return;
            }

            form.action = action;
            form.dataset.rowId = row ? row.dataset.userId : '';
            nameText.textContent = username || 'ini';
            openModal('modal-konfirmasi-hapus');
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.custom-select').forEach((selectWrapper) => {
                syncCustomSelectValue(selectWrapper);

                const trigger = selectWrapper.querySelector('.select-trigger');
                const menu = selectWrapper.querySelector('.select-menu');

                trigger.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const isOpen = !menu.classList.contains('hidden');
                    closeAllCustomSelects();
                    setCustomSelectState(selectWrapper, !isOpen);
                });

                selectWrapper.querySelectorAll('.select-option').forEach((option) => {
                    option.addEventListener('click', (event) => {
                        event.stopPropagation();
                        const hiddenInput = selectWrapper.querySelector('input[type="hidden"]');
                        const label = selectWrapper.querySelector('.select-label');
                        if (!hiddenInput || !label) {
                            return;
                        }

                        hiddenInput.value = option.dataset.value || '';
                        label.textContent = option.dataset.label || (selectWrapper.dataset.select === 'role' ? 'Role' : 'Kelompok Kerja');
                        setCustomSelectState(selectWrapper, false);
                    });
                });
            });

            document.addEventListener('click', (event) => {
                const clickedInsideSelect = event.target.closest('.custom-select');
                if (!clickedInsideSelect) {
                    closeAllCustomSelects();
                }
            });

            document.querySelectorAll('.close-modal-btn, [data-close]').forEach((button) => {
                button.addEventListener('click', () => {
                    const targetId = button.dataset.close;
                    if (targetId === 'modal-tambah-user') {
                        resetAddUserForm();
                    }
                    closeModal(targetId);
                });
            });

            const addUserForm = document.getElementById('add-user-form');
            if (addUserForm) {
                addUserForm.addEventListener('submit', () => {
                    const submitButton = addUserForm.querySelector('.submit-btn');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.textContent = 'Menambahkan...';
                    }
                });
            }

            const deleteForm = document.getElementById('delete-user-form');
            if (deleteForm) {
                deleteForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const submitButton = deleteForm.querySelector('.confirm-delete-btn');
                    const formData = new FormData(deleteForm);

                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.textContent = 'Menghapus...';
                    }

                    try {
                        const response = await fetch(deleteForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': formData.get('_token'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            throw new Error('Delete failed');
                        }

                        const data = await response.json().catch(() => ({ success: true }));
                        const row = document.querySelector('[data-user-id="' + (deleteForm.dataset.rowId || '') + '"]');
                        if (row) {
                            row.classList.add('opacity-0', 'transition-all', 'duration-200');
                            row.style.maxHeight = '0';
                            row.style.overflow = 'hidden';
                            setTimeout(() => row.remove(), 220);
                        }

                        closeModal('modal-konfirmasi-hapus');
                        document.getElementById('success-modal-message').textContent = data.message || 'Selamat, anda telah menghapus user ini.';
                        openModal('modal-sukses');
                        setTimeout(() => window.location.reload(), 1200);
                    } catch (error) {
                        closeModal('modal-konfirmasi-hapus');
                        document.getElementById('error-modal-message').textContent = 'Gagal menghapus user.';
                        openModal('modal-error');
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.textContent = 'Ya, Hapus';
                        }
                    }
                });
            }

            const deleteButtons = document.querySelectorAll('[data-delete-action]');
            deleteButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const row = button.closest('tr');
                    const username = row ? row.dataset.username : '';
                    openDeleteModal(button.dataset.deleteAction, username, row);
                });
            });

            const successModal = document.getElementById('modal-sukses');
            if (successModal && successModal.classList.contains('flex')) {
                setModalState('modal-sukses', true);
            }

            const errorModal = document.getElementById('modal-error');
            if (errorModal && errorModal.classList.contains('flex')) {
                setModalState('modal-error', true);
            }

            if (document.getElementById('modal-tambah-user') && document.querySelectorAll('.form-error').length) {
                setModalState('modal-tambah-user', true);
            }
        });

        document.querySelectorAll('.fixed.inset-0').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal(modal.id);
                }
            });
        });
    </script>
    <x-logout-confirmation />
</body>
</html>
