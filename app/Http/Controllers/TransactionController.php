<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $transactions = Transaction::with(['user', 'product'])
                ->latest('transaction_id')
                ->get();

            return $this->success($transactions, 'Daftar transaksi');
        } catch (QueryException $e) {
            Log::error('Gagal ambil transaksi: ' . $e->getMessage());

            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $transaction = Transaction::with(['user', 'product'])
                ->find($id);

            if (!$transaction) {
                return $this->error('Transaksi tidak ditemukan', 404);
            }

            return $this->success($transaction, 'Detail transaksi');
        } catch (QueryException $e) {
            Log::error('Gagal ambil detail transaksi: ' . $e->getMessage());

            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer|exists:products,product_id',
                'quantity' => 'required|integer|min:1',
                'total_price' => 'required|numeric|min:0',
                'status' => 'sometimes|in:pending,paid,completed,cancelled',
            ]);

            if ($validator->fails()) {
                return $this->error(
                    'Data tidak valid',
                    422,
                    $validator->errors()
                );
            }

            $transaction = Transaction::create([
                ...$validator->validated(),
                'user_id' => auth('api')->id(),
            ]);

            return $this->success(
                $transaction,
                'Transaksi berhasil ditambahkan',
                201
            );
        } catch (QueryException $e) {
            Log::error('Gagal tambah transaksi: ' . $e->getMessage());

            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return $this->error('Transaksi tidak ditemukan', 404);
            }

            $validator = Validator::make($request->all(), [
                'product_id' => 'sometimes|required|integer|exists:products,product_id',
                'quantity' => 'sometimes|required|integer|min:1',
                'total_price' => 'sometimes|required|numeric|min:0',
                'status' => 'sometimes|in:pending,paid,completed,cancelled',
            ]);

            if ($validator->fails()) {
                return $this->error(
                    'Data tidak valid',
                    422,
                    $validator->errors()
                );
            }
            $transaction->update($validator->validated());

            return $this->success(
                $transaction,
                'Transaksi berhasil diubah'
            );
        } catch (QueryException $e) {
            Log::error('Gagal ubah transaksi: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = Transaction::whereKey($id)->delete();
            if ($deleted === 0) {
                return $this->error('Transaksi tidak ditemukan', 404);
            }

            return $this->success(null, 'Transaksi berhasil dihapus');
        } catch (QueryException $e) {
            Log::error('Gagal hapus transaksi: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan pada database', 500);
        }
    }
}
