<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminOrderController extends Controller
{
    /**
     * Display the admin dashboard with summary statistics.
     */
    public function dashboard()
    {
        $totalOrders = Order::count();
        $ordersAwaiting = Order::where('status', 'Diterima')->count();
        $completedOrdersCount = Order::where('status', 'Selesai')->count();
        $totalRevenue = Order::where('status', 'Selesai')->sum('total_price');

        // Calculate efficiency percentage, handling division by zero
        $efficiency = $totalOrders > 0 
            ? round(($completedOrdersCount / $totalOrders) * 100) 
            : 0;

        $latestOrders = Order::with(['details.menu', 'user'])
            ->where('status', '!=', 'Selesai')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders', 
            'ordersAwaiting', 
            'totalRevenue', 
            'efficiency', 
            'latestOrders'
        ));
    }

    /**
     * Display a paginated list of all orders.
     */
    public function index()
    {
        $orders = Order::with(['details.menu', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Manage customer data with search functionality.
     */
    public function customersIndex(Request $request)
    {
        $search = $request->query('search');

        $customers = User::where('role', 'user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers', compact('customers'));
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroyCustomer(User $user)
    {
        // Prevent admins from deleting their own account accidentally
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return to_route('admin.customers.index')->with('success', 'Customer account deleted successfully.');
    }

    /**
     * Display sales reports filtered by period.
     */
    public function reportsIndex(Request $request)
    {
        $period = $request->query('period', '7_days');
        $search = $request->query('search');
        
        // Retrieve date range based on selected period
        $dateRange = $this->getDateRange($period);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        $titleChart = $dateRange['title'];

        // Base query for completed orders within the date range
        $query = Order::with(['details.menu', 'user'])
            ->where('status', 'Selesai')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Apply search filter if present
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('nomor_meja', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $completedOrders = $query->latest()->get();
        $totalRevenue = $completedOrders->sum('total_price');

        // Generate data for the chart visualization
        $chartData = $this->generateChartData($startDate, $endDate, $period);

        return view('admin.reports', array_merge([
            'completedOrders' => $completedOrders,
            'totalRevenue' => $totalRevenue,
            'currentPeriod' => $period,
            'titleChart' => $titleChart
        ], $chartData));
    }

    /**
     * Generate a printable report.
     */
    public function printReport(Request $request)
    {
        $period = $request->query('period', '7_days');
        $dateRange = $this->getDateRange($period);

        $completedOrders = Order::with(['details.menu', 'user'])
            ->where('status', 'Selesai')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->latest()
            ->get();

        return view('admin.reports-print', [
            'completedOrders' => $completedOrders,
            'totalRevenue' => $completedOrders->sum('total_price'),
            'titlePeriod' => $dateRange['title']
        ]);
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Diterima,Sedang Dimasak,Selesai,Dibatalkan'
        ]);

        $order->update(['status' => $validated['status']]);

        return to_route('admin.orders.index')->with('success', 'Order status updated successfully!');
    }

    /**
     * Print order receipt or kitchen ticket.
     */
    public function printOrder(Request $request, Order $order, $type)
    {
        $order->load(['details.menu', 'user']);

        $data = [
            'order' => $order,
            'type' => $type,
            'bayar' => $request->query('bayar', 0),
            'kembali' => $request->query('kembali', 0)
        ];

        if (in_array($type, ['dapur', 'kasir', 'struk'])) {
            return view('admin.orders.print', $data);
        }

        abort(404);
    }
    
    // Legacy alias for printOrder
    public function printStruk(Request $request, Order $order, $type)
    {
        return $this->printOrder($request, $order, $type);
    }

    /**
     * Display user feedback.
     */
    public function feedbackIndex()
    {
        $feedbacks = Feedback::latest()->paginate(10);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function feedbackDestroy(Feedback $feedback)
    {
        $feedback->delete();
        return back()->with('success', 'Feedback deleted successfully.');
    }

    /**
     * Show the change password form.
     */
    public function showChangePasswordForm()
    {
        return view('admin.password');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Show QR Code generator page.
     */
    public function qrCodeIndex()
    {
        return view('admin.qrcode.index');
    }

    public function qrCodePrint(Request $request)
    {
        $request->validate(['nomor_meja' => 'required|integer|min:1']);

        $url = route('menu.indexPage', ['meja' => $request->nomor_meja]);
        $qrcode = QrCode::size(300)->margin(2)->generate($url);

        return view('admin.qrcode.print', [
            'qrcode' => $qrcode,
            'nomor_meja' => $request->nomor_meja,
            'url' => $url
        ]);
    }

    /* |--------------------------------------------------------------------------
    | Private Helper Functions
    |--------------------------------------------------------------------------
    */

    private function getDateRange($period)
    {
        $endDate = Carbon::now()->endOfDay();
        
        return match($period) {
            '30_days' => [
                'start' => Carbon::now()->subDays(29)->startOfDay(),
                'end' => $endDate,
                'title' => 'Last 30 Days'
            ],
            'this_month' => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => $endDate,
                'title' => 'This Month (' . Carbon::now()->format('F Y') . ')'
            ],
            'all' => [
                'start' => Carbon::create(2000, 1, 1),
                'end' => $endDate,
                'title' => 'All Time'
            ],
            default => [ // 7 days
                'start' => Carbon::now()->subDays(6)->startOfDay(),
                'end' => $endDate,
                'title' => 'Last 7 Days'
            ]
        };
    }

    private function generateChartData($startDate, $endDate, $period)
    {
        $revenueData = Order::where('status', 'Selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartValues = [];
        $daysDiff = $startDate->diffInDays($endDate);

        for ($i = 0; $i <= $daysDiff; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateKey = $date->format('Y-m-d');
            
            $label = ($period == '30_days' || $period == 'this_month') 
                ? $date->format('d M') 
                : $date->format('D, d M');

            $chartLabels[] = $label;
            $chartValues[] = $revenueData[$dateKey] ?? 0;
        }

        return compact('chartLabels', 'chartValues');
    }
}