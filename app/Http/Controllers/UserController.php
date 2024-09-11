<?php

namespace App\Http\Controllers;

use App\Commands\Models\User\EmailDuplicatedException;
use App\Commands\Models\User\UsernameDuplicatedException;
use App\Commands\Services\User\RegisterUserService;
use App\Http\Requests\RegisterUserRequest;
use App\Queries\Services\UserQueryService;
use Illuminate\Http\JsonResponse;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class UserController extends Controller
{
    public function registerUser(
        RegisterUserService $service,
        UserQueryService $queryService,
        RegisterUserRequest $request,
    ): JsonResponse {
        try {
            $input = $request->validated()['user'];
            $writeModel = $service->handle($input);
            $readModel = $queryService->getByUserId($writeModel->id);

            if (is_null($readModel)) {
                throw new RuntimeException('$readModel is unexpectedly set to null');
            }

            return new JsonResponse([
                'user' => $readModel,
            ]);
        } catch (EmailDuplicatedException | UsernameDuplicatedException $ex) {
            throw new UnprocessableEntityHttpException($ex->getMessage());
        }
    }

    public function login(): JsonResponse
    {
        return new JsonResponse();
    }

    public function getCurrentUser(): JsonResponse
    {
        return new JsonResponse();
    }

    public function updateSettings(): JsonResponse
    {
        return new JsonResponse();
    }
}
