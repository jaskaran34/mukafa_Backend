<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Cache\CacheManager as Cache;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Routing\Middleware\ValidateSignature as BaseMiddleware;

class ValidateSignature extends BaseMiddleware
{
    /**
     * Cache manager
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Create a new ValidateSignature instance.
     *
     * @param  \Illuminate\Cache\CacheManager $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  mixed ...$args
     * @return \Illuminate\Http\Response
     *
     */
    public function handle($request, Closure $next, ...$args)
    {
        $consume = $args[0] ?? false;

        if (($consume && $this->signatureConsumed($request)) || !$request->hasValidSignature()) {
            throw new InvalidSignatureException;
        }

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if ($consume && $response->isSuccessful()) {
            $this->consumeSignature($request);
        }

        return $response;
    }

    /**
     * Checks if the signature was consumed
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function signatureConsumed(Request $request)
    {
        return $this->cache->driver('file')->has($this->cacheKey($request));
    }

    /**
     * Consumes the signature, marking it as unavailable
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function consumeSignature(Request $request)
    {
        // Get the expiration time from the request
        $expiresAt = Carbon::createFromTimestamp($request->query('expires'));
    
        // Determine the remaining time from now until expiration
        $ttl = Carbon::now()->diffInSeconds($expiresAt, false);
    
        // If the TTL is a negative number, it means the expiration time is in the past
        // So we'll set the TTL to 0, which will expire the cache item immediately
        if ($ttl < 0) {
            $ttl = 0;
        }
    
        // Store the signature in the cache with the calculated TTL
        $this->cache->driver('file')->put($this->cacheKey($request), '', $ttl);
    }    

    /**
     * Return the cache Key to check
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function cacheKey(Request $request)
    {
        return 'consumable|' . $request->query('signature');
    }
}