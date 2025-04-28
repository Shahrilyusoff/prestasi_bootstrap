<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserType;
use App\Models\PydGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['userType', 'pydGroup'])->get();
        return response()->json($users);
    }

    public function show(User $user)
    {
        $user->load(['userType', 'pydGroup']);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
            'pyd_group_id' => 'nullable|exists:pyd_groups,id',
            'position' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:255',
            'ministry_department' => 'nullable|string|max:255',
            'ic_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type_id' => $request->user_type_id,
            'pyd_group_id' => $request->pyd_group_id,
            'position' => $request->position,
            'grade' => $request->grade,
            'ministry_department' => $request->ministry_department,
            'ic_number' => $request->ic_number,
        ]);

        return response()->json([
            'message' => 'Pengguna berjaya dicipta',
            'user' => $user->load('userType', 'pydGroup'),
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'user_type_id' => 'sometimes|exists:user_types,id',
            'pyd_group_id' => 'nullable|exists:pyd_groups,id',
            'position' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:255',
            'ministry_department' => 'nullable|string|max:255',
            'ic_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->except('password');

        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Pengguna berjaya dikemaskini',
            'user' => $user->fresh()->load('userType', 'pydGroup'),
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Pengguna berjaya dipadam',
        ]);
    }

    public function getUserTypes()
    {
        $userTypes = UserType::all();
        return response()->json($userTypes);
    }

    public function getPydGroups()
    {
        $pydGroups = PydGroup::all();
        return response()->json($pydGroups);
    }

    public function getPPPUsers()
    {
        $pppUsers = User::whereHas('userType', function($query) {
            $query->where('name', 'PPP');
        })->get();

        return response()->json($pppUsers);
    }

    public function getPPKUsers()
    {
        $ppkUsers = User::whereHas('userType', function($query) {
            $query->where('name', 'PPK');
        })->get();

        return response()->json($ppkUsers);
    }

    public function getPYDUsers()
    {
        $pydUsers = User::whereHas('userType', function($query) {
            $query->where('name', 'PYD');
        })->get();

        return response()->json($pydUsers);
    }
}