<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    use ApiResponse;

    // GET - Semua review
    public function index(): JsonResponse
    {
        $reviews = ProductReview::with('user:id,name')
            ->latest()
            ->get();

        return $this->success($reviews, 'Daftar review produk');
    }

    // GET - Detail review
    public function show(int $id): JsonResponse
    {
        $review = ProductReview::with('user:id,name')
            ->find($id);

        if (!$review) {
            return $this->error('Review tidak ditemukan', 404);
        }

        return $this->success($review, 'Detail review produk');
    }

    // POST - Tambah review
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|min:1',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->error(
                'Data tidak valid',
                422,
                $validator->errors()
            );
        }

        $review = ProductReview::create([
            'user_id' => auth('api')->id(),
            ...$validator->validated(),
        ]);

        return $this->success(
            $review,
            'Review berhasil ditambahkan',
            201
        );
    }

    // PUT/PATCH - Update review
    public function update(Request $request, int $id): JsonResponse
    {
        $review = ProductReview::find($id);

        if (!$review) {
            return $this->error('Review tidak ditemukan', 404);
        }

        // User hanya bisa mengubah review sendiri
        if (
            auth('api')->user()->role !== 'admin' &&
            $review->user_id !== auth('api')->id()
        ) {
            return $this->error('Akses ditolak', 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|integer|between:1,5',
            'comment' => 'sometimes|nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->error(
                'Data tidak valid',
                422,
                $validator->errors()
            );
        }

        $review->update($validator->validated());

        return $this->success(
            $review->fresh(),
            'Review berhasil diperbarui'
        );
    }

    // DELETE - Khusus admin
    public function destroy(int $id): JsonResponse
    {
        $review = ProductReview::find($id);

        if (!$review) {
            return $this->error('Review tidak ditemukan', 404);
        }

        $review->delete();

        return $this->success(
            null,
            'Review berhasil dihapus'
        );
    }
}
