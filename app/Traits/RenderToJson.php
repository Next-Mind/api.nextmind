<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

trait RenderToJson
{
    /**
     * render
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return Response::json([
            'error' => class_basename($this),
            'message' => $this->getMessage()
        ], $this->getCode());
    }
}
