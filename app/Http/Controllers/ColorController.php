<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use function Laravel\Prompts\error;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        $colors = Cache::remember(
            'colors',
            Carbon::now()->addDays(30),
            fn() => Color::all('id', 'name', 'hex_code')
        );
        return response()->json($colors, Response::HTTP_OK);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $data = $request->validate([
            "name" => ['required', 'string', 'min:3', 'max:20', Rule::unique('colors', 'name')],
            "hex_code" => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i']
        ]);

        $color = Color::create($data);

        return response()->json([
            'message' => 'Color created successfully',
            'data' => [
                'name' => $color->name,
                'hexCode' => $color->hex_code
            ]
        ], Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {

        try {

            $color = Color::findOrFail($id);
            return response()->json($color);

        } catch (\Exception $e) {

            error($e->getMessage());

            return response()->json(['message' => 'color not found'], Response::HTTP_NOT_FOUND);

        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            "name" => ['required', 'string', 'min:3', 'max:20', Rule::unique('colors', 'name')->ignore($id)],
            "hex_code" => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i']
        ]);

        $color = Color::findOrFail($id);

        $color->update($data);

        return response()->json([
            'message' => 'Color updated successfully',
            'data' => [
                'name' => $color->name,
                'hexCode' => $color->hex_code
            ]
        ], Response::HTTP_OK);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {

        try {

            $color = Color::findOrFail($id);
            $color->delete();
            return response()->json([
                'message' => 'Color deleted successfully',
            ], Response::HTTP_OK);

        } catch (\Exception $e) {

            error($e->getMessage());
            return response()->json([
                'message' => 'color not found',
            ], Response::HTTP_NOT_FOUND);
        }


    }
}
