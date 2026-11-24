<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();

        if (!$user) {
            return;
        }

        $products = Product::all();

        $comments = [
            'Rasanya sangat enak dan memuaskan.',
            'Harga terjangkau dan kualitas bagus.',
            'Sangat recommended, pasti pesan lagi.',
            'Porsinya pas dan rasanya enak.',
            'Produk cukup bagus, tetapi masih bisa ditingkatkan.',
        ];

        foreach ($products as $index => $product) {
            ProductReview::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $product->product_id,
                ],
                [
                    'rating' => 5 - ($index % 3),
                    'comment' => $comments[$index % count($comments)],
                ]
            );
        }
    }
}