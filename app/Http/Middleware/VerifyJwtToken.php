<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Shared\Jwt\JwtManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class VerifyJwtToken
{
    public function __construct(private readonly JwtManager $jwtManager) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization');

        if (is_null($header)) {
            throw new UnauthorizedHttpException('', 'invalid authorization token');
        }

        preg_match('/^[Tt]oken ([!-~]*)$/', $header, $matches);
        $userId = $this->jwtManager->decode($matches[1]);

        $request->setUserResolver(function () use ($userId) {
            return $userId;
        });

        return $next($request);
    }
}
