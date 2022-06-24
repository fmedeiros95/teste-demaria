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
		// Get total sales amount
		$totalSalesAmount = Sale::sum('total');

		// Set page title
		$title = __('Dashboard');

        return view('dashboard.index', compact('title', 'totalCustomers', 'totalSales', 'totalProducts', 'totalSalesAmount'));
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
