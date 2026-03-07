<?php

namespace Modules\Category\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Category\Models\Category;
use Modules\Common\Helpers\UploadHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    use UploadHelper;

    /**
     * Get all categories with optional filtering and pagination.
     */
    public function findAll(array $data = [], array $relations = []): Collection|LengthAwarePaginator
    {
        $query = Category::query()
            ->with($relations)
            ->filter($data)
            ->orderBy('order')
            ->latest();

        return getCaseCollection($query, $data);
    }

    /**
     * Find a single category by ID.
     */
    public function findById(int $id, array $relations = []): Category
    {
        return Category::with($relations)->findOrFail($id);
    }

    /**
     * Get all active categories, optionally filtered by branch.
     */
    public function active(array $relations = [], ?int $branchId = null): Collection
    {
        return Category::active()
            ->with($relations)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('order')
            ->get();
    }

    /**
     * Create a new category.
     */
    public function save(array $data): Category
    {
        DB::beginTransaction();
        try {
            if (request()->hasFile('image')) {
                $data['image'] = $this->upload(request()->file('image'), config('category.images_folder'));
            }
            $record = Category::create($data);
            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing category.
     */
    public function update(int $id, array $data): Category
    {
        DB::beginTransaction();
        try {
            $record = $this->findById($id);
            if (request()->hasFile('image')) {
                if ($record->getOriginal('image')) {
                    File::delete(public_path('uploads/' . config('category.images_folder') . '/' . $this->getImageName(config('category.images_folder'), $record->getOriginal('image'))));
                }
                $data['image'] = $this->upload(request()->file('image'), config('category.images_folder'));
            }
            $record->update($data);
            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Toggle the is_active status of a category.
     */
    public function activate(int $id): void
    {
        $record = $this->findById($id);
        $record->is_active = !$record->is_active;
        $record->save();
    }

    /**
     * Delete a category and its image.
     */
    public function delete(int $id): void
    {
        $record = $this->findById($id);
        if ($record->getOriginal('image')) {
            File::delete(public_path('uploads/' . config('category.images_folder') . '/' . $this->getImageName(config('category.images_folder'), $record->getOriginal('image'))));
        }
        $record->delete();
    }
}
