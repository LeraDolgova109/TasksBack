<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make($request['password']);
        $user = User::create($data);
        $token = auth()->tokenById($user['id']);
        return response() -> json(['token' => $token]);
    }

    public function update(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();
        $user->update([
            'nickname' => $data['nickname'],
            'image' => $data['image']
        ]);
        return response() -> json($user);
    }
}
