<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OptionRequest;
use App\Http\Resources\Option\OptionResource;
use App\Models\Option;
use App\Services\OptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionController extends ApiController
{
    protected OptionService $optionService;

    public function __construct(OptionService $optionService)
    {
        $this->optionService = $optionService;
    }

    /**
     * @OA\Get(
     *     path="/options",
     *     summary="Get all options",
     *     tags={"Options"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all options",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(ref="#/components/schemas/Option")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResource
    {
        return OptionResource::collection($this->optionService->getAll());
    }

    /**
     * @OA\Post(
     *     path="/options",
     *     summary="Create a new option",
     *     tags={"Options"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Climate Control")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Option created successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Option")
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
    public function store(OptionRequest $request): JsonResource
    {
        $option = $this->optionService->create($request->validated());
        return new OptionResource($option);
    }

    /**
     * @OA\Get(
     *     path="/options/{id}",
     *     summary="Get a single option",
     *     tags={"Options"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the option to get",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Option details",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Option")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option not found"
     *     )
     * )
     */
    public function show(Option $option): JsonResource
    {
        return new OptionResource($option);
    }

    /**
     * @OA\Put(
     *     path="/options/{id}",
     *     summary="Update an option",
     *     tags={"Options"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the option to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Climate Control")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Option updated successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/Option")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option not found"
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
    public function update(OptionRequest $request, Option $option): JsonResource
    {
        $option = $this->optionService->update($option, $request->validated());
        return new OptionResource($option);
    }

    /**
     * @OA\Delete(
     *     path="/options/{id}",
     *     summary="Delete an option",
     *     tags={"Options"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the option to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Option deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option not found"
     *     )
     * )
     */
    public function destroy(Option $option): JsonResponse
    {
        $this->optionService->delete($option);
        return response()->json(null, 204);
    }
} 