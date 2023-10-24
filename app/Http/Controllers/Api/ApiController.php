<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @package App\Http\Controllers\Api
 */
class ApiController extends Controller 
{

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $data 
     * @param int $statusCode 
     * @return JsonResponse 
     * @throws BindingResolutionException 
     */
    protected function formatResponse(array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json(['resp' => $data], $statusCode);
    }
}