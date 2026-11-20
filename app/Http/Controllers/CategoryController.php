<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            return $this->success(Category::latest('id')->get(), 'Daftar kategori');
        } catch (QueryException $e) {
            Log::error('Gagal ambil kategori: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return $this->error('Kategori tidak ditemukan', 404);
            }
            return $this->success($category, 'Detail kategori');
        } catch (QueryException $e) {
            Log::error('Gagal ambil detail kategori: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama'      => 'required|string|max:50|unique:categories,nama',
                'deskripsi' => 'nullable|string|max:255',
            ]);
            if ($validator->fails()) {
                return $this->error('Data tidak valid', 422, $validator->errors());
            }

            $category = Category::create([
                ...$validator->validated(),
                'user_id' => auth('api')->id(),
            ]);

            return $this->success($category, 'Kategori berhasil ditambahkan', 201);
        } catch (QueryException $e) {
            Log::error('Gagal tambah kategori: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return $this->error('Kategori tidak ditemukan', 404);
            }

            $validator = Validator::make($request->all(), [
                'nama'      => 'sometimes|required|string|max:50|unique:categories,nama,' . $category->id,
                'deskripsi' => 'nullable|string|max:255',
            ]);
            if ($validator->fails()) {
                return $this->error('Data tidak valid', 422, $validator->errors());
            }

            $category->update($validator->validated());
            return $this->success($category, 'Kategori berhasil diubah');
        } catch (QueryException $e) {
            Log::error('Gagal ubah kategori: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = Category::whereKey($id)->delete();
            if ($deleted === 0) {
                return $this->error('Kategori tidak ditemukan', 404);
            }
            return $this->success(null, 'Kategori berhasil dihapus');
        } catch (QueryException $e) {
            Log::error('Gagal hapus kategori: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }
}