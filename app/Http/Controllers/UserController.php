<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $this->authorizeAdmin();
        //validate
        $user = User::all();
        return response()->json(['user' => $user], 200);
    }

    // {
    //     // $this->authorizeAdmin();
    //     return response()->json(User::findOrFail($id));
    // }

    // public function store(Request $request)
    // {
    //     // $this->authorizeAdmin();
    //     $request->validate([
    //         'name'=>'required',
    //         'email'=>'required|email|unique:users',
    //         'password'=>'required|min:6',
    //         'role'=>'required|in:admin,user'
    //     ]);

    //     $user = User::create([
    //         'name'=>$request->name,
    //         'email'=>$request->email,
    //         'password'=>bcrypt($request->password),
    //         'role'=>$request->role
    //     ]);

    //     return response()->json($user,201);
    // }

    // public function update(Request $request, $id)
    // {
    //     // $this->authorizeAdmin();
    //     $user = User::findOrFail($id);

    //     if ($request->has('password')) {
    //         $request['password'] = bcrypt($request->password);
    //     }

    //     $user->update($request->all());
    //     return response()->json($user);
    // }

    // public function destroy($id)
    // {
    //     // $this->authorizeAdmin();
    //     $user = User::findOrFail($id);
    //     $user->delete();
    //     return response()->json(['message'=>'User deleted']);
    // }

    // private function authorizeAdmin()
    // {
    //     if (auth('api')->user()->role !== 'admin') {
    //         abort(403, 'Admin only');
    //     }
    // }

    // public function index()
    // {
    //     $this->authorizeAdmin();

    //     // tindakan setelah validasi
    //     $users = User::paginate(10);

    //     return response()->json($users);
    // }

    public function show($id)
    {
        $this->authorizeAdmin();

        // validasi input
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        // validasi input
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'role'=>'required|in:admin,user'
        ]);

        // tindakan setelah validasi
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role
        ]);

        return response()->json([
            'message'=>'User created successfully',
            'data'=>$user
        ],201);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        // validasi input
        $request->validate([
            'name'=>'sometimes|string|max:255',
            'email'=>'sometimes|email|unique:users,email,'.$id,
            'password'=>'sometimes|min:6',
            'role'=>'sometimes|in:admin,user'
        ]);

        // tindakan setelah validasi
        $user = User::findOrFail($id);
        if ($request->has('password')) {
            $request['password'] = bcrypt($request->password);
        }
        $user->update($request->all());

        return response()->json([
            'message'=>'User updated successfully',
            'data'=>$user
        ]);
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        // validasi input
        $user = User::findOrFail($id);

        // tindakan setelah validasi
        $user->delete();

        return response()->json(['message'=>'User deleted successfully']);
    }

    private function authorizeAdmin()
    {
        if (auth('api')->user()->role !== 'admin') {
            abort(403,'Admin only');
        }
    }


}
