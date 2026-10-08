<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Pokja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex min-h-screen w-full bg-[#f4f4f5] font-sans lg:h-screen lg:overflow-hidden">

    <!-- Sisi Kiri: Form Login & Logo -->
    <div class="relative z-10 flex w-full flex-col justify-center bg-[#f4f4f5] px-8 py-12 sm:px-14 lg:w-[32%] lg:px-10 lg:py-0">
        
        <!-- Logo / Supported By (Posisi Absolute di Atas Kiri) -->
        <div class="absolute left-8 top-8 flex items-center gap-3 rounded-lg border border-gray-100 bg-white px-5 py-3 shadow-sm sm:left-14 lg:left-10 lg:top-12">
            <span class="text-sm font-bold text-gray-400">Supported by :</span>
            <!-- Ganti nama file sesuai dengan logo yang Anda simpan di public/images/ -->
            <img src="{{ asset('images/logo-wuri.webp') }}" alt="LLDIKTI WILAYAH 3" class="h-8 object-contain">
        </div>

        <!-- Kotak Form Login -->
        <div class="mt-20 w-full max-w-sm rounded-xl border border-gray-200 bg-white p-8 shadow-md lg:translate-x-10">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Input Username -->
                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Username</label>
                    <input type="text" name="username" placeholder="Username" required 
                        class="w-full border border-gray-400 bg-transparent rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                </div>

                <!-- Input Password -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" placeholder="Masukan Password" required
                            class="w-full border border-gray-400 bg-transparent rounded-lg px-4 py-3 pr-12 text-sm text-gray-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                        <button id="password-toggle" type="button" class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-gray-500 transition hover:text-blue-600 focus:outline-none" aria-label="Tampilkan password" aria-pressed="false">
                            <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        Username atau password salah.
                    </div>
                @endif

                <!-- Tombol Masuk -->
                <button type="submit"
                    class="{{ $errors->any() ? 'mt-5' : 'mt-8' }} h-[47px] w-full rounded-lg bg-[#1d4ed8] text-sm font-semibold text-white transition hover:bg-blue-800">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <!-- Sisi Kanan: Background Gambar Kota -->
    <!-- Ganti 'bg-login.png' dengan nama file gambar kota Anda yang ada di public/images/ -->
    <div class="relative hidden bg-[#a5d8dd] lg:block lg:w-[68%]">
        <div class="absolute inset-0 bg-cover bg-bottom bg-no-repeat" 
             style="background-image: url('{{ asset('images/bg-login-dikti.webp') }}');">
        </div>
    </div>

</body>
<script>
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');
    passwordToggle?.addEventListener('click', () => {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        passwordToggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        passwordToggle.setAttribute('aria-pressed', String(isHidden));
        passwordToggle.querySelector('i').className = isHidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    });
</script>
</html>