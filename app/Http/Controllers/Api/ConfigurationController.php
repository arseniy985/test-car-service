<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ConfigurationRequest;
use App\Http\Resources\Configuration\ConfigurationResource;
use App\Models\Configuration;
use App\Services\ConfigurationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationController extends ApiController
{
    protected ConfigurationService $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * @OA\Get(
     *     path="/configurations",
     *     summary="Get all configurations",
     *     tags={"Configurations"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all configurations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(ref="#/components/schemas/Configuration")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResource
    {
        return ConfigurationResource::collection($this->configurationService->getAll());
    }

    /**
     * @OA\Post(
     *     path="/configurations",
     *     summary="Create a new configuration",
     *     tags={"Configurations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"car_id", "name"},
     *             @OA\Property(property="car_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Comfort"),
     *             @OA\Property(
     *                 property="options",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Configuration created successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Configuration")
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
    public function store(ConfigurationRequest $request): JsonResource
    {
        $configuration = $this->configurationService->create($request->validated());
        return new ConfigurationResource($configuration);
    }

    /**
     * @OA\Get(
     *     path="/configurations/{id}",
     *     summary="Get a single configuration",
     *     tags={"Configurations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the configuration to get",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Configuration details",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Configuration")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Configuration not found"
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
        
        return new ConfigurationResource($this->configurationService->findById((int)$id));
    }

    /**
     * @OA\Put(
     *     path="/configurations/{id}",
     *     summary="Update a configuration",
     *     tags={"Configurations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the configuration to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"car_id", "name"},
     *             @OA\Property(property="car_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Comfort"),
     *             @OA\Property(
     *                 property="options",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Configuration updated successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Configuration")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Configuration not found"
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
    public function update(ConfigurationRequest $request, Configuration $configuration): JsonResource
    {
        $configuration = $this->configurationService->update($configuration, $request->validated());
        return new ConfigurationResource($configuration);
    }

    /**
     * @OA\Delete(
     *     path="/configurations/{id}",
     *     summary="Delete a configuration",
     *     tags={"Configurations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the configuration to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Configuration deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Configuration not found"
     *     )
     * )
     */
    public function destroy(Configuration $configuration): JsonResponse
    {
        $this->configurationService->delete($configuration);
        return response()->json(null, 204);
    }
} 