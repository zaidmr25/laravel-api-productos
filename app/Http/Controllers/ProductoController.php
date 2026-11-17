<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductoController extends Controller
{
    /**
     * GET /api/v1/productos
     * Obtener la lista completa de registros mediante ORM.
     * Retorna 200 OK o 500 Internal Error.
     */
    public function index()
    {
        try {
            $productos = Producto::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Lista de productos obtenida correctamente',
                'total' => $productos->count(),
                'data' => $productos
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno al consultar productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/productos/{id}
     * Obtener un registro individual filtrado por su ID.
     * Retorna 200 OK o 404 Not Found.
     */
    public function show($id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Producto con ID {$id} no encontrado"
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Producto encontrado',
                'data' => $producto
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al buscar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/v1/productos
     * Crear un nuevo registro recibido en JSON Body procesado por ORM.
     * Retorna 201 Created o 400 Bad Request.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $producto = Producto::create($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Producto creado exitosamente',
                'data' => $producto
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/v1/productos/{id}
     * Actualizar registro existente mediante JSON Body e ID en URL.
     * Retorna 200 OK, 400 Bad Request o 404 Not Found.
     */
    public function update(Request $request, $id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Producto con ID {$id} no encontrado para actualizar"
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:255',
                'descripcion' => 'sometimes|required|string',
                'precio' => 'sometimes|required|numeric|min:0',
                'stock' => 'sometimes|required|integer|min:0',
                'categoria' => 'sometimes|required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Datos de entrada inválidos para la actualización',
                    'errors' => $validator->errors()
                ], 400);
            }

            $producto->update($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/v1/productos/{id}
     * Eliminar un registro específico por ID utilizando el ORM.
     * Retorna 200 OK o 404 Not Found.
     */
    public function destroy($id)
    {
        try {
            $producto = Producto::find($id);

            if (!$producto) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Producto con ID {$id} no encontrado para eliminar"
                ], 404);
            }

            $producto->delete();

            return response()->json([
                'status' => 'success',
                'message' => "Producto con ID {$id} eliminado exitosamente"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
