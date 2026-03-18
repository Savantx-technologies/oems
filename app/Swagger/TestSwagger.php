<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Test API",
 *     version="1.0.0"
 * )
 */

/**
 * @OA\Get(
 *     path="/api/test",
 *     operationId="testApi",
 *     tags={"Test"},
 *     summary="Test endpoint",
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     )
 * )
 */