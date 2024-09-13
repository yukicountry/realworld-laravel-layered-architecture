<?php

namespace App\Http\Controllers;

use App\Commands\Models\Follow\UserNotFoundException;
use App\Commands\Services\Follow\MakeFollowService;
use App\Commands\Services\Follow\UnfollowService;
use App\Queries\Services\ProfileQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class FollowController extends Controller
{
    public function makeFollow(
        MakeFollowService $service,
        ProfileQueryService $queryService,
        Request $request,
        string $username,
    ): JsonResponse {
        $currentUserId = $request->user();

        try {
            $service->handle($currentUserId, $username);
        } catch (UserNotFoundException $ex) {
            throw new NotFoundHttpException("User (username: {$username}) could not be found.");
        }

        $profile = $queryService->getProfileByUsername($username, $currentUserId);
        if (is_null($profile)) {
            throw new RuntimeException('$profile is unexpectedly set to null');
        }

        return new JsonResponse(['profile' => $profile]);
    }

    public function unfollow(
        UnfollowService $service,
        ProfileQueryService $queryService,
        Request $request,
        string $username,
    ): JsonResponse {
        $currentUserId = $request->user();
        $service->handle($currentUserId, $username);
        $profile = $queryService->getProfileByUsername($username, $currentUserId);
        if (is_null($profile)) {
            throw new RuntimeException('$profile is unexpectedly set to null');
        }

        return new JsonResponse(['profile' => $profile]);
    }
}
