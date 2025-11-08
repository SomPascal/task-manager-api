<?php

namespace App\Repositories;

use App\Dtos\PaginationDto;
use App\Exceptions\CategoryNotDeletedException;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function filter(PaginationDto $dto, array $with = []): LengthAwarePaginator
    {
        return Category::with($with)->paginate(
            perPage: $dto->perPage,
            page: $dto->page
        );
    }

    public function findOrFail(int $categoryId, array $with = []): Category
    {
        return Category::with($with)
        ->where('id', $categoryId)
        ->firstOrFail();
    }

    public function findOrFailWithTrashed(int $categoryId, array $with = []): Category
    {
        return Category::withTrashed()->with($with)
        ->where('id', $categoryId)
        ->firstOrFail();
    }

    public function create(string $categoryName): Category
    {
        return Category::create([
            'name' => $categoryName,
            'user_id' => auth()->id()
        ]);
    }

    public function update(int $categoryId, string $categoryName): Category
    {
        $category = $this->findOrFail($categoryId);

        $category->update([
            'name' => $categoryName
        ]);
        $category->refresh();

        return $category;
    }

    public function pin(int $categoryId): Category
    {
        $category = $this->findOrFail($categoryId);

        if ($category->pinned == true) {
            return $category;
        }

        return DB::transaction(function () use ($category): Category {
            $category->update([
                'pinned' => true
            ]);

            Category::where('id', '!=', $category->id)
            ->where('user_id', auth()->id())
            ->where('pinned', true)
            ->update(['pinned' => false]);

            $category->refresh();

            return $category;
        });
    }

    public function unpin(int $categoryId): Category
    {
        $category = $this->findOrFail($categoryId);

        $category->update([
            'pinned' => false
        ]);
        $category->refresh();

        return $category;
    }

    public function existsForUser(string $categoryName, int $userId, ?string $ignoreName = null): bool
    {
        $query = Category::where('name', '=', $categoryName)
        ->where('user_id', $userId);

        if ($ignoreName !== null) {
            $query->where('name', '!=', $categoryName);
        }

        return $query->exists();
    }

    public function delete(int $categoryId): bool
    {
        $category = $this->findOrFail($categoryId);
        return (bool) $category->delete();
    }

    /**
     * @param int $categoryId
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws CategoryNotDeletedException
     * @return bool
     */
    public function restore(int $categoryId): bool
    {
        $category = $this->findOrFailWithTrashed($categoryId);

        throw_if(
            condition: ! $category->trashed(),
            exception: CategoryNotDeletedException::class
        );

        return (bool) $category->restore();
    }
}
