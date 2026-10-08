<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Peminjaman - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f4f5] flex font-sans text-gray-800">

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
            
            <!-- Menu -->
            <nav class="px-4 space-y-1">
                <a href="/ketua-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>
                
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <!-- Panah menghadap ke bawah karena sub-menu sedang aktif dibuka -->
                        <i id="ikon-panah" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Sub-menu Terbuka -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <!-- Menu Aktif (Biru Solid) -->
                            <a href="/ketua-pokja/ajukan" class="block px-4 py-2 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">Ajukan Peminjaman</a>
                            <a href="/ketua-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <!-- Validasi Upload (Khas Ketua Pokja) -->
                <a href="/ketua-pokja/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-circle-check"></i> Validasi Upload
                </a>

                <a href="/ketua-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <a href="/ketua-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas -->
        <header class="px-8 py-6 flex items-center justify-between sticky top-0 z-10 bg-[#f4f4f5]">
            <div class="flex items-center gap-4">
                <a href="/ketua-pokja/ajukan" class="text-black hover:text-blue-600 text-lg transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <span class="text-sm font-bold text-gray-500">Input Peminjaman - Ketua Pokja</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">ketua Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN FORMULIR -->
        <div class="p-8">
            <h2 class="text-3xl font-bold mb-6 text-gray-900">Permohonan Peminjaman Formulir</h2>

            <form action="{{ route('ketua.peminjaman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Nama Surat -->
                <div class="mb-5">
                    <label class="block text-sm font-bold text-black mb-1.5">Nama Surat</label>
                    <input type="text" name="nama_surat" value="{{ old('nama_surat') }}" required placeholder="Nama Surat" class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>

                <!-- Grid 3 Kolom: Kategori, Tanggal Mulai, Tanggal Selesai -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
                    
                    <!-- Kategori (Custom Dropdown) -->
                    <div class="relative">
                        <label class="block text-sm font-bold text-black mb-1.5">Kategori</label>
                        
                        <!-- Trigger Dropdown -->
                        <button type="button" onclick="toggleKategori()" class="w-full flex items-center justify-between border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm focus:outline-none group">
                            <span id="kategori-text" class="text-gray-400">Kategori Surat</span>
                            <i id="kategori-icon" class="fa-solid fa-arrow-right text-black transition-transform duration-300"></i>
                        </button>
                        
                        <!-- Menu Dropdown Gelap (Sesuai Gambar 2) -->
                        <div id="kategori-menu" class="hidden absolute top-0 left-0 w-full bg-[#1c1c1c] text-gray-300 rounded-md shadow-xl border border-gray-700 z-20 overflow-hidden">
                            <!-- Header Dropdown -->
                            <div class="px-4 py-3 flex justify-between items-center cursor-pointer border-b border-gray-700 bg-[#222222]" onclick="toggleKategori()">
                                <span class="font-medium text-gray-400">Kategori Surat</span>
                                <i class="fa-solid fa-arrow-down text-black font-bold"></i>
                            </div>
                            <!-- Opsi Dropdown -->
                            @foreach ($kategoris as $kategori)
                                <div class="px-4 py-3 cursor-pointer hover:bg-gray-700 hover:text-white transition" onclick="selectKategori('{{ $kategori->id }}', '{{ addslashes($kategori->name) }}')">
                                    {{ $kategori->name }}
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Input Hidden untuk menampung nilai yang dipilih agar bisa di-submit -->
                        <input type="hidden" name="kategori_id" id="kategori-input" value="{{ old('kategori_id') }}" required>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-bold text-black mb-1.5">Tanggal Mulai</label>
                        <div class="relative">
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        </div>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-sm font-bold text-black mb-1.5">Tanggal Selesai</label>
                        <div class="relative">
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-8">
                    <label class="block text-sm font-bold text-black mb-1.5">Keterangan</label>
                    <textarea name="keperluan" rows="8" required placeholder="Keterangan" class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none">{{ old('keperluan') }}</textarea>
                </div>

                <!-- Tombol Aksi -->
                <p data-upload-limit class="mt-1 text-xs text-gray-500">Maximal upload file 5 MB.</p>
                    {{-- BUTTON FILE + SUBMIT --}}
                <div class="mb-10 w-full">
                    <div class="flex flex-col gap-4">
                        
                        {{-- UPLOAD PDF --}}
                        <label for="file_pengajuan" id="label_file" class="w-full h-12 border border-blue-600 text-blue-600 bg-transparent rounded-md flex items-center justify-center gap-2 font-bold cursor-pointer hover:bg-blue-50 transition">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>Upload File PDF</span>
                        </label>
                        <input type="file" id="file_pengajuan" name="file_pengajuan" accept=".pdf,application/pdf" class="hidden">
                        
                        @error('file_pengajuan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- SUBMIT --}}
                        <button type="submit" class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-bold flex items-center justify-center gap-2 transition shadow-sm">
                            Submit Peminjaman <i class="fa-solid fa-arrow-right"></i>
                        </button>

                    </div>
                </div>
            </form>

        </div>
    </main>

    <!-- SCRIPT -->
    <script>
        // Script Animasi Menu Sidebar
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

        // Script Custom Dropdown Kategori
        function toggleKategori() {
            const menu = document.getElementById('kategori-menu');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        }

        function selectKategori(value, label) {
            // Ubah teks di tombol trigger
            const textElement = document.getElementById('kategori-text');
            textElement.textContent = label;
            textElement.classList.remove('text-gray-400');
            textElement.classList.add('text-black');
            
            // Simpan value ke input hidden
            document.getElementById('kategori-input').value = value;
            
            // Tutup dropdown
            document.getElementById('kategori-menu').classList.add('hidden');
        }

        // Menutup dropdown jika user klik di luar area dropdown
        window.addEventListener('click', function(e) {
            const menu = document.getElementById('kategori-menu');
            const triggerBtn = document.querySelector('button[onclick="toggleKategori()"]');
            
            if (!menu.contains(e.target) && !triggerBtn.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
    <x-logout-confirmation />
</body>
</html>
