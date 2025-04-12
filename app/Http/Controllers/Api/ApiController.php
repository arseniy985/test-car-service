<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="Car Configurations API",
 *     version="1.0.0",
 *     description="API for managing cars, configurations, options and prices"
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 */
abstract class ApiController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The given data was invalid."
     *     ),
     *     @OA\Property(
     *         property="errors",
     *         type="object",
     *         example={"field": {"The field is required."}}
     *     )
     * )
     * 
     * @OA\Schema(
     *     schema="Car",
     *     required={"id", "name"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Toyota Camry"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * 
     * @OA\Schema(
     *     schema="Option",
     *     required={"id", "name"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Climate Control"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * 
     * @OA\Schema(
     *     schema="Configuration",
     *     required={"id", "car_id", "name"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="car_id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Comfort"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * 
     * @OA\Schema(
     *     schema="Price",
     *     required={"id", "configuration_id", "price", "start_date"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="configuration_id", type="integer", example=1),
     *     @OA\Property(property="price", type="number", format="float", example=35000),
     *     @OA\Property(property="start_date", type="string", format="date", example="2023-01-01"),
     *     @OA\Property(property="end_date", type="string", format="date", example="2023-12-31"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * 
     * @OA\Schema(
     *     schema="AvailableCar",
     *     required={"id", "name", "configurations"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Toyota Camry"),
     *     @OA\Property(
     *         property="configurations",
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             required={"id", "name", "options", "current_price"},
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="name", type="string", example="Comfort"),
     *             @OA\Property(
     *                 property="options",
     *                 type="array",
     *                 @OA\Items(type="string", example="Climate Control")
     *             ),
     *             @OA\Property(property="current_price", type="number", format="float", example=35000)
     *         )
     *     )
     * )
     */
} 