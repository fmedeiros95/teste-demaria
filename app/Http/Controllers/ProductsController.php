<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btns = '<div class="button-list">';
                        $btns .= '<button type="button" data-id="' . $row->id . '" class="edit btn btn-secondary btn-sm waves-effect waves-light"><i class="fas fa-edit"></i></a>';
                        $btns .= '<button type="button" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm waves-effect waves-light"><i class="fas fa-trash"></i></a>';
                    $btns .= '</div>';
                    return $btns;
                })
				->editColumn('cost', function($row) {
					return display_money($row->cost);
				})
				->editColumn('price', function($row) {
					return display_money($row->price);
				})
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = __('Produtos');

        return view('products.index', compact('title'));
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
        $validator = Validator::make($fields, [
            'name' => 'required',
            'cost' => 'required|numeric|min:0',
			'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|numeric|min:0'
        ], [
			'name.required' => __('O nome do produto é obrigatório'),
			'cost.required' => __('O preço de custo é obrigatório'),
			'cost.numeric' => __('O preço de custo deve ser um número'),
			'cost.min' => __('O preço de custo deve ser maior que zero'),
			'price.required' => __('O preço de venda é obrigatório'),
			'price.numeric' => __('O preço de venda deve ser um número'),
			'price.min' => __('O preço de venda deve ser maior que zero'),
			'quantity.required' => __('A quantidade é obrigatória'),
			'quantity.numeric' => __('A quantidade deve ser um número'),
			'quantity.min' => __('A quantidade deve ser maior que zero')
		]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

		$product = new Product();
		$product->name = $request->name;
		$product->cost = $request->cost;
		$product->price = $request->price;
		$product->quantity = $request->quantity;
		$product->save();

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' created a new product [' . $product->id . '] ' . $product->name);

        return response()->json([
            'success' => true,
            'message' => __('Produto cadastrado com sucesso')
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => __('Produto não encontrado')
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
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
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => __('Produto não encontrado')
            ], 404);
        }

        $fields = $request->all();
        $validator = Validator::make($fields, [
            'name' => 'required',
			'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|numeric|min:0'
        ], [
			'name.required' => __('O nome do produto é obrigatório'),
			'cost.required' => __('O preço de custo é obrigatório'),
			'cost.numeric' => __('O preço de custo deve ser um número'),
			'cost.min' => __('O preço de custo deve ser maior que zero'),
			'price.required' => __('O preço de venda é obrigatório'),
			'price.numeric' => __('O preço de venda deve ser um número'),
			'price.min' => __('O preço de venda deve ser maior que zero'),
			'quantity.required' => __('A quantidade é obrigatória'),
			'quantity.numeric' => __('A quantidade deve ser um número'),
			'quantity.min' => __('A quantidade deve ser maior que zero')
		]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $product->name = $request->name;
		$product->cost = $request->cost;
		$product->price = $request->price;
		$product->quantity = $request->quantity;
		$product->save();

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' updated product [' . $product->id . '] ' . $product->name);

        return response()->json([
            'success' => true,
            'message' => __('Produto atualizado com sucesso')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => __('Produto não encontrado')
            ], 404);
        }

		try {
			// Add log
			add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' deleted product [' . $product->id . '] ' . $product->name);

			// Delete product
			$product->delete();
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => __('Não foi possível remover o produto')
			], 400);
		}

        return response()->json([
            'success' => true,
            'message' => __('Produto excluído com sucesso')
        ], 201);
    }

	/**
	 * Autocomplete search
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function autocomplete(Request $request)
	{
		$term = $request->input('query');
		$products = Product::where('name', 'ilike', '%' . $term . '%')
			->where('quantity', '>', 0)
			->get();
		$data = [];
		foreach ($products as $product) {
			$data[] = [
				'value'	=> (string) $product->name . ' [Estoque: ' . $product->quantity . ']',
				'data' => [
					'id' => (int) $product->id,
					'name' => (string) $product->name,
					'price' => (float) $product->price,
					'inventory' => (int) $product->quantity
				]
			];
		}
		return response()->json([
			'query'	=> $term,
			'suggestions' => $data
		]);
	}
}
