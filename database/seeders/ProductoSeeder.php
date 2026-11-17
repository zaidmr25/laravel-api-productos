<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create([
            'nombre' => 'Laptop Gamer Pro 16',
            'descripcion' => 'Procesador Intel i7, 16GB RAM, 512GB SSD, GPU RTX 4060',
            'precio' => 24999.99,
            'stock' => 12,
            'categoria' => 'Computadoras'
        ]);

        Producto::create([
            'nombre' => 'Teclado Mecánico RGB',
            'descripcion' => 'Switches mecánicos táctiles, retroiluminación RGB configurable',
            'precio' => 1299.50,
            'stock' => 35,
            'categoria' => 'Accesorios'
        ]);

        Producto::create([
            'nombre' => 'Monitor 27 Pulgadas 144Hz',
            'descripcion' => 'Panel IPS QHD 2K, tasa de refresco 144Hz con FreeSync',
            'precio' => 5499.00,
            'stock' => 8,
            'categoria' => 'Monitores'
        ]);
    }
}
