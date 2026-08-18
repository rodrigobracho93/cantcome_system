<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\User;
use App\Models\Setting;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function reports()
    {
        $dailySales = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total) as total')
        )
            ->where('status', 'completado')
            ->groupBy('date')
            ->orderByDesc('date')
            ->limit(30)
            ->get();

        $monthlyRevenue = Sale::where('status', 'completado')
            ->where('created_at', '>=', now()->subMonth())
            ->sum('total');

        $totalCustomers = Customer::count();
        $totalSales = Sale::where('status', 'completado')->count();
        $totalRevenue = Sale::where('status', 'completado')->sum('total');

        return view('admin.reports', compact('dailySales', 'monthlyRevenue', 'totalCustomers', 'totalSales', 'totalRevenue'));
    }

    private function themeColor(?string $theme): string
    {
        return match ($theme) {
            'blue' => '#2563eb',
            'green' => '#16a34a',
            'red' => '#dc2626',
            'purple' => '#9333ea',
            'orange' => '#ea580c',
            'teal' => '#0d9488',
            'pink' => '#db2777',
            'neutro' => '#64748b',
            'celeste' => '#0284c7',
            default => '#4f46e5',
        };
    }

    public function reportsPdf(Request $request)
    {
        $primaryColor = $this->themeColor($request->input('theme'));

        $dailySales = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total) as total')
        )
            ->where('status', 'completado')
            ->groupBy('date')
            ->orderByDesc('date')
            ->limit(30)
            ->get();

        $monthlyRevenue = Sale::where('status', 'completado')
            ->where('created_at', '>=', now()->subMonth())
            ->sum('total');

        $totalCustomers = Customer::count();
        $totalSales = Sale::where('status', 'completado')->count();
        $totalRevenue = Sale::where('status', 'completado')->sum('total');

        $pdf = Pdf::loadView('admin.reports-pdf', compact(
            'dailySales', 'monthlyRevenue', 'totalCustomers', 'totalSales', 'totalRevenue', 'primaryColor'
        ));

        return $pdf->stream('reportes.pdf');
    }

    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories')->with('success', 'Categoría creada exitosamente.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories')->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->withErrors('No se puede eliminar una categoría con productos asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Categoría eliminada exitosamente.');
    }

    public function users(Request $request)
    {
        $query = User::orderBy('name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('cedula', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.users', compact('users'));
    }

    public function storeUser(StoreUserRequest $request)
    {
        $validated = $request->validated();

        if ($validated['role'] === 'superadmin' && !auth()->user()->isSuperAdmin()) {
            abort(403, 'No puedes crear un superadmin.');
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cedula' => $validated['cedula'] ?? null,
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ];

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    public function updateUser(UpdateUserRequest $request, User $user)
    {
        if ($user->role === 'superadmin' && !auth()->user()->isSuperAdmin()) {
            abort(403, 'No puedes modificar un superadmin.');
        }

        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cedula' => $validated['cedula'] ?? null,
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = bcrypt($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $user->uploadProfilePhoto($request->file('profile_photo'));
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes desactivarte a ti mismo.');
        }

        if ($user->role === 'superadmin' && !auth()->user()->isSuperAdmin()) {
            abort(403, 'No puedes desactivar un superadmin.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activado' : 'desactivado';
        return redirect()->route('admin.users')->with('success', "Usuario {$status} exitosamente.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes eliminarte a ti mismo.');
        }

        if ($user->role === 'superadmin' && !auth()->user()->isSuperAdmin()) {
            abort(403, 'No puedes eliminar un superadmin.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuario eliminado permanentemente.');
    }

    public function settings()
    {
        $settings = Setting::getMany([
            'timezone' => 'America/Asuncion',
            'date_format' => 'd/m/Y',
            'time_format' => '24h',
            'system_name' => 'CantCome',
            'system_logo' => 'logo.png',
        ]);

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'timezone' => 'required|string|max:60',
            'date_format' => 'required|in:d/m/Y,m/d/Y,Y-m-d,d-m-Y,d.m.Y',
            'time_format' => 'required|in:24h,12h',
        ]);

        Setting::set('timezone', $validated['timezone']);
        Setting::set('date_format', $validated['date_format']);
        Setting::set('time_format', $validated['time_format']);

        config(['app.timezone' => $validated['timezone']]);
        date_default_timezone_set($validated['timezone']);

        return redirect()->route('admin.settings')->with('success', 'Configuración guardada exitosamente.');
    }

    public function updateBranding(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required|string|max:100',
            'system_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        Setting::set('system_name', $validated['system_name']);

        if ($request->hasFile('system_logo')) {
            $file = $request->file('system_logo');
            $ext = $file->getClientOriginalExtension();
            $filename = 'logo.' . $ext;

            $file->move(public_path(), $filename);
            Setting::set('system_logo', $filename);

            $this->generatePwaIcons(public_path($filename));
        }

        return redirect()->route('admin.settings')->with('success', 'Branding actualizado exitosamente.');
    }

    private function generatePwaIcons(string $sourcePath): void
    {
        if (!file_exists($sourcePath)) return;

        $sizes = [192, 512];
        $info = getimagesize($sourcePath);
        if (!$info) return;

        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $src = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $src = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $src = imagecreatefromwebp($sourcePath);
                break;
            default:
                return;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        foreach ($sizes as $size) {
            $dst = imagecreatetruecolor($size, $size);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefilledrectangle($dst, 0, 0, $size, $size, $transparent);

            $ratio = $size / max($origW, $origH);
            $newW = (int)($origW * $ratio);
            $newH = (int)($origH * $ratio);
            $offsetX = (int)(($size - $newW) / 2);
            $offsetY = (int)(($size - $newH) / 2);

            imagecopyresampled($dst, $src, $offsetX, $offsetY, 0, 0, $newW, $newH, $origW, $origH);
            imagepng($dst, public_path("icon-{$size}.png"));
            imagedestroy($dst);
        }

        imagedestroy($src);
    }

    public function resetBranding()
    {
        $defaultLogo = 'logo-default.png';
        $currentLogo = Setting::get('system_logo', 'logo.png');

        if ($currentLogo !== 'logo.png' && file_exists(public_path($currentLogo))) {
            @unlink(public_path($currentLogo));
        }

        copy(public_path($defaultLogo), public_path('logo.png'));

        $this->generatePwaIcons(public_path('logo.png'));

        Setting::set('system_name', 'CantCome');
        Setting::set('system_logo', 'logo.png');

        return redirect()->route('admin.settings')->with('success', 'Branding restablecido a los valores por defecto.');
    }
}
