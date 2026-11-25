<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // GET - Menampilkan semua produk
    public function index()
    {
        $products = Product::with('user:id,name')->get();

        return response()->json([
            'message' => 'Daftar produk berhasil ditampilkan',
            'data' => $products
        ], 200);
    }

    // GET - Menampilkan detail produk
    public function show($id)
    {
        $product = Product::with('user:id,name')
            ->findOrFail($id);

        return response()->json([
            'message' => 'Detail produk berhasil ditampilkan',
            'data' => $product
        ], 200);
    }

    // POST - User dan Admin
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_product' => 'required|string|max:100',
            'kategori' => [
                'required',
                Rule::in(['Makanan', 'Minuman', 'Dessert'])
            ],
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0'
        ]);

        $validated['user_id'] = $request->user()->id;

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
        ], 201);
    }

    // PUT/PATCH - User dan Admin
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $rule = $request->isMethod('PUT')
            ? 'required'
            : 'sometimes|required';

        $validated = $request->validate([
            'nama_product' => "$rule|string|max:100",
            'kategori' => [
                $rule,
                Rule::in(['Makanan', 'Minuman', 'Dessert'])
            ],
            'harga' => "$rule|integer|min:0",
            'deskripsi' => $request->isMethod('PUT')
                ? 'present|nullable|string'
                : 'sometimes|nullable|string',
            'stok' => "$rule|integer|min:0"
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data' => $product->fresh()
        ], 200);
    }

    // DELETE - Hanya Admin
    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang dapat menghapus produk'
            ], 403);
        }

        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ], 200);
    }
}