<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    private $roles = [];

    public function __construct()
    {
        $this->middleware('auth');

        $this->roles['seller'] = __('Vendedor');
        $this->roles['admin'] = __('Administrador');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		Gate::authorize('admin');

		if ($request->ajax()) {
            $data = User::latest()->get();
            return DataTables::of($data)
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

        return view('users.index', [
            'title' => __('Usuários'),
            'roles' => $this->roles
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
		Gate::authorize('admin');

		$fields = $request->all();

		// Create validation
        $validator = Validator::make($fields, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'role' => 'required|in:' . implode(',', array_keys($this->roles)),
			'password' => 'required|string|min:6|confirmed'
		]);

		// If validation fails
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()->first()
			], 400);
		}

		// Create new user
		$user = new User();
		$user->name = $fields['name'];
		$user->email = $fields['email'];
		$user->password = bcrypt($fields['password']);
		$user->role = $fields['role'];
		$user->save();

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' created a new user [' . $user->id . '] ' . $user->name);

		return response()->json([
			'success' => true,
			'message' => __('Usuário criado com sucesso!')
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
		Gate::authorize('admin');

		// Find user
        $user = User::find($id);

		// Check if user exists
		if (!$user) {
			return response()->json([
				'success' => false,
				'message' => __('Usuário não encontrado!')
			], 404);
		}

		// Return user
		return response()->json([
			'success' => true,
			'data' => $user
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
		Gate::authorize('admin');

		// Find user
        $user = User::find($id);

		// Check if user exists
		if (!$user) {
			return response()->json([
				'success' => false,
				'message' => __('Usuário não encontrado!')
			], 404);
		}

		$fields = $request->all();

		// Edit validation (only if password is changed)
        $validator = Validator::make($fields, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
			'role' => 'required|in:' . implode(',', array_keys($this->roles)),
			'password' => 'nullable|string|min:6|confirmed'
		]);

		// If validation fails
		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'message' => $validator->errors()->first()
			], 400);
		}

		// Update user
		$user->name = $fields['name'];
		$user->email = $fields['email'];
		$user->role = $fields['role'];
		if ($fields['password']) {
			$user->password = bcrypt($fields['password']);
		}
		$user->save();

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' updated user [' . $user->id . '] ' . $user->name);

		return response()->json([
			'success' => true,
			'message' => __('Usuário atualizado com sucesso!')
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
		Gate::authorize('admin');

		// Find user
        $user = User::find($id);

		// Check if user exists
		if (!$user) {
			return response()->json([
				'success' => false,
				'message' => __('Usuário não encontrado!')
			], 404);
		}

		// Can't delete yourself
		if ($user->id == auth()->user()->id) {
			return response()->json([
				'success' => false,
				'message' => __('Você não pode excluir seu próprio usuário!')
			], 404);
		}

		// Add log
		add_log('User [' . auth()->user()->id . '] ' . auth()->user()->name . ' deleted user [' . $user->id . '] ' . $user->name);

		// Remove user
		$user->delete();

		return response()->json([
			'success' => true,
			'message' => __('Usuário excluído com sucesso!')
		]);
    }
}
