<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $totalRevenue = Order::sum('total_amount');
        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'shipped'])->count();
        $pendingShipments = Order::where('status', 'shipped')->count();
        $totalCustomers = User::whereNotIn('role', ['admin', 'super-admin'])->count();
        $recentOrders = Order::with('user')->latest()->limit(6)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'activeOrders',
            'pendingShipments',
            'totalCustomers',
            'recentOrders'
        ));
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
