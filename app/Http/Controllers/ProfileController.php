<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\Services\ProfileQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ProfileController extends Controller
{
    public function getProfile(ProfileQueryService $queryService, Request $request, string $username): JsonResponse
    {
        $profile = $queryService->getProfileByUsername($username, $request->user());

        if (is_null($profile)) {
            throw new NotFoundHttpException("Profile of {$username} could not be found.");
        }

        return new JsonResponse(['profile' => $profile]);
    }
}
