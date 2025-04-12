<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CarRequest;
use App\Http\Resources\Car\AvailableCarResource;
use App\Http\Resources\Car\CarResource;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CarController extends ApiController
{
    protected CarService $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * @OA\Get(
     *     path="/cars",
     *     summary="Get all cars",
     *     tags={"Cars"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all cars",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(ref="#/components/schemas/Car")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResource
    {
        return CarResource::collection($this->carService->getAll());
    }

    /**
     * @OA\Post(
     *     path="/cars",
     *     summary="Create a new car",
     *     tags={"Cars"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Toyota Camry")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Car created successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Car")
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
    public function store(CarRequest $request): JsonResource
    {
        $car = $this->carService->create($request->validated());
        return new CarResource($car);
    }

    /**
     * @OA\Get(
     *     path="/cars/{id}",
     *     summary="Get a single car",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the car to get",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Car details",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Car not found"
     *     )
     * )
     */
    public function show(Car $car): JsonResource
    {
        return new CarResource($car);
    }

    /**
     * @OA\Put(
     *     path="/cars/{id}",
     *     summary="Update a car",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the car to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Toyota Camry")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Car updated successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Car not found"
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
    public function update(CarRequest $request, Car $car): JsonResource
    {
        $car = $this->carService->update($car, $request->validated());
        return new CarResource($car);
    }

    /**
     * @OA\Delete(
     *     path="/cars/{id}",
     *     summary="Delete a car",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the car to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Car deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Car not found"
     *     )
     * )
     */
    public function destroy(Car $car): JsonResponse
    {
        $this->carService->delete($car);
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/cars/available",
     *     summary="Get all available cars with their configurations and prices",
     *     tags={"Cars"},
     *     @OA\Response(
     *         response=200,
     *         description="List of available cars with configurations and prices",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(ref="#/components/schemas/AvailableCar")
     *             )
     *         )
     *     )
     * )
     */
    public function available(): JsonResource
    {
        $cars = $this->carService->getAvailable();
        return AvailableCarResource::collection($cars);
    }
} 