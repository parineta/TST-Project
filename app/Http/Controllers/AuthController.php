<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);
            if ($validator->fails()) {
                return $this->error('Data tidak valid', 422, $validator->errors());
            }

            $user = User::create([
                ...$validator->validated(),
                'role' => 'user', // register selalu jadi user
            ]);

            return $this->success($user->only('id', 'name', 'email', 'role'), 'Register berhasil', 201);
        } catch (QueryException $e) {
            Log::error('Gagal register: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->error('Data tidak valid', 422, $validator->errors());
            }

            $token = auth('api')->attempt($validator->validated());
            if (!$token) {
                return $this->error('Email atau password salah', 401);
            }

            return $this->success([
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'expires_in'   => auth('api')->factory()->getTTL() * 60,
            ], 'Login berhasil');
        } catch (QueryException $e) {
            Log::error('Gagal login: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function me(): JsonResponse
    {
        return $this->success(auth('api')->user()->only('id', 'name', 'email', 'role'), 'Profil pengguna');
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return $this->success(null, 'Logout berhasil');
    }
}