<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Commands\Models\User\EmailDuplicatedException;
use App\Commands\Models\User\InvalidCredentialException;
use App\Commands\Models\User\UsernameDuplicatedException;
use App\Commands\Services\User\AuthenticationService;
use App\Commands\Services\User\RegisterUserService;
use App\Commands\Services\User\UpdateUserSettingsService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserSettingsRequest;
use App\Queries\Services\UserQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class UserController extends Controller
{
    public function registerUser(
        RegisterUserService $service,
        UserQueryService $queryService,
        RegisterUserRequest $request,
    ): JsonResponse {
        try {
            $input = $request->validated('user');
            $writeModel = $service->handle($input);
            $readModel = $queryService->getByUserId($writeModel->id);

            if (is_null($readModel)) {
                throw new RuntimeException('$readModel is unexpectedly set to null');
            }

            return new JsonResponse([
                'user' => $readModel,
            ]);
        } catch (EmailDuplicatedException $ex) {
            throw ValidationException::withMessages(['user.email' => $ex->getMessage()]);
        } catch (UsernameDuplicatedException $ex) {
            throw ValidationException::withMessages(['user.username' => $ex->getMessage()]);
        }
    }

    public function login(
        AuthenticationService $service,
        UserQueryService $queryService,
        LoginRequest $request,
    ): JsonResponse {
        try {
            $input = $request->validated('user');
            $writeModel = $service->handle($input['email'], $input['password']);
            $readModel = $queryService->getByUserId($writeModel->id);

            if (is_null($readModel)) {
                throw new RuntimeException('$readModel is unexpectedly set to null');
            }

            return new JsonResponse([
                'user' => $readModel,
            ]);
        } catch (InvalidCredentialException $ex) {
            throw new UnauthorizedHttpException("", "invalid credentials");
        }
    }

    public function getCurrentUser(
        Request $request,
        UserQueryService $queryService,
    ): JsonResponse {
        $userId = $request->user();
        $readModel = $queryService->getByUserId($userId);

        if (is_null($readModel)) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse([
            'user' => $readModel,
        ]);
    }

    public function updateSettings(
        UpdateUserSettingsService $service,
        UserQueryService $queryService,
        UpdateUserSettingsRequest $request,
    ): JsonResponse {
        $userId = $request->user();
        $input = $request->validated('user');
        $writeModel = $service->handle($userId, $input);
        $readModel = $queryService->getByUserId($writeModel->id);

        if (is_null($readModel)) {
            throw new RuntimeException('$readModel is unexpectedly set to null');
        }

        return new JsonResponse([
            'user' => $readModel,
        ]);
    }
}
