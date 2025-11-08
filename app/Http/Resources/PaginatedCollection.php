<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatedCollection extends ResourceCollection
{
    public $resource;
    protected string $resourceKey;

    public function __construct(mixed $resource, string $collects, ?string $resourceKey = null, ?string $wrap = null)
    {
        $this->resourceKey = $resourceKey ?? 'data';
        self::$wrap = $wrap;
        $this->collects = $collects;

        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pagination = $this->resource instanceof LengthAwarePaginator ? $this->resource : null;

        return [
            $this->resourceKey => $this->collection,
            'links' => $this->paginationLinks($pagination),
            'meta' => $this->paginationMeta($pagination)
        ];
    }

    protected function paginationLinks(?LengthAwarePaginator $pagination): array
    {
        if (!$pagination) {
            return [
                'first' => null,
                'last' => null,
                'prev' => null,
                'next' => null,
                'prev_page_number' => null,
                'next_page_number' => null,
            ];
        }

        $prevPageUrl = $pagination->previousPageUrl();
        $nextPageUrl = $pagination->nextPageUrl();

        // Extract page number from URL if available
        $prevPageNumber = $prevPageUrl ? (int) parse_url($prevPageUrl, PHP_URL_QUERY) : null;
        $nextPageNumber = $nextPageUrl ? (int) parse_url($nextPageUrl, PHP_URL_QUERY) : null;

        // More robust parsing for page number from URL
        $getParam = function(?string $url, string $param = 'page') {
            if (!$url) return null;
            $queryString = parse_url($url, PHP_URL_QUERY);
            if ($queryString) {
                parse_str($queryString, $params);
                return isset($params[$param]) ? (int) $params[$param] : null;
            }
            return null;
        };

        return [
            'first' => $pagination->url(1),
            'last' => $pagination->url($pagination->lastPage()),
            'prev' => $prevPageUrl,
            'next' => $nextPageUrl,
            // Add the page numbers here
            'prev_page_number' => $getParam($prevPageUrl, 'page'),
            'next_page_number' => $getParam($nextPageUrl, 'page'),
        ];
    }

    /**
     * Helper to get structured pagination metadata.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator|null $pagination
     * @return array
     */
    protected function paginationMeta(?LengthAwarePaginator $pagination): array
    {
        if (!$pagination) {
            return [];
        }

        return [
            'current_page' => $pagination->currentPage(),
            'from' => $pagination->firstItem(),
            'last_page' => $pagination->lastPage(),
            'path' => $pagination->path(),
            'per_page' => $pagination->perPage(),
            'to' => $pagination->lastItem(),
            'total' => $pagination->total(),
        ];
    }
}
