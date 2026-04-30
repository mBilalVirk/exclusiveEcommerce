<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
   // ✅ Declare the property
    protected $AdminService;

    // ✅ Inject the service through the constructor
    public function __construct(AdminService $AdminService)
    {
        $this->AdminService = $AdminService;
    }
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $startDate = now()->subDays(30);

        $data = array_merge(
            $this->AdminService->getDashboardData($startDate),
            $this->AdminService->getDashboardSummary()
        );

        return view('admin.dashboard', $data);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function products()
    {
        return view('admin.products');
    }
}
