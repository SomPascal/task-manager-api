<?php

namespace App\Dtos;

use App\Constants\Pagination;

final readonly class PaginationDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $page = Pagination::DEFAULT_PAGE,
        public int $perPage = Pagination::DEFAULT_PER_PAGE,
        public string $pageName = Pagination::DEFAULT_PAGE_NAME
    )
    {
        //
    }
}
