<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use function Laravel\Prompts\error;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $colors = Cache::remember(
            'sizes',
            Carbon::now()->addDays(30),
            fn() => Size::all('id', 'name')
        );
        return response()->json($colors, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "name" => ["required", "string", "min:1", "max:10", Rule::unique("sizes", "name")]
        ]);

        $size = Size::create($data);

        return response()->json($size, Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {

            $size = Size::findOrFail($id);
            return response()->json($size, Response::HTTP_OK);

        } catch (\Exception $e) {

            error("show size : {$e->getMessage()}");
            return response()->json(["message" => "size not found"]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $data = $request->validate([
            "name" => ["required", "string", "min:1", "max:10", Rule::unique("sizes", "name")->ignore($id)]
        ]);

        try {

            $size = Size::findOrFail($id);

            $size->update($data);

            return response()->json($size, Response::HTTP_OK);

        } catch (\Exception $e) {

            error("show size : {$e->getMessage()}");

            return response()->json(["message" => "size not found"],Response::HTTP_NOT_FOUND);
        }



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
