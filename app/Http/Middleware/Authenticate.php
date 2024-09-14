<?php declare(strict_types=1);

namespace App\Http\Middleware;

use App\Shared\Jwt\JwtEncoder;
use Closure;
use DomainException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use UnexpectedValueException;

final class Authenticate
{
    public function __construct(private readonly JwtEncoder $jwtManager) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if authorization header is not set, set user to null and go next
        $header = $request->header('Authorization');
        if (is_null($header)) {
            $request->setUserResolver(function () {
                return null;
            });
            return $next($request);
        }

        // decode jwt token and extract user id
        preg_match('/^[Tt]oken ([!-~]*)$/', $header, $matches);
        if (!isset($matches[0])) {
            throw new UnauthorizedHttpException('', 'invalid authorization token');
        }
        try {
            $userId = $this->jwtManager->decode($matches[1]);
        } catch (
            InvalidArgumentException
            | DomainException
            | UnexpectedValueException
            | SignatureInvalidException
            | BeforeValidException
            | ExpiredException $ex
        ) {
            throw new UnauthorizedHttpException('', 'invalid authorization token');
        }

        // set user id to request
        $request->setUserResolver(function () use ($userId) {
            return $userId;
        });

        return $next($request);
    }
}
