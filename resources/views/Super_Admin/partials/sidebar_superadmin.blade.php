@php
    $sidebarRoute = request()->route()?->getName() ?? '';
    $sidebarPokjas = collect($pokjas ?? [])->sortBy(fn ($pokja) => match ($pokja->slug) {
        'anti-dosa-pendidikan-dan-integritas-akademik' => 0,
        'penjaminan-mutu' => 1,
        default => 2,
    })->values();
    $sidebarPokjaSlug = request()->route('pokjaSlug');

    if ($sidebarRoute === 'superadmin.surat-masuk.adia') {
        $sidebarPokjaSlug = 'anti-dosa-pendidikan-dan-integritas-akademik';
    }

    $sidebarActiveMenu = str_starts_with($sidebarRoute, 'superadmin.surat-masuk')
        ? 'surat-masuk'
        : (str_starts_with($sidebarRoute, 'superadmin.surat-keluar') ? 'surat-keluar' : null);
    $sidebarArchiveOpen = str_starts_with($sidebarRoute, 'superadmin.arsip.');
    $sidebarVitalOpen = str_starts_with($sidebarRoute, 'superadmin.arsip-vital.');
@endphp
<aside id="superadmin-sidebar" class="fixed inset-y-0 left-0 z-30 flex w-64 -translate-x-full flex-col bg-[#0a1120] text-white transition-transform duration-200 md:translate-x-0">
    <div class="min-h-0 flex-1 overflow-y-auto">
        <div id="sidebar-logo" class="mb-3 flex items-center gap-3 px-6 py-6">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                <i class="fa-solid fa-book-open text-sm text-blue-600"></i>
            </div>
            <h1 data-sidebar-label class="text-xl font-bold tracking-wider text-white">SIMARSIP</h1>
        </div>
        <nav class="space-y-1 px-4 pb-4">
            <a href="{{ route('superadmin.dashboard') }}" @if ($sidebarRoute === 'superadmin.dashboard') aria-current="page" @endif class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition {{ $sidebarRoute === 'superadmin.dashboard' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                <i class="fa-solid fa-house w-5" aria-hidden="true"></i><span data-sidebar-label>Dashboard</span>
            </a>

            <div class="pt-1">
                <button type="button" data-sidebar-archive-toggle aria-expanded="{{ $sidebarArchiveOpen ? 'true' : 'false' }}" class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold transition {{ $sidebarArchiveOpen ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                    <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5" aria-hidden="true"></i><span data-sidebar-label>Kelola Arsip</span></span>
                    <i class="fa-solid fa-chevron-right text-xs transition-transform duration-300 {{ $sidebarArchiveOpen ? 'rotate-90' : '' }}" data-sidebar-archive-icon aria-hidden="true"></i>
                </button>
                <div class="overflow-hidden transition-all duration-300 {{ $sidebarArchiveOpen ? 'max-h-40' : 'max-h-0' }}" data-sidebar-archive-submenu>
                    <div class="flex flex-col gap-1 py-1">
                        <a href="{{ route('superadmin.arsip.penghapusan') }}" @if ($sidebarRoute === 'superadmin.arsip.penghapusan') aria-current="page" @endif class="mx-2 block rounded-lg px-4 py-2.5 pl-8 text-sm font-medium transition {{ $sidebarRoute === 'superadmin.arsip.penghapusan' ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">Penghapusan Arsip</a>
                        <a href="{{ route('superadmin.arsip.laporan') }}" @if ($sidebarRoute === 'superadmin.arsip.laporan' || $sidebarRoute === 'superadmin.arsip.edit') aria-current="page" @endif class="mx-2 block rounded-lg px-4 py-2.5 pl-8 text-sm font-medium transition {{ $sidebarRoute === 'superadmin.arsip.laporan' || $sidebarRoute === 'superadmin.arsip.edit' ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">Laporan Arsip</a>
                    </div>
                </div>
            </div>

            <a href="{{ route('superadmin.peminjaman.validasi') }}" @if ($sidebarRoute === 'superadmin.peminjaman.validasi' || $sidebarRoute === 'superadmin.peminjaman.decision') aria-current="page" @endif class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition {{ str_starts_with($sidebarRoute, 'superadmin.peminjaman.validasi') ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <i class="fa-regular fa-circle-check w-5" aria-hidden="true"></i><span data-sidebar-label>Validasi Peminjaman</span>
            </a>

            <a href="{{ route('superadmin.users') }}" @if (str_starts_with($sidebarRoute, 'superadmin.users')) aria-current="page" @endif class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition {{ str_starts_with($sidebarRoute, 'superadmin.users') ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <i class="fa-solid fa-users w-5" aria-hidden="true"></i><span data-sidebar-label>Kelola User</span>
            </a>

            <div class="pt-1">
                <button type="button" data-sidebar-vital-toggle aria-expanded="{{ $sidebarVitalOpen ? 'true' : 'false' }}" class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-semibold transition {{ $sidebarVitalOpen ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                    <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5" aria-hidden="true"></i><span data-sidebar-label>Arsip Vital</span></span>
                    <i class="fa-solid fa-chevron-right text-xs transition-transform duration-300 {{ $sidebarVitalOpen ? 'rotate-90' : '' }}" data-sidebar-vital-icon aria-hidden="true"></i>
                </button>
                <div class="overflow-hidden transition-all duration-300 {{ $sidebarVitalOpen ? 'max-h-40' : 'max-h-0' }}" data-sidebar-vital-submenu>
                    <div class="flex flex-col gap-1 py-1">
                        <a href="{{ route('superadmin.arsip-vital.aktif') }}" @if ($sidebarRoute === 'superadmin.arsip-vital.aktif') aria-current="page" @endif class="mx-2 block rounded-lg px-4 py-2.5 pl-8 text-sm font-medium transition {{ $sidebarRoute === 'superadmin.arsip-vital.aktif' ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">Aktif</a>
                        <a href="{{ route('superadmin.arsip-vital.inaktif') }}" @if ($sidebarRoute === 'superadmin.arsip-vital.inaktif') aria-current="page" @endif class="mx-2 block rounded-lg px-4 py-2.5 pl-8 text-sm font-medium transition {{ $sidebarRoute === 'superadmin.arsip-vital.inaktif' ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">Inaktif</a>
                    </div>
                </div>
            </div>

            <p data-sidebar-heading class="px-4 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-gray-400">Tim Pokja</p>
            @foreach ($sidebarPokjas as $menuPokja)
                @php
                    $isCurrentPokja = $sidebarPokjaSlug !== null && $sidebarPokjaSlug === $menuPokja->slug;
                    $pokjaMenuOpen = $isCurrentPokja && $sidebarActiveMenu !== null;
                    $suratMasukUrl = $menuPokja->slug === 'anti-dosa-pendidikan-dan-integritas-akademik'
                        ? route('superadmin.surat-masuk.adia')
                        : route('superadmin.surat-masuk', $menuPokja->slug);
                    $menuPokjaTitle = match ($menuPokja->slug) {
                        'anti-dosa-pendidikan-dan-integritas-akademik' => 'Tim ADIA',
                        'penjaminan-mutu' => 'Tim Penjamin Mutu',
                        default => $menuPokja->name,
                    };
                @endphp
                <div>
                    <button type="button" data-pokja-menu-toggle="{{ $menuPokja->id }}" aria-expanded="{{ $pokjaMenuOpen ? 'true' : 'false' }}" class="flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-left text-sm font-medium transition {{ $isCurrentPokja ? 'text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <span class="flex min-w-0 items-center gap-3"><i class="fa-solid fa-people-group w-5 shrink-0" aria-hidden="true"></i><span data-sidebar-label class="truncate">{{ $menuPokjaTitle }}</span></span>
                        <i class="fa-solid fa-chevron-right text-[10px] transition-transform {{ $pokjaMenuOpen ? 'rotate-90' : '' }}" aria-hidden="true"></i>
                    </button>
                    <div data-pokja-submenu="{{ $menuPokja->id }}" class="overflow-hidden transition-all duration-300 {{ $pokjaMenuOpen ? 'max-h-40' : 'max-h-0' }}">
                        <div class="flex flex-col gap-1 py-1">
                            <a href="{{ $suratMasukUrl }}" @if ($isCurrentPokja && $sidebarActiveMenu === 'surat-masuk') aria-current="page" @endif class="mx-2 block rounded-lg py-1.5 pl-10 text-xs font-medium transition {{ $isCurrentPokja && $sidebarActiveMenu === 'surat-masuk' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">Surat Masuk</a>
                            <a href="{{ route('superadmin.surat-keluar', $menuPokja->slug) }}" @if ($isCurrentPokja && $sidebarActiveMenu === 'surat-keluar') aria-current="page" @endif class="mx-2 block rounded-lg py-1.5 pl-10 text-xs font-medium transition {{ $isCurrentPokja && $sidebarActiveMenu === 'surat-keluar' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">Surat Keluar</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <a href="#" title="Panduan Penggunaan" class="mt-3 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                <i class="fa-regular fa-file-lines w-5" aria-hidden="true"></i><span data-sidebar-label>Panduan Penggunaan</span>
            </a>
            <a href="#" title="Pengaturan Sistem" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                <i class="fa-solid fa-gear w-5" aria-hidden="true"></i><span data-sidebar-label>Pengaturan Sistem</span>
            </a>
        </nav>
    </div>
    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-800 p-4">
        @csrf
        <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-left text-sm font-semibold text-red-400 transition hover:bg-red-950 hover:text-red-300">
            <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i><span data-sidebar-label>Logout</span>
        </button>
    </form>
    @if (request()->routeIs('superadmin.dashboard', 'superadmin.arsip.*', 'superadmin.users*', 'superadmin.peminjaman.create', 'superadmin.peminjaman.aktif', 'superadmin.peminjaman.validasi'))
        <button id="sidebar-resize-handle" type="button" aria-label="Ubah lebar sidebar" title="Tarik untuk mengubah lebar sidebar" class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize bg-transparent transition hover:bg-blue-500/60"></button>
    @endif
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

        document.querySelector('[data-sidebar-vital-toggle]')?.addEventListener('click', (event) => {
            const button = event.currentTarget;
            const submenu = document.querySelector('[data-sidebar-vital-submenu]');
            const icon = document.querySelector('[data-sidebar-vital-icon]');
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
