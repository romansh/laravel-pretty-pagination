<?php

namespace CTSoft\Laravel\PrettyPagination\Http\Middleware;

use Closure;
use CTSoft\Laravel\PrettyPagination\Pagination\LengthAwarePaginator as PrettyLengthAwarePaginator;
use CTSoft\Laravel\PrettyPagination\Pagination\Paginator as PrettyPaginator;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as BaseLengthAwarePaginator;
use Illuminate\Pagination\Paginator as BasePaginator;

class SetPrettyPagination
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $addTrailingSlashParam Parameter from route definition (e.g., 'true' or 'false')
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $addTrailingSlashParam = 'false')
    {
        $container = Container::getInstance();

        $container->bind(BaseLengthAwarePaginator::class, PrettyLengthAwarePaginator::class);
        $container->bind(BasePaginator::class, PrettyPaginator::class);

        // Configure the PrettyPaginator with resolvers for the current request
        PrettyPaginator::currentPathResolver(function () use ($request) {
            $route = $request->route();
            return $route ? preg_replace('/\.page$/', '', $route->getName()) : $request->path();
        });

        PrettyPaginator::currentParametersResolver(function () use ($request) {
            $route = $request->route();
            return $route ? collect($route->originalParameters())->forget('page')->all() : [];
        });

        PrettyPaginator::currentPageResolver(function () use ($request) {
            $route = $request->route();
            $pageFromRoute = $route ? $request->route('page') : null;
            return max((int)($pageFromRoute ?: $request->input('page', 1)), 1);
        });

        // Set the trailing slash preference based on the middleware parameter
        PrettyPaginator::resolveTrailingSlashes($addTrailingSlashParam === 'true');

        return $next($request);
    }
}