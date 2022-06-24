<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Customer;
use App\Models\Log;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		// Get total customers
		$totalCustomers = Customer::count();
		// Get total sales
		$totalSales = Sale::count();
		// Get total products
		$totalProducts = Product::count();

		// Get today sales
		$todaySales = Sale::whereDate('created_at', today())->where('status', 'paid')->sum('total');

		// Get total sales amount
		$totalSalesAmountPaid = Sale::where('status', 'paid')->sum('total');
		$totalSalesAmountPending = Sale::where('status', 'pending')->sum('total');

		// Set page title
		$title = __('Dashboard');

        return view('dashboard.index', compact('title', 'totalCustomers', 'totalSales', 'totalProducts', 'todaySales', 'totalSalesAmountPaid', 'totalSalesAmountPending'));
    }

	public function logs(Request $request)
    {
        if ($request->ajax()) {
			// Get logs
			$data = Log::latest()->get();

			return DataTables::of($data)
				->make(true);
		}
	}
}
