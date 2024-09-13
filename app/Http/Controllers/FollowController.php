<?php

namespace App\Http\Controllers;

use App\Commands\Services\Follow\MakeFollowService;
use App\Commands\Services\Follow\UnfollowService;
use App\Queries\Services\ProfileQueryService;
use App\Queries\Services\UserQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class FollowController extends Controller
{
    public function makeFollow(
        MakeFollowService $service,
        UserQueryService $userQueryService,
        ProfileQueryService $queryService,
        Request $request,
        string $username,
    ): JsonResponse {
        $currentUserId = $request->user();

        $followeeId = $userQueryService->getUserIdFromUsername($username);
        if (is_null($followeeId)) {
            throw new NotFoundHttpException("User (username: {$username}) could not be found.");
        }

        $service->handle($currentUserId, $followeeId);

        $profile = $queryService->getProfileByUsername($username, $currentUserId);
        if (is_null($profile)) {
            throw new RuntimeException('$profile is unexpectedly set to null');
        }

        return new JsonResponse(['profile' => $profile]);
    }

    public function unfollow(
        UnfollowService $service,
        UserQueryService $userQueryService,
        ProfileQueryService $profileQueryService,
        Request $request,
        string $username,
    ): JsonResponse {
        $currentUserId = $request->user();

        $followeeId = $userQueryService->getUserIdFromUsername($username);
        if (is_null($followeeId)) {
            throw new NotFoundHttpException("User (username: {$username}) could not be found.");
        }

        $service->handle($currentUserId, $followeeId);

        $profile = $profileQueryService->getProfileByUsername($username, $currentUserId);
        if (is_null($profile)) {
            throw new RuntimeException('$profile is unexpectedly set to null');
        }

        return new JsonResponse(['profile' => $profile]);
    }
}
