<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Models\Pokja;
use App\Models\User;
use App\Models\KategoriArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperadminDashboardController extends Controller
{
    public function users(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $roleId = $request->integer('role_id') ?: null;
        $pokjaId = $request->integer('pokja_id') ?: null;
        $deleteMode = filter_var($request->query('delete_mode', false), FILTER_VALIDATE_BOOLEAN);

        $users = User::with(['role', 'pokja'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('username', 'like', '%' . $search . '%');
                });
            })
            ->when($roleId, fn ($query) => $query->where('role_id', $roleId))
            ->when($pokjaId, fn ($query) => $query->where('pokja_id', $pokjaId))
            ->latest('created_at')
            ->paginate(10);
        $users->appends($request->query());

        return view('Super_Admin.kelola_user_superadmin', [
            'users' => $users,
            'roles' => \App\Models\Role::orderBy('name')->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
            'filters' => compact('search', 'roleId', 'pokjaId'),
            'deleteMode' => $deleteMode,
        ]);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'pokja_id' => ['nullable', 'exists:pokjas,id'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ], [
            'username.unique' => 'Username sudah digunakan.',
        ]);

        $role = \App\Models\Role::findOrFail($validated['role_id']);
        $pokjaId = in_array($role->slug, ['admin_pokja', 'ketua_pokja'], true)
            ? $validated['pokja_id']
            : null;

        if (in_array($role->slug, ['admin_pokja', 'ketua_pokja'], true) && ! $pokjaId) {
            return back()->withInput()->withErrors(['pokja_id' => 'Pokja wajib dipilih untuk role ini.']);
        }

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['username'] . '@simarsip.local',
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'pokja_id' => $pokjaId,
            'status' => $validated['status'],
        ]);

        return redirect()->route('superadmin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function deleteUser(User $user)
    {
        abort_unless(auth()->user()?->role?->slug === 'superadmin', 403);

        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun yang sedang digunakan.']);
        }

        if ($user->peminjamans()->exists() || $user->arsips()->exists() || $user->validations()->exists()) {
            return back()->withErrors(['user' => 'User masih memiliki data terkait dan tidak dapat dihapus.']);
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function activeLoans()
    {
        $peminjamans = Peminjaman::with(['user.role', 'user.pokja', 'kategori', 'arsip'])
            ->activeNow()
            ->latest('tanggal_mulai')
            ->paginate(10);

        return view('Super_Admin.peminjaman_aktif_superadmin', compact('peminjamans'));
    }

    public function archiveReport(Request $request)
    {
        $filters = $this->archiveFilters($request);
        $arsips = $this->archiveQuery($request)->paginate(10)->withQueryString();

        return view('Super_Admin.laporan_arsip_superadmin', [
            'arsips' => $arsips,
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
            ...$this->archiveStatistics(),
            'filters' => $filters,
        ]);
    }

    public function deletionArchive(Request $request)
    {
        $request->merge(['sumber' => $request->query('sumber', 'semua')]);

        return view('Super_Admin.penghapusan_arsip_superadmin', [
            'arsips' => $this->archiveQuery($request)->paginate(10)->withQueryString(),
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
            ...$this->archiveStatistics(),
            'filters' => $this->archiveFilters($request),
        ]);
    }

    public function deleteArchive(Arsip $arsip)
    {
        $filePath = $arsip->file_path;

        try {
            DB::transaction(fn () => $arsip->delete());

            if ($filePath && Storage::disk('public')->exists($filePath) && ! Storage::disk('public')->delete($filePath)) {
                DB::transaction(fn () => $arsip->restore());

                return back()->withErrors(['arsip' => 'File arsip tidak dapat dihapus dari storage.']);
            }
        } catch (\Throwable $exception) {
            if ($arsip->trashed()) {
                DB::transaction(fn () => $arsip->restore());
            }

            throw $exception;
        }

        return back()->with('success', 'Arsip berhasil dihapus.');
    }

    private function archiveQuery(Request $request)
    {
        $filters = $this->archiveFilters($request);

        return Arsip::with(['kategori', 'pokja', 'uploader.role'])
            ->whereNotIn('status', ['dihapus'])
            ->when($filters['search'] !== '', fn ($builder) => $builder->where('nama_arsip', 'like', '%' . $filters['search'] . '%'))
            ->when($filters['tanggal'], fn ($builder) => $builder->whereDate('created_at', $filters['tanggal']))
            ->when($filters['kategoriId'], fn ($builder) => $builder->where('kategori_arsip_id', $filters['kategoriId']))
            ->when($filters['pokjaId'], fn ($builder) => $builder->where('pokja_id', $filters['pokjaId']))
            ->when($filters['source'] === 'kelompok_kerja', fn ($builder) => $builder->whereNotNull('pokja_id'))
            ->when($filters['source'] === 'tu', function ($builder) {
                $builder->whereNull('pokja_id')->whereHas('uploader.role', fn ($role) => $role->where('slug', 'admin_tu'));
            })
            ->latest('created_at');
    }

    private function archiveFilters(Request $request): array
    {
        $source = $request->query('sumber', 'semua');

        return [
            'search' => trim((string) $request->query('search', '')),
            'tanggal' => $request->query('tanggal'),
            'kategoriId' => $request->integer('kategori_id') ?: null,
            'pokjaId' => $request->integer('pokja_id') ?: null,
            'source' => in_array($source, ['semua', 'kelompok_kerja', 'tu'], true) ? $source : 'semua',
        ];
    }

    private function archiveStatistics(): array
    {
        $activeArchives = Arsip::whereNotIn('status', ['dihapus']);

        return [
            'totalArsip' => (clone $activeArchives)->count(),
            'totalArsipPokja' => (clone $activeArchives)->whereNotNull('pokja_id')->count(),
            'totalArsipTu' => (clone $activeArchives)
                ->whereNull('pokja_id')
                ->whereHas('uploader.role', fn ($role) => $role->where('slug', 'admin_tu'))
                ->count(),
        ];
    }

    public function dashboard()
    {
        $user = auth()->user();
        $totalArsip = Arsip::whereNotIn('status', ['dihapus'])->count();

        // Reuse scope yang sudah ada di Model Peminjaman (sama seperti
        // yang dipakai PeminjamanApprovalController::tuDashboard()),
        // supaya definisi "aktif" konsisten di seluruh dashboard.
        $totalPeminjamanAktif = Peminjaman::activeNow()->count();

        $totalUserAktif = User::where('status', 'active')
            ->whereHas('role', function ($query) {
                $query->whereIn('slug', ['admin_pokja', 'ketua_pokja', 'admin_tu']);
            })
            ->count();

        $totalPokja = Pokja::count();

        $arsipTerbaru = Arsip::with(['kategori', 'pokja'])
            ->whereNotIn('status', ['dihapus'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $peminjamanTerbaru = Peminjaman::with(['user.pokja', 'kategori', 'arsip'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('Super_Admin.dashboard_superadmin', compact(
            'user',
            'totalArsip',
            'totalPeminjamanAktif',
            'totalUserAktif',
            'totalPokja',
            'arsipTerbaru',
            'peminjamanTerbaru'
        ));
    }
}