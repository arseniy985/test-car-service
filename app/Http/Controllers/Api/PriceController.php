<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PriceRequest;
use App\Http\Resources\Price\PriceResource;
use App\Models\Price;
use App\Services\PriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceController extends ApiController
{
    protected PriceService $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    /**
     * @OA\Get(
     *     path="/prices",
     *     summary="Get all prices",
     *     tags={"Prices"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all prices",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(ref="#/components/schemas/Price")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResource
    {
        return PriceResource::collection($this->priceService->getAll());
    }

    /**
     * @OA\Post(
     *     path="/prices",
     *     summary="Create a new price",
     *     tags={"Prices"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"configuration_id", "price", "start_date"},
     *             @OA\Property(property="configuration_id", type="integer", example=1),
     *             @OA\Property(property="price", type="number", format="float", example=35000),
     *             @OA\Property(property="start_date", type="string", format="date", example="2023-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2023-12-31")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Price created successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Price")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/ErrorResponse")
     *         )
     *     )
     * )
     */
    public function store(PriceRequest $request): JsonResource
    {
        $price = $this->priceService->create($request->validated());
        return new PriceResource($price);
    }

    /**
     * @OA\Get(
     *     path="/prices/{id}",
     *     summary="Get a single price",
     *     tags={"Prices"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the price to get",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Price details",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Price")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Price not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID format",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ID должен быть числом"),
     *             @OA\Property(property="error", type="string", example="invalid_id_format")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'message' => 'ID должен быть числом',
                'error' => 'invalid_id_format'
            ], 400);
        }
        
        return new PriceResource($this->priceService->findById((int)$id));
    }

    /**
     * @OA\Put(
     *     path="/prices/{id}",
     *     summary="Update a price",
     *     tags={"Prices"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the price to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"configuration_id", "price", "start_date"},
     *             @OA\Property(property="configuration_id", type="integer", example=1),
     *             @OA\Property(property="price", type="number", format="float", example=35000),
     *             @OA\Property(property="start_date", type="string", format="date", example="2023-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2023-12-31")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Price updated successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Price")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Price not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/ErrorResponse")
     *         )
     *     )
     * )
     */
    public function update(PriceRequest $request, Price $price): JsonResource
    {
        $price = $this->priceService->update($price, $request->validated());
        return new PriceResource($price);
    }

    /**
     * @OA\Delete(
     *     path="/prices/{id}",
     *     summary="Delete a price",
     *     tags={"Prices"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the price to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Price deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Price not found"
     *     )
     * )
     */
    public function destroy(Price $price): JsonResponse
    {
        $this->priceService->delete($price);
        return response()->json(null, 204);
    }
} 