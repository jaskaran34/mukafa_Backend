<?php
namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     version="1.6.0",
 *     title="Reward Loyalty API",
 *     description="This API provides endpoints for the various users of the Reward Loyalty script.",
 *     @OA\Contact(
 *         email="support@nowsquare.com"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local API Server"
 * )
 * @OA\Server(
 *     url="https://reward-loyalty-demo.nowsquare.com/api",
 *     description="Demo API Server"
 * )
 * 
 * @OA\Tag(
 *     name="Admin",
 *     description="Operations related to Admininstrators and Managers"
 * ),
 * @OA\Tag(
 *     name="Partner",
 *     description="Operations related to Partners"
 * ),
 * @OA\Tag(
 *     name="Member",
 *     description="Operations related to Members"
 * )
 */
class OpenApiSpec
{
}
