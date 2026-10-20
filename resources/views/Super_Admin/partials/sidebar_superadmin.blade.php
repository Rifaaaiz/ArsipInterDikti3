<aside id="superadmin-sidebar" class="fixed inset-y-0 left-0 z-30 flex w-64 -translate-x-full flex-col bg-[#0a1120] text-white transition-transform duration-200 md:translate-x-0">
    <div class="min-h-0 flex-1 overflow-y-auto">
        <div class="mb-2 flex items-center gap-3 px-6 py-6">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                <i class="fa-solid fa-book-open text-sm text-blue-600"></i>
            </div>
            <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
        </div>
        <nav class="space-y-1 px-4 pb-4">
            <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-gray-200 transition hover:bg-gray-800 hover:text-white">
                <i class="fa-solid fa-house w-5"></i>Dashboard
            </a>
            <div class="pt-1">
                <button type="button" data-sidebar-archive-toggle aria-expanded="false" class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-medium text-gray-200 transition hover:bg-gray-800 hover:text-white">
                    <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i>Kelola Arsip</span>
                    <i class="fa-solid fa-chevron-right text-xs transition-transform" data-sidebar-archive-icon></i>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-300" data-sidebar-archive-submenu>
                    <div class="flex flex-col gap-1 py-1">
                        <a href="{{ route('superadmin.arsip.penghapusan') }}" class="py-2 pl-12 text-sm font-medium text-gray-400 transition hover:text-white">Penghapusan Arsip</a>
                        <a href="{{ route('superadmin.arsip.laporan') }}" class="py-2 pl-12 text-sm font-medium text-gray-400 transition hover:text-white">Laporan Arsip</a>
                    </div>
                </div>
            </div>
            <a href="{{ route('superadmin.peminjaman.validasi') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                <i class="fa-regular fa-circle-check w-5"></i>Validasi Peminjaman
            </a>
            <a href="{{ route('superadmin.users') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                <i class="fa-solid fa-users w-5"></i>Kelola User
            </a>
            <p class="px-4 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-gray-500">Tim Pokja</p>
            @foreach ($pokjas as $menuPokja)
                @php
                    $isCurrentPokja = (int) $activePokja->id === (int) $menuPokja->id;
                @endphp
                <div>
                    <button type="button" data-pokja-menu-toggle="{{ $menuPokja->id }}" aria-expanded="{{ $isCurrentPokja ? 'true' : 'false' }}" class="flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-left text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                        <span class="flex min-w-0 items-center gap-3"><i class="fa-solid fa-people-group w-5 shrink-0"></i><span class="truncate">{{ $menuPokja->name }}</span></span>
                        <i class="fa-solid fa-chevron-right text-[10px] transition-transform {{ $isCurrentPokja ? 'rotate-90' : '' }}"></i>
                    </button>
                    <div data-pokja-submenu="{{ $menuPokja->id }}" class="{{ $isCurrentPokja ? 'max-h-40' : 'max-h-0' }} overflow-hidden transition-all duration-300">
                        <div class="flex flex-col gap-1 py-1">
                            <a href="{{ $menuPokja->slug === 'anti-dosa-pendidikan-dan-integritas-akademik' ? route('superadmin.surat-masuk.adia') : route('superadmin.surat-masuk', $menuPokja->slug) }}" class="block rounded-lg py-1.5 pl-12 text-xs font-medium transition hover:text-white {{ $isCurrentPokja && $activeMenu === 'surat-masuk' ? 'text-blue-300' : 'text-gray-400' }}">Surat Masuk</a>
                            <a href="{{ route('superadmin.surat-keluar', $menuPokja->slug) }}" class="block py-1.5 pl-12 text-xs font-medium transition hover:text-white {{ $isCurrentPokja && $activeMenu === 'surat-keluar' ? 'text-blue-300' : 'text-gray-400' }}">Surat Keluar</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <a href="#" class="mt-3 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                <i class="fa-regular fa-file-lines w-5"></i>Panduan Penggunaan
            </a>
            <a href="#" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                <i class="fa-solid fa-gear w-5"></i>Pengaturan Sistem
            </a>
        </nav>
    </div>
    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-800 p-4">
        @csrf
        <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-left text-sm font-semibold text-red-500 transition hover:bg-red-950 hover:text-red-400">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>Logout
        </button>
    </form>
</aside>
<div id="sidebar-backdrop" class="fixed inset-0 z-20 hidden bg-black/50 md:hidden"></div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('superadmin-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggle = document.querySelector('[data-sidebar-toggle]');

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            toggle?.setAttribute('aria-expanded', 'false');
        };

        toggle?.addEventListener('click', () => {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            sidebar.classList.toggle('-translate-x-full', !isClosed);
            backdrop.classList.toggle('hidden', !isClosed);
            toggle.setAttribute('aria-expanded', String(isClosed));
        });
        backdrop.addEventListener('click', closeSidebar);

        document.querySelector('[data-sidebar-archive-toggle]')?.addEventListener('click', (event) => {
            const button = event.currentTarget;
            const submenu = document.querySelector('[data-sidebar-archive-submenu]');
            const icon = document.querySelector('[data-sidebar-archive-icon]');
            const expanded = button.getAttribute('aria-expanded') === 'true';
            submenu.classList.toggle('max-h-0', expanded);
            submenu.classList.toggle('max-h-40', !expanded);
            icon.classList.toggle('rotate-90', !expanded);
            button.setAttribute('aria-expanded', String(!expanded));
        });

        document.querySelectorAll('[data-pokja-menu-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const id = button.dataset.pokjaMenuToggle;
                const submenu = document.querySelector(`[data-pokja-submenu="${id}"]`);
                const icon = button.querySelector('i.fa-chevron-right');
                const expanded = button.getAttribute('aria-expanded') === 'true';
                submenu.classList.toggle('max-h-0', expanded);
                submenu.classList.toggle('max-h-40', !expanded);
                icon.classList.toggle('rotate-90', !expanded);
                button.setAttribute('aria-expanded', String(!expanded));
            });
        });
    });
</script>
