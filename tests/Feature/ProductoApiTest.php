<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductoApiTest extends TestCase
{
    public function test_get_all_productos(): void
    {
        $response = $this->getJson('/api/v1/productos');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'total',
                     'data' => [
                         '*' => ['id', 'nombre', 'descripcion', 'precio', 'stock', 'categoria', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function test_get_single_producto(): void
    {
        $response = $this->getJson('/api/v1/productos/1');
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }

    public function test_get_producto_not_found(): void
    {
        $response = $this->getJson('/api/v1/productos/9999');
        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error'
                 ]);
    }

    public function test_create_producto_validation_error(): void
    {
        $response = $this->postJson('/api/v1/productos', []);
        $response->assertStatus(400)
                 ->assertJson([
                     'status' => 'error'
                 ]);
    }

    public function test_create_producto_success(): void
    {
        $response = $this->postJson('/api/v1/productos', [
            'nombre' => 'Mouse Inalámbrico',
            'descripcion' => 'Mouse ergonómico 2.4GHz con batería recargable',
            'precio' => 450.00,
            'stock' => 20,
            'categoria' => 'Accesorios'
        ]);
        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }

    public function test_update_producto_success(): void
    {
        $response = $this->putJson('/api/v1/productos/1', [
            'precio' => 22999.00
        ]);
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }

    public function test_update_producto_not_found(): void
    {
        $response = $this->putJson('/api/v1/productos/9999', [
            'precio' => 100.00
        ]);
        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error'
                 ]);
    }

    public function test_delete_producto_success(): void
    {
        $response = $this->deleteJson('/api/v1/productos/2');
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }

    public function test_delete_producto_not_found(): void
    {
        $response = $this->deleteJson('/api/v1/productos/9999');
        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error'
                 ]);
    }
}
