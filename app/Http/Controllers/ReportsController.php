<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleProduct;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportsController extends Controller
{
    public function sellers(Request $request)
	{
		Gate::authorize('admin');

		if ($request->ajax()) {
			$data = Sale::select(
				'users.name as seller',
				DB::raw('COUNT(sales.id) as sales_count'),
				DB::raw('SUM(sales.total) as total'),
				DB::raw('SUM(sale_products.quantity) as quantity')
			)
				->join('users', 'users.id', '=', 'sales.user_id')
				->join('sale_products', 'sale_products.sale_id', '=', 'sales.id')
				->groupBy('users.name');

			return DataTables::of($data)
				->make(true);
		}

		$title = __('Relatório de Vendedores');
		return view('reports.sellers', compact('title'));
	}

	public function products(Request $request)
	{
		if ($request->ajax()) {
			$data = SaleProduct::select(
				'products.name as product',
				DB::raw('SUM(sale_products.quantity) as quantity'),
				DB::raw('SUM(sale_products.quantity * products.cost) as expense'),
				DB::raw('SUM(sale_products.quantity * products.price) as sold')
			)
				->join('products', 'products.id', '=', 'sale_products.product_id')
				->join('sales', 'sales.id', '=', 'sale_products.sale_id')
				->groupBy('product');

			return DataTables::of($data)
				->addColumn('profit', function ($row) {
					return $row->sold - $row->expense;
				})
				->make(true);
		}

		$title = __('Relatório de Produtos');
		return view('reports.products', compact('title'));
	}
}
