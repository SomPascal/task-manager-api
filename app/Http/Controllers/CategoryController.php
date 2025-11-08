<?php

namespace App\Http\Controllers;

use App\Exceptions\CategoryNotDeletedException;
use App\Factories\PaginationDtoFactory;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PaginatedCollection;
use App\Repositories\CategoryRepository;
use App\Rules\UniqueCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {
        $this->channel = 'category';
    }

    public function index(PaginationRequest $request): JsonResponse
    {
        $paginationDto = PaginationDtoFactory::fromRequest($request);
        $paginatedCategories = $this->categoryRepository->filter($paginationDto);

        return $this->success(
            message: 'Categories successfuly retrieved',

            data: new PaginatedCollection(
                resource: $paginatedCategories,
                collects: CategoryResource::class,
                resourceKey: 'categories'
            )
        );
    }

    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new UniqueCategory()
            ]
        ]);

        try {
            $createdCategory = $this->categoryRepository->create($validated['name']);

            return $this->success(
                message: 'Category successuly created',
                data: new CategoryResource($createdCategory),
                status: 201
            );
        } catch (\Throwable $th) {
            $this->logException(
                message: 'Could not create a category',
                th: $th
            );

            return $this->failServerError();
        }

    }

    public function show(int $categoryId): JsonResponse
    {
        try {
            $category = $this->categoryRepository->findOrFail($categoryId);
    
            return $this->success(
                message: 'Category successfuly restrieved',
                data: new CategoryResource($category)
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No category found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not show a category', $th);

            return $this->failServerError();
        }
    }

    public function update(Request $request, int $categoryId): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                new UniqueCategory($request->input('name'))
            ]
        ]);

        try {
            $updatedCategory = $this->categoryRepository->update(
                $categoryId,
                $validated['name']
            );
    
            return $this->success(
                message: 'Category successfuly updated',
                data: new CategoryResource($updatedCategory),
                status: 200
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No category found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not update this category', $th);
            return $this->failServerError();
        }

    }

    public function pin(int $categoryId): JsonResponse
    {
        try {
            $pinnedCategory = $this->categoryRepository->pin($categoryId);
    
            return $this->success(
                message: 'Category successfuly pinned',
                data: new CategoryResource($pinnedCategory)
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No category found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException(
                message: 'Could not pin a category',
                th: $th
            );

            return $this->failServerError();
        }
    }

    public function unpin(int $categoryId): JsonResponse
    {
        try {
            $pinnedCategory = $this->categoryRepository->unpin($categoryId);
    
            return $this->success(
                message: 'Category successfuly unpinned',
                data: new CategoryResource($pinnedCategory)
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No category found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException(
                message: 'Could not unpin a category',
                th: $th
            );

            return $this->failServerError();
        }
    }

    public function delete(int $categoryId): JsonResponse
    {
        try {
            $this->categoryRepository->delete($categoryId);
    
            return $this->success(
                message: 'Category successfuly deleted'
            );
        } catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No category found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not delete a category', $th);

            return $this->failServerError();
        }
    }

    public function restore(int $categoryId): JsonResponse
    {
        try {
            $this->categoryRepository->restore($categoryId);
    
            return $this->success(
                message: 'Category successfuly restored'
            );
        } catch (CategoryNotDeletedException $e) {
            return $this->fail(
                message: 'This category is not deleted',
                status: 409
            );
        }
        catch (ModelNotFoundException $e) {
            return $this->fail(
                message: 'No category found',
                status: 404
            );
        }
        catch (\Throwable $th) {
            $this->logException('Could not restore a category', $th);

            return $this->failServerError();
        }
    }
}
