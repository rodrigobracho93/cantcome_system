<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@cantcome.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Cantina',
            'email' => 'cantina@cantcome.com',
            'password' => bcrypt('cantina123'),
            'role' => 'cantina',
            'is_active' => true,
        ]);

        $cat = Category::create(['name' => 'Bebidas', 'description' => 'Bebidas en general']);
        Category::create(['name' => 'Comidas', 'description' => 'Comidas rápidas']);
        Category::create(['name' => 'Snacks', 'description' => 'Snacks y dulces']);

        Product::create(['category_id' => $cat->id, 'name' => 'Coca Cola 355ml', 'price' => 1.50, 'stock' => 100]);
        Product::create(['category_id' => $cat->id, 'name' => 'Pepsi 355ml', 'price' => 1.50, 'stock' => 100]);
        Product::create(['category_id' => $cat->id, 'name' => 'Agua Mineral 500ml', 'price' => 1.00, 'stock' => 100]);
    }
}
