<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Pokja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex h-screen w-full bg-[#f4f4f5] font-sans overflow-hidden">

    <!-- Sisi Kiri: Form Login & Logo -->
    <div class="w-full lg:w-[40%] bg-[#f4f4f5] flex flex-col justify-center px-10 lg:px-16 relative z-10">
        
        <!-- Logo / Supported By (Posisi Absolute di Atas Kiri) -->
        <div class="absolute top-12 left-10 lg:left-16 bg-white px-5 py-3 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
            <span class="text-sm font-bold text-gray-400">Supported by :</span>
            <!-- Ganti nama file sesuai dengan logo yang Anda simpan di public/images/ -->
            <img src="{{ asset('images/logo-wuri.webp') }}" alt="LLDIKTI WILAYAH 3" class="h-8 object-contain">
        </div>

        <!-- Kotak Form Login -->
        <div class="bg-[#f8f9fa] p-8 rounded-2xl shadow-sm border border-gray-200 mt-20">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Input Username -->
                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Username</label>
                    <input type="text" name="username" placeholder="Username" required 
                        class="w-full border border-gray-400 bg-transparent rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                </div>

                <!-- Input Password -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Password</label>
                    <input type="password" name="password" placeholder="Masukan Password" required 
                        class="w-full border border-gray-400 bg-transparent rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                </div>

                <!-- Tombol Masuk -->
                <button type="submit" 
                    class="w-full bg-[#2563eb] text-white font-bold py-3.5 rounded-lg hover:bg-blue-700 transition shadow-sm">
                    Masuk
                </button>
            </form>
            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first('username') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Sisi Kanan: Background Gambar Kota -->
    <!-- Ganti 'bg-login.png' dengan nama file gambar kota Anda yang ada di public/images/ -->
    <div class="hidden lg:block lg:w-[60%] relative bg-[#a5d8dd]">
        <div class="absolute inset-0 bg-cover bg-bottom bg-no-repeat" 
             style="background-image: url('{{ asset('images/bg-login-dikti.webp') }}');">
        </div>
    </div>

</body>
</html>