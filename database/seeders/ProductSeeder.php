<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // MAKANAN
            ['Nasi Goreng Spesial', 'Makanan', 20000],
            ['Mie Goreng Seafood', 'Makanan', 25000],
            ['Ayam Geprek Mozzarella', 'Makanan', 23000],
            ['Chicken Katsu Curry', 'Makanan', 28000],
            ['Beef Teriyaki Rice Bowl', 'Makanan', 30000],
            ['Spaghetti Carbonara', 'Makanan', 27000],
            ['Nasi Ayam Sambal Matah', 'Makanan', 22000],
            ['Chicken Steak BBQ', 'Makanan', 35000],
            ['Dimsum Mentai', 'Makanan', 20000],
            ['French Fries Cheese', 'Makanan', 15000],

            // MINUMAN
            ['Es Teh Manis', 'Minuman', 5000],
            ['Lemon Tea', 'Minuman', 10000],
            ['Matcha Latte', 'Minuman', 18000],
            ['Caramel Macchiato', 'Minuman', 22000],
            ['Iced Americano', 'Minuman', 15000],
            ['Chocolate Milkshake', 'Minuman', 20000],
            ['Strawberry Smoothie', 'Minuman', 22000],
            ['Mango Yakult', 'Minuman', 18000],
            ['Lychee Tea', 'Minuman', 15000],
            ['Red Velvet Latte', 'Minuman', 20000],

            // DESSERT
            ['Chocolate Lava Cake', 'Dessert', 25000],
            ['Strawberry Cheesecake', 'Dessert', 28000],
            ['Tiramisu', 'Dessert', 25000],
            ['Matcha Pudding', 'Dessert', 18000],
            ['Brownies Ice Cream', 'Dessert', 22000],
            ['Mango Panna Cotta', 'Dessert', 25000],
            ['Oreo Cheesecake', 'Dessert', 27000],
            ['Waffle Ice Cream', 'Dessert', 23000],
            ['Churros Chocolate', 'Dessert', 18000],
            ['Banana Split', 'Dessert', 20000],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['nama_product' => $product[0]],
                [
                    'kategori' => $product[1],
                    'harga' => $product[2],
                    'deskripsi' => 'Menu ' . $product[0],
                    'stok' => 20,
                ]
            );
        }
    }
}
