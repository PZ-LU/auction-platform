<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Resources\Users\UserBasic as UserBasicResources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $users = User::where('role', '=', 'user')->get();

        return response()->json([
            'status' => 'success',
            'users' => $users->toArray()
        ], 200);
    }

    public function show(Request $request, $id) {
        $user = User::find($id);

        return response()->json([
            'status' => 'success',
            'user' => $user->toArray()
        ], 200);
    }

    public function update(Request $request) {
        $user = Auth::user();

        $v = Validator::make($request->all(), [
            'username' => 'unique:users,username,' . $request->user_id,
            'email' => 'unique:users,email,' . $request->user_id
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $user->fill([
            'name' => $request->name,
            'surname' => $request->surname,
            'username' => $request->username,
            'email' => $request->email,
            'gender' => $request->gender
        ]);

        if ($request->password) {
            $user->fill([
                'password' => bcrypt($request->password)
            ]);
        }

        $user->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function updateAvatar (Request $request) {
        $user = Auth::user();
        $newLabel = md5(time()+rand()).'.'.$request->avatar->getClientOriginalExtension();
        $request->avatar->storeAs(
            'public/uploads/users', $newLabel
        );
        $user->avatar_path = Storage::disk('public')->url('uploads/users/'.$newLabel);
        $user->save();
        return response()->json(['status' => 'success'], 200);
    }

    public function delete (Request $request) {
        $user = Auth::user();
        $user->delete();

        return response()->json(['status' => 'success'], 200);
    }

    public static function findById ($userId) {
        return new UserBasicResources(User::find($userId));
    }

    public function findByIDs ($userIds) {
        return UserBasicResources::collection(User::whereIn('id', $userIds)->get());
    }

    public function setRole (Request $request) {
        $userToChange = User::find($request->user_id);
        $userToChange->role = $request->role;
        $userToChange->save();
        return;
    }

    public function setStatus (Request $request) {
        $userToChange = User::find($request->user_id);
        $userToChange->status = $request->status;
        $userToChange->save();
        return;
    }

    public function showAll () {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'users' => $users->toArray()
        ], 200);
    }
}
