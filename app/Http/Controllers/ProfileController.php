<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function getProfile(): JsonResponse
    {
        return new JsonResponse();
    }
}
