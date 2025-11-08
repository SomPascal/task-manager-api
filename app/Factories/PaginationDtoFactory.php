<?php

namespace App\Factories;

use App\Dtos\PaginationDto;
use App\Http\Requests\PaginationRequest;

class PaginationDtoFactory
{
    public static function fromRequest(PaginationRequest $request): PaginationDto
    {
        return new PaginationDto(
            page: $request->validated('page'),
            perPage: $request->validated('per_page')
        );
    }
}
