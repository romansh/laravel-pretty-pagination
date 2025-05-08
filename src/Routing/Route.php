<?php

namespace CTSoft\Laravel\PrettyPagination\Routing;

use CTSoft\Laravel\PrettyPagination\Http\Middleware\SetPrettyPagination;
use Illuminate\Support\Facades\Route as Router; // Alias for Laravel's Route facade
use Illuminate\Support\Str;

class Route // This class would be used to define the macro, e.g. via a service provider
{
    /**
     * Add the route as pagination-aware.
     * This method returns a callable that will be registered as a route macro.
     *
     * @return callable
     */
    public function paginate(): callable
    {
        /**
         * @param string|null $prefix The prefix for the page parameter in the URL (e.g., 'p' for /p/2). Defaults to 'page'.
         * @param bool $addTrailingSlash If true, generated pagination URLs will have a trailing slash.
         */
        return function (?string $prefix = 'page', bool $addTrailingSlash = false): void {
            /** @var BaseRouteLaravel $this refers to the Illuminate\Routing\Route instance */

            // Apply the middleware, passing the trailing slash preference as a string parameter.
            $this->middleware(SetPrettyPagination::class . ':' . ($addTrailingSlash ? 'true' : 'false'));

            $route = clone $this;

            $route->uri = sprintf('%s%s{page}',
                Str::finish($route->uri, '/'),
                $prefix ? Str::finish($prefix, '/') : ''
            );

            $route->name('.page');

            Router::getRoutes()->add($route);
        };
    }
}