<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if ($request->ajax()) {
			$data = Sale::join('users', 'sales.user_id', '=', 'users.id')
				->join('customers', 'sales.customer_id', '=', 'customers.id')
				->select('sales.*', 'users.name as user_name', 'customers.name as customer_name');
			return Datatables::of($data)
				->addIndexColumn()
				->addColumn('action', function($row) {
					$btns = '<div class="button-list">';
						$btns .= '<button type="button" data-id="' . $row->id . '" class="edit btn btn-secondary btn-sm waves-effect waves-light"><i class="fas fa-edit"></i></a>';
						$btns .= '<button type="button" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm waves-effect waves-light"><i class="fas fa-trash"></i></a>';
					$btns .= '</div>';
					return $btns;
				})
				->editColumn('total', function($row) {
					return display_money($row->total);
				})
				->rawColumns(['action'])
				->make(true);
		}

		$title = __('Vendas');
        return view('sales.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$fields = $request->all();

		// Create validator
		$validator = Validator::make($fields, [
			'customer_id' => 'required|exists:customers,id',
			'product.*' => 'required|numeric|exists:products,id',
			'quantity.*' => 'required|numeric|min:1',
			'status' => 'required|in:paid,pending,canceled',
			'payment_method' => 'required|in:cash,credit_card,debit_card,transfer',
		], [
			'customer_id.required' => __('O cliente é obrigatório.'),
			'customer_id.exists' => __('O cliente não existe.'),
			'product.*.required' => __('O produto é obrigatório.'),
			'product.*.exists' => __('O produto não existe.'),
			'quantity.*.required' => __('A quantidade é obrigatória.'),
			'quantity.*.numeric' => __('A quantidade deve ser um número.'),
			'quantity.*.min' => __('A quantidade deve ser maior que zero.'),
			'status.required' => __('O status é obrigatório.'),
			'status.in' => __('O status selecionado não existe'),
			'payment_method.required' => __('O método de pagamento é obrigatório.'),
			'payment_method.in' => __('O método de pagamento selecionado não existe'),
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()->first()
			], 400);
		}

		$items = [];
		$total = 0;
		foreach ($fields['product'] as $product_id) {
			$product = Product::find($product_id);
			if (!$product) continue;

			$quantity = $fields['quantity'][$product_id];
			if ($product->quantity < $quantity) {
				return response()->json([
					'success' => false,
					'message' => __(':Product não tem estoque suficiente.', ['product' => $product->name])
				], 400);
			}

			$total += $product->price * $quantity;
			$items[] = $product;
		}

		$sale = new Sale();
		$sale->customer_id = $fields['customer_id'];
		$sale->user_id = auth()->user()->id;
		$sale->status = $fields['status'];
		$sale->payment_method = $fields['payment_method'];
		$sale->total = $total;
		$sale->save();

		// Create sale products
		foreach ($items as $item) {
			$quantity = $fields['quantity'][$item->id];

			// Reduce the quantity of the product
			$item->quantity -= $quantity;
			$item->save();

			SaleProduct::create([
				'sale_id' => $sale->id,
				'product_id' => $item->id,
				'quantity' => $quantity,
			]);
		}

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' created a new sale #' . $sale->id);

		return response()->json([
			'success' => true,
			'message' => __('Venda criada com sucesso')
		], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::find($id);
		if (!$sale) {
			return response()->json([
				'success' => false,
				'message' => __('Venda não encontrada')
			], 404);
		}

		return response()->json([
			'success' => true,
			'data' => [
				'id' => (int) $sale->id,
				'total' => (float) $sale->total,
				'status' => (string) $sale->status,
				'payment_method' => (string) $sale->payment_method,
				'created_at' => (string) $sale->created_at,
				'updated_at' => (string) $sale->updated_at,
				'customer' => $sale->customer,
				'products' => $sale->products->map(function($item) {
					return [
						'id' => (int) $item->product_id,
						'quantity' => (int) $item->quantity,
						'price' => (float) $item->product->price,
						'name' => (string) $item->product->name,
						'inventory' => $item->quantity + $item->product->quantity
					];
				})
			]
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fields = $request->all();

		// Create validator
		$validator = Validator::make($fields, [
			'customer_id' => 'required|exists:customers,id',
			'product.*' => 'required|numeric|exists:products,id',
			'quantity.*' => 'required|numeric|min:1|max:100',
			'status' => 'required|in:paid,pending,canceled',
			'payment_method' => 'required|in:cash,credit_card,debit_card,transfer',
		], [
			'customer_id.required' => __('O campo cliente é obrigatório'),
			'customer_id.exists' => __('O cliente selecionado não existe'),
			'product.*.required' => __('O campo produto é obrigatório'),
			'product.*.exists' => __('O produto selecionado não existe'),
			'quantity.*.required' => __('O campo quantidade é obrigatório'),
			'quantity.*.min' => __('A quantidade deve ser maior que 0'),
			'quantity.*.max' => __('A quantidade deve ser menor ou igual a 100'),
			'status.required' => __('O campo status é obrigatório'),
			'status.in' => __('O status selecionado não existe'),
			'payment_method.required' => __('O campo método de pagamento é obrigatório'),
			'payment_method.in' => __('O método de pagamento selecionado não existe'),
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()->first()
			], 400);
		}

		$sale = Sale::find($id);
		if (!$sale) {
			return response()->json([
				'success' => false,
				'message' => __('Venda não encontrada')
			], 404);
		}

		$items = [];
		$total = 0;
		foreach ($fields['product'] as $product_id) {
			$product = Product::find($product_id);
			if (!$product) continue;

			// Get old quantity
			$old = $sale->products->where('product_id', $product_id)->first();
			$old_quantity = $old ? $old->quantity : 0;

			// Get new quantity
			$quantity = $fields['quantity'][$product_id];

			// difference between old and new quantity
			$diff = $quantity - $old_quantity;

			// Check if the product has enough quantity
			if ($diff && $product->quantity < $diff) {
				return response()->json([
					'success' => false,
					'message' => __(':Product não tem estoque suficiente.', ['product' => $product->name])
				], 400);
			}

			// Add product to items
			$items[] = $product;

			// Add product price to total
			$total += $product->price * $quantity;
 		}

		$sale->customer_id = $fields['customer_id'];
		$sale->status = $fields['status'];
		$sale->payment_method = $fields['payment_method'];
		$sale->total = $total;
		$sale->save();

		// Create sale products
		foreach ($items as $item) {
			// Get new quantity
			$quantity = $fields['quantity'][$item->id];

			// Get old quantity
			$old = $sale->products->where('product_id', $item->id)->first();
			$old_quantity = $old ? $old->quantity : 0;

			// difference between old and new quantity
			$diff = $quantity - $old_quantity;

			// Update product quantity
			$item->quantity -= $diff;
			$item->save();

			// update or create sale product
			$sale->products()->updateOrCreate([
				'product_id' => $item->id
			], [
				'quantity' => $quantity
			]);
		}

		// get items not in the request, delete and update quantity
		$oldItems = $sale->products()->whereNotIn('product_id', ($fields['product']));
		foreach ($oldItems->get() as $item) {
			$product = Product::find($item->product_id);
			$product->quantity += $item->quantity;
			$product->save();
		}
		$oldItems->delete();

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' updated sale #' . $sale->id);

		return response()->json([
			'success' => true,
			'message' => __('Venda atualizada com sucesso')
		], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = Sale::find($id);
		if (!$sale) {
			return response()->json([
				'success' => false,
				'message' => __('Venda não encontrada')
			], 400);
		}

		// Delete sale products before deleting the sale
		$items = $sale->products;
		foreach ($items as $item) {
			// Restore the quantity of the product
			$product = Product::find($item->product_id);
			if (!$product) continue;

			$product->quantity += $item->quantity;
			$product->save();
		}
		$sale->products()->delete();
		$sale->delete();

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' deleted sale #' . $sale->id);

		return response()->json([
			'success' => true,
			'message' => __('Venda removida com sucesso')
		], 200);
    }
}
