<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
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
            $data = Customer::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btns = '<div class="button-list">';
                        $btns .= '<button type="button" data-id="' . $row->id . '" class="edit btn btn-secondary btn-sm waves-effect waves-light"><i class="fas fa-edit"></i></a>';
                        $btns .= '<button type="button" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm waves-effect waves-light"><i class="fas fa-trash"></i></a>';
                    $btns .= '</div>';
                    return $btns;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('customers.index', [
            'title' => __('Clientes')
        ]);
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

		// Create validation
        $validator = Validator::make($fields, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:customers',
		]);

		// If validation fails
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()->first()
			], 400);
		}

		// Create new customer
		$customer = new Customer();
		$customer->name = $fields['name'];
		$customer->email = $fields['email'];
		$customer->save();

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' created a new customer [' . $customer->id . '] ' . $customer->name);

		return response()->json([
			'success' => true,
			'message' => __('Cliente criado com sucesso!')
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
		// Find customer
        $customer = Customer::find($id);

		// Check if customer exists
		if (!$customer) {
			return response()->json([
				'success' => false,
				'message' => __('Cliente não encontrado!')
			], 404);
		}

		// Return customer
		return response()->json([
			'success' => true,
			'data' => $customer
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
		// Find customer
        $customer = Customer::find($id);

		// Check if customer exists
		if (!$customer) {
			return response()->json([
				'success' => false,
				'message' => __('Cliente não encontrado!')
			], 404);
		}

		$fields = $request->all();

		// Create validation (email ignore soft deleted)
        $validator = Validator::make($fields, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
		]);

		// If validation fails
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()->first()
			], 400);
		}

		// Update customer
		$customer->name = $fields['name'];
		$customer->email = $fields['email'];
		$customer->save();

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' updated customer [' . $customer->id . '] ' . $customer->name);

		return response()->json([
			'success' => true,
			'message' => __('Cliente atualizado com sucesso!')
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
		// Find customer
        $customer = Customer::find($id);

		// Check if customer exists
		if (!$customer) {
			return response()->json([
				'success' => false,
				'message' => __('Cliente não encontrado!')
			], 404);
		}

		try {
			// Add log
			add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' deleted customer [' . $customer->id . '] ' . $customer->name);

			// Remove customer
			$customer->delete();
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => __('Não foi possível remover o cliente!')
			], 400);
		}

		return response()->json([
			'success' => true,
			'message' => __('Cliente removido com sucesso!')
		]);
    }

	/**
	 * Autocomplete search
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function autocomplete(Request $request)
	{
		$term = $request->input('query');
		$customers = Customer::where('name', 'ilike', '%' . $term . '%')->get();
		$data = [];
		foreach ($customers as $customer) {
			$data[] = [
				'value'	=> (string) $customer->name,
				'data' => [
					'id' => (int) $customer->id,
					'name' => (string) $customer->name,
				]
			];
		}
		return response()->json([
			'query'	=> $term,
			'suggestions' => $data
		]);
	}
}
