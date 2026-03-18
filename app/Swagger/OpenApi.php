<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Admin API",
 *     version="1.0.0",
 *     description="Admin Authentication APIs with Mobile OTP"
 * )
 *
 * @OA\Server(
 *      url="http://103.153.58.130",
 *     description="Live Server"
 * )
 *
 * @OA\Tag(
 *     name="Admin Auth",
 *     description="Admin authentication APIs"
 * )
 */
class OpenAPI
{
    // Required for Swagger scan
}