<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File Kearsipan - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f4f5] flex font-sans text-gray-800">

    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>

            <nav class="px-4 space-y-1">
                <a href="/ketua-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>

                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>

                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/ketua-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/ketua-pokja/upload" class="block px-4 py-2 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

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

        <div class="p-6">
            <a href="#" class="flex items-center gap-3 text-red-600 font-bold hover:text-red-500 transition text-sm">
                <i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i> Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        <header class="px-8 py-6 flex items-center justify-between sticky top-0 z-10 bg-[#f4f4f5]">
            <div class="flex items-center gap-4">
                <a href="/ketua-pokja/upload" class="text-black hover:text-blue-600 text-lg transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <span class="text-sm font-bold text-gray-500">Upload File Arsip - Ketua Pokja</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">ketua Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <div class="px-8 pb-10 max-w-5xl">
            <h2 class="text-3xl font-bold mb-8 text-black">Upload File Kearsipan</h2>

            <form action="{{ route('ketua.arsip.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-5">
                    <label for="nama_arsip" class="block text-sm font-bold text-black mb-1.5">Nama Arsip</label>
                    <input type="text" id="nama_arsip" name="nama_arsip" value="{{ old('nama_arsip') }}" placeholder="Nama Arsip" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('nama_arsip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
                    <div>
                        <label for="nomor_dokumen" class="block text-sm font-bold text-black mb-1.5">Nomor Dokumen</label>
                        <input type="text" id="nomor_dokumen" name="nomor_dokumen" value="{{ old('nomor_dokumen') }}" placeholder="Nomor Dokumen (opsional)" class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('nomor_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kategori_arsip_id" class="block text-sm font-bold text-black mb-1.5">Kategori</label>
                        <select id="kategori_arsip_id" name="kategori_arsip_id" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            <option value="" disabled {{ old('kategori_arsip_id') ? '' : 'selected' }}>Kategori Surat</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_arsip_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_arsip_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_dokumen" class="block text-sm font-bold text-black mb-1.5">Tanggal Dokumen</label>
                        <input type="date" id="tanggal_dokumen" name="tanggal_dokumen" value="{{ old('tanggal_dokumen') }}" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('tanggal_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-2 relative">
                    <div id="file-drop-box" class="w-full h-48 border border-blue-400 bg-[#f8f9fa] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-blue-50 transition relative overflow-hidden">
                        <input type="file" id="file_arsip" name="file_arsip" required accept=".pdf,.doc,.docx,.zip,.rar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <span id="file-drop-text" class="text-blue-600 font-bold text-base mb-2 z-0">Select File</span>
                        <span id="file-drop-subtext" class="text-gray-400 text-sm z-0">Upload File Kearsipan Disini (PDF, DOC, DOCX, ZIP, RAR — maks. 10MB)</span>
                    </div>
                </div>
                @error('file_arsip')
                    <p class="mb-5 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <button type="submit" class="w-full py-3 rounded-md bg-[#2563eb] text-white font-bold flex items-center justify-center gap-2 hover:bg-blue-700 transition shadow-sm">
                    Upload Arsip <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </main>

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

        const fileInput = document.getElementById('file_arsip');
        const fileDropText = document.getElementById('file-drop-text');
        const fileDropSubtext = document.getElementById('file-drop-subtext');

        fileInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                fileDropText.textContent = 'Select File';
                fileDropSubtext.textContent = 'Upload File Kearsipan Disini (PDF, DOC, DOCX, ZIP, RAR — maks. 10MB)';
                return;
            }

            fileDropText.textContent = 'File Terpilih';
            fileDropSubtext.textContent = file.name;
        });
    </script>
</body>
</html>