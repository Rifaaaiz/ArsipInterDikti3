<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arsip - Admin TU</title>
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
            
            <!-- Menu -->
            <nav class="px-4 space-y-1">
                <a href="/admin-tu" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <a href="/admin-tu/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>

                <a href="/admin-tu/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- Menu Laporan Arsip (Aktif/Biru Solid) -->
                <a href="/admin-tu/laporan" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-1">
                    <i class="fa-regular fa-file"></i> Laporan Arsip
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
        <div class="p-6">
            <a href="#" class="flex items-center gap-3 text-red-600 font-bold hover:text-red-500 transition text-sm">
                <i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i> Logout
            </a>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas -->
        <header class="px-8 py-6 flex items-center justify-end sticky top-0 z-10 bg-[#f4f7f6]">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">Ketua Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA LAPORAN ARSIP -->
        <div class="px-8 pb-10 max-w-7xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Laporan Arsip</h2>

            <!-- 2 Kartu Statistik di Atas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 max-w-3xl">
                <!-- Kartu 1 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-1">Total Arsip</p>
                    <p class="text-5xl font-bold text-gray-900 mb-2">1909</p>
                    <p class="text-sm text-gray-400 font-semibold">Dokumen</p>
                </div>
                <!-- Kartu 2 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-1">Total Arsip Kelompok Kerja</p>
                    <p class="text-5xl font-bold text-gray-900 mb-2">250</p>
                    <p class="text-sm text-gray-400 font-semibold">Dokumen</p>
                </div>
            </div>

            <!-- Form Filter Pencarian & Dropdown Interaktif -->
            <div class="flex items-center gap-3 mb-6 relative">
                <!-- Input Nama Surat -->
                <input type="text" placeholder="Nama Surat" class="w-64 bg-[#f4f7f6] border border-gray-400 px-4 py-2 rounded-md text-sm text-gray-700 outline-none focus:border-blue-500">
                
                <!-- Filter Tanggal Dokumen (Fungsi Date Picker Aktif) -->
                <div class="relative w-48">
                    <input type="date" class="w-full bg-[#f4f7f6] border border-gray-400 px-4 py-2 rounded-md text-sm text-gray-500 outline-none focus:border-blue-500 cursor-pointer">
                </div>
                
                <!-- Dropdown Kategori Surat -->
                <div class="relative w-48">
                    <button onclick="toggleDropdown('dropdown-kategori')" type="button" class="w-full bg-[#f4f7f6] border border-gray-400 px-4 py-2 rounded-md text-sm text-gray-700 flex items-center justify-between focus:outline-none">
                        <span id="label-kategori">Kategori Surat</span>
                        <i id="icon-kategori" class="fa-solid fa-arrow-right text-black text-xs transition-transform duration-300"></i>
                    </button>
                    <!-- Opsi Dropdown Kategori Surat -->
                    <div id="dropdown-kategori" class="hidden absolute left-0 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-30">
                        <div onclick="selectOption('kategori', 'Surat Peminjaman')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">Surat Peminjaman</div>
                        <div onclick="selectOption('kategori', 'Surat Arsip')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">Surat Arsip</div>
                    </div>
                </div>
                
                <!-- Dropdown Kelompok Kerja -->
                <div class="relative w-48">
                    <button onclick="toggleDropdown('dropdown-pokja')" type="button" class="w-full bg-[#f4f7f6] border border-gray-400 px-4 py-2 rounded-md text-sm text-gray-700 flex items-center justify-between focus:outline-none">
                        <span id="label-pokja">Kelompok Kerja</span>
                        <i id="icon-pokja" class="fa-solid fa-arrow-right text-black text-xs transition-transform duration-300"></i>
                    </button>
                    <!-- Opsi Dropdown Kelompok Kerja -->
                    <div id="dropdown-pokja" class="hidden absolute left-0 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-30 max-h-60 overflow-y-auto">
                        <div onclick="selectOption('pokja', 'TUBMN')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">TUBMN</div>
                        <div onclick="selectOption('pokja', 'BELMAWA')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">BELMAWA</div>
                        <div onclick="selectOption('pokja', 'SDPT')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">SDPT</div>
                        <div onclick="selectOption('pokja', 'Keuangan')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">Keuangan</div>
                        <div onclick="selectOption('pokja', 'HKT')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">HKT</div>
                        <div onclick="selectOption('pokja', 'RPM')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">RPM</div>
                        <div onclick="selectOption('pokja', 'Sistem Informasi')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">Sistem Informasi</div>
                        <div onclick="selectOption('pokja', 'Humas')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">Humas</div>
                        <div onclick="selectOption('pokja', 'Penjamin Mutu')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">Penjamin Mutu</div>
                        <div onclick="selectOption('pokja', 'ADIA')" class="px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition">ADIA</div>
                    </div>
                </div>
                
                <!-- Tombol Filter -->
                <button class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Filter</button>
            </div>

            <!-- Tabel Data Laporan -->
            <div class="bg-transparent overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-4 px-2 text-base font-bold text-indigo-800">Nama Arsip</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-800">Kategori</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-800">Kelompok Kerja</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-800">Tanggal Upload</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-800">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        <!-- Baris 1 -->
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Peminjaman Mobil</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Dokumen SK</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Hukum, Kepegawaian, dan Tata Laksana</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">19/09/2024</td>
                            <td class="py-3 px-2">
                                <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Lihat</button>
                            </td>
                        </tr>
                        <!-- Baris 2 -->
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">SK A.N Nanang</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Dokumen SK</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Pembelajaran dan Kemahasiswaan</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">19/09/2024</td>
                            <td class="py-3 px-2">
                                <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Lihat</button>
                            </td>
                        </tr>
                        <!-- Baris 3 -->
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Peminjaman Mobil</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Dokumen SK</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Keuangan</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">19/09/2024</td>
                            <td class="py-3 px-2">
                                <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Lihat</button>
                            </td>
                        </tr>
                        <!-- Baris 4 -->
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">SK A.N Nanang</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Dokumen SK</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Sumber Daya Perguruan Tinggi</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">19/09/2024</td>
                            <td class="py-3 px-2">
                                <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Lihat</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Navigasi Halaman) -->
            <div class="flex justify-center items-center gap-4 mt-8">
                <button class="text-gray-800 hover:text-black transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="flex gap-2">
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded font-bold shadow-sm">1</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-800 font-bold hover:bg-gray-200 rounded transition">2</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-800 font-bold hover:bg-gray-200 rounded transition">3</button>
                </div>
                <button class="text-gray-800 hover:text-black transition">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            
        </div>
    </main>

    <!-- SCRIPT INTERAKTIF DROPDOWN -->
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            // Tutup dropdown lain jika sedang terbuka
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if(el.id !== id) el.classList.add('hidden');
            });
            dropdown.classList.toggle('hidden');
        }

        function selectOption(type, value) {
            if(type === 'kategori') {
                document.getElementById('label-kategori').innerText = value;
                document.getElementById('dropdown-kategori').classList.add('hidden');
            } else if(type === 'pokja') {
                document.getElementById('label-pokja').innerText = value;
                document.getElementById('dropdown-pokja').classList.add('hidden');
            }
        }

        // Tutup dropdown jika klik di luar area
        window.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });
    </script>

</body>
</html>