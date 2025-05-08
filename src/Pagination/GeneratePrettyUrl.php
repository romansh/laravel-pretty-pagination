<?php

namespace CTSoft\Laravel\PrettyPagination\Pagination;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
// Import your custom Paginator to access the static shouldAddTrailingSlashes method
use CTSoft\Laravel\PrettyPagination\Pagination\Paginator as PrettyPaginator;


trait GeneratePrettyUrl
{
    /**
     * The parameters to assign to all URLs.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Get the URL for a given page number.
     *
     * @param int $page
     * @return string
     */
    public function url($page)
    {
        $url = $this->getPageUrl(max($page, 1));
        $url = $this->addQueryString($url);
        $url .= $this->buildFragment(); // Assumes buildFragment() exists if fragment is used

        return $url;
    }

    /**
     * Get the base URL for a page, before query string and fragment.
     *
     * @param int $page
     * @return string
     */
    protected function getPageUrl(int $page): string
    {
        // $this->path() will call the path() method on the Paginator instance,
        // which should use the Paginator::currentPathResolver() if set by middleware.
        $routeBaseName = $this->path();
        $parameters = $this->parameters(); // Get parameters resolved via Paginator::currentParametersResolver()

        $routeName = $routeBaseName;

        if ($page > 1) {
            $routeName .= '.page'; // Convention for paged routes, e.g., 'items.index.page'
            $parameters['page'] = $page;
        }

        // Generate the URL using Laravel's route helper.
        // The 'true' argument generates an absolute URL.
        $url = URL::route($routeName, $parameters, true);

        // Add a trailing slash if configured and not already present,
        // and if the URL doesn't already contain a query string or fragment part.
        if (PrettyPaginator::shouldAddTrailingSlashes()) {
            if (!Str::endsWith($url, '/') && strpos($url, '?') === false && strpos($url, '#') === false) {
                $url .= '/';
            }
        }

        return $url;
    }

    /**
     * Add the query string to an URL.
     *
     * @param string $url
     * @return string
     */
    protected function addQueryString(string $url): string
    {
        // $this->query should be an array of query parameters to append,
        // typically set via the appends() method on the paginator instance.
        if (empty($this->query)) {
            return $url;
        }

        $queryString = Arr::query($this->query); // Arr::query handles array to query string conversion

        if (empty($queryString)) {
            return $url;
        }

        return sprintf(
            '%s%s%s',
            $url,
            Str::contains($url, '?') ? '&' : '?',
            $queryString
        );
    }

    /**
     * Get the parameters for paginator generated URLs.
     *
     * @return array
     */
    protected function parameters(): array
    {
        if (!isset($this->parameters)) {
            // static::resolveCurrentParameters() is called on the class using the trait (LengthAwarePaginator).
            // LengthAwarePaginator's implementation delegates to PrettyPaginator::resolveCurrentParameters().
            $this->parameters = static::resolveCurrentParameters();
        }
        return $this->parameters;
    }

    /**
     * Build the fragment portion of the URL.
     *
     * @return string
     */
    protected function buildFragment(): string
    {
        // $this->fragment should be set via the fragment() method on the paginator instance.
        return $this->fragment ? '#' . $this->fragment : '';
    }
}