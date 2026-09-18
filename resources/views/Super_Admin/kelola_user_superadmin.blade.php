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
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-solid fa-house w-5"></i> Dashboard
                </a>
                
                <!-- Dropdown Kelola Arsip -->
                <div class="pt-1">
                    <!-- Tambahin onclick sama id ikon-panah di sini -->
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i> Kelola Arsip</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Tambahin id submenu-arsip sama class max-h-0 overflow-hidden di sini -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-1 pt-1 pb-2">
                            <a href="#" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Pengapusan Arsip</a>
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
        <div class="p-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 text-red-600 font-bold hover:text-red-500 transition text-sm w-full">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-64 min-h-screen">
        <header class="px-8 py-6 flex justify-end font-bold">
            <span class="mr-3">{{ auth()->user()->name }}</span>
            <i class="fa-solid fa-circle-user text-2xl ml-1"></i>
        </header>

        <div class="px-8 pb-10 max-w-7xl w-full">
            @if (session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

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
                                <tr class="hover:bg-gray-50">
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
                                                <form method="POST" action="{{ route('superadmin.users.delete', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-red-700">Hapus</button>
                                                </form>
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

    <div id="modal-tambah-user" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
        <div class="w-full max-w-sm rounded-3xl bg-[#f8fafc] p-8 shadow-2xl">
            <h2 class="mb-8 text-center text-3xl font-bold text-gray-800">Tambah User</h2>
            <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <input name="name" value="{{ old('name') }}" required placeholder="Nama" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                <input name="username" value="{{ old('username') }}" required placeholder="Username" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                <input name="password" type="password" required minlength="8" placeholder="Password minimal 8 karakter" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                <select name="role_id" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <select name="pokja_id" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Pilih Kelompok Kerja</option>
                    @foreach ($pokjas as $pokja)
                        <option value="{{ $pokja->id }}" {{ old('pokja_id') == $pokja->id ? 'selected' : '' }}>{{ $pokja->name }}</option>
                    @endforeach
                </select>
                <select name="status" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-tambah-user')" class="flex-1 rounded-lg border border-gray-300 bg-white py-3 font-bold text-gray-700">Batal</button>
                    <button type="submit" class="flex-1 rounded-lg bg-blue-600 py-3 font-bold text-white hover:bg-blue-700">Tambahkan User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMenuArsip() {
            const submenu = document.getElementById('submenu-arsip');
            const ikonPanah = document.getElementById('ikon-panah');
            
            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                ikonPanah.classList.add('rotate-180');
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikonPanah.classList.remove('rotate-180');
            }
        }
    </script>
</body>
</html>
