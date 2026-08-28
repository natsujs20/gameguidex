<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    /**
     * Listar los proyectos del usuario autenticado.
     */
    public function index(
        Request $request
    ): JsonResponse {
        $proyectos = $request
            ->user()
            ->proyectos()
            ->latest()
            ->get();

        return response()->json([
            'proyectos' => $proyectos,
        ]);
    }

    /**
     * Crear un proyecto perteneciente
     * al usuario autenticado.
     */
    public function store(
        Request $request
    ): JsonResponse {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $proyecto = $request
            ->user()
            ->proyectos()
            ->create([
                'nombre' => $datos['nombre'],

                'descripcion' => $datos['descripcion']
                    ?? null,
            ]);

        return response()->json([
            'mensaje' => 'Proyecto creado correctamente.',
            'proyecto' => $proyecto,
        ], 201);
    }

    /**
     * Mostrar un proyecto específico.
     */
    public function show(
        Request $request,
        Proyecto $proyecto
    ): JsonResponse {
        if (!$this->perteneceAlUsuario(
            $request,
            $proyecto
        )) {
            return $this->respuestaProhibida();
        }

        return response()->json([
            'proyecto' => $proyecto,
        ]);
    }

    /**
     * Actualizar un proyecto.
     */
    public function update(
        Request $request,
        Proyecto $proyecto
    ): JsonResponse {
        if (!$this->perteneceAlUsuario(
            $request,
            $proyecto
        )) {
            return $this->respuestaProhibida();
        }

        $datos = $request->validate([
            'nombre' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'descripcion' => [
                'sometimes',
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $proyecto->update(
            $datos
        );

        return response()->json([
            'mensaje' => 'Proyecto actualizado correctamente.',
            'proyecto' => $proyecto->fresh(),
        ]);
    }

    /**
     * Eliminar un proyecto.
     */
    public function destroy(
        Request $request,
        Proyecto $proyecto
    ): JsonResponse {
        if (!$this->perteneceAlUsuario(
            $request,
            $proyecto
        )) {
            return $this->respuestaProhibida();
        }

        $proyecto->delete();

        return response()->json([
            'mensaje' => 'Proyecto eliminado correctamente.',
        ]);
    }

    /**
     * Comprobar la propiedad del proyecto.
     */
    private function perteneceAlUsuario(
        Request $request,
        Proyecto $proyecto
    ): bool {
        return (int) $proyecto->created_by
            === (int) $request->user()->id;
    }

    /**
     * Evitar que un usuario manipule
     * proyectos pertenecientes a otro usuario.
     */
    private function respuestaProhibida(): JsonResponse
    {
        return response()->json([
            'mensaje' => 'No tienes autorización para acceder a este proyecto.',
        ], 403);
    }
}