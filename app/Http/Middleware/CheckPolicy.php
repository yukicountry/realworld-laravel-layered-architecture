<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class CheckPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $policyName): Response
    {
        $policyFunc = 'App\\Http\\Auth\\' . $policyName;
        if (!is_callable($policyFunc)) {
            throw new InvalidArgumentException("Policy function {$policyFunc} is not callable.");
        }

        // check policy
        $policyFunc($request);

        return $next($request);
    }
}
