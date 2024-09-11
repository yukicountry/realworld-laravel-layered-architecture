<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class FollowController extends Controller
{
    public function makeFollow(): JsonResponse
    {
        return new JsonResponse();
    }

    public function unfollow(): JsonResponse
    {
        return new JsonResponse();
    }
}
