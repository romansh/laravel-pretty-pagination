<?php

namespace CTSoft\Laravel\PrettyPagination\Pagination;

use Closure;
use Illuminate\Pagination\LengthAwarePaginator as BaseLengthAwarePaginator;

use CTSoft\Laravel\PrettyPagination\Pagination\Paginator as Paginator;

class LengthAwarePaginator extends BaseLengthAwarePaginator
{
    use GeneratePrettyUrl;

    /**
     * Resolve the current parameters or return the default value.
     * Delegates to the custom PrettyPaginator's static method.
     *
     * @param array $default
     * @return array
     */
    public static function resolveCurrentParameters(array $default = []): array
    {
        return Paginator::resolveCurrentParameters($default);
    }

    /**
     * Set the current request parameters resolver callback.
     * Delegates to the custom PrettyPaginator's static method.
     *
     * @param Closure $resolver
     * @return void
     */
    public static function currentParametersResolver(Closure $resolver): void
    {
        Paginator::currentParametersResolver($resolver);
    }
}