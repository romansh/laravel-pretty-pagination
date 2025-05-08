<?php

namespace CTSoft\Laravel\PrettyPagination\Pagination;

use Closure;
use Illuminate\Pagination\Paginator as BasePaginator;

class Paginator extends BasePaginator
{
    /**
     * Indicates if trailing slashes should be added to pagination URLs.
     * @var bool
     */
    protected static bool $resolveTrailingSlashes = false;

    /**
     * The resolver for the current request parameters.
     *
     * @var \Closure|null
     */
    protected static $currentParametersResolver;

    /**
     * Set whether to add trailing slashes to pagination URLs.
     *
     * This method is typically called by the SetPrettyPagination middleware
     * based on the route definition.
     *
     * @param bool $resolve
     * @return void
     */
    public static function resolveTrailingSlashes(bool $resolve): void
    {
        static::$resolveTrailingSlashes = $resolve;
    }

    /**
     * Check if trailing slashes should be added to pagination URLs.
     *
     * This method is used by the GeneratePrettyUrl trait.
     *
     * @return bool
     */
    public static function shouldAddTrailingSlashes(): bool
    {
        return static::$resolveTrailingSlashes;
    }

    /**
     * Set the current request parameters resolver callback.
     *
     * @param  \Closure  $resolver
     * @return void
     */
    public static function currentParametersResolver(Closure $resolver): void
    {
        static::$currentParametersResolver = $resolver;
    }

    /**
     * Resolve the current parameters or return the default value.
     * This method is called by LengthAwarePaginator's static proxy.
     *
     * @param  array  $default
     * @return array
     */
    public static function resolveCurrentParameters(array $default = []): array
    {
        if (isset(static::$currentParametersResolver)) {
            return call_user_func(static::$currentParametersResolver);
        }
        return $default;
    }

}