<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    public function makeFavorite(): JsonResponse
    {
        return new JsonResponse();
    }

    public function unfavorite(): JsonResponse
    {
        return new JsonResponse();
    }
}
