<?php

namespace Modules\Product\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductImage;
use Modules\Common\Helpers\UploadHelper;

class ProductService
{
    use UploadHelper;

    public function findAll(array $data = [], array $relations = []): Collection|LengthAwarePaginator
    {
        $query = Product::query()
            ->with($relations)
            ->filter($data)
            ->orderBy('order')
            ->latest();

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Product
    {
        return Product::with($relations)->findOrFail($id);
    }

    public function active(array $relations = [], ?int $branchId = null): Collection
    {
        return Product::active()
            ->with($relations)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('order')
            ->get();
    }

    public function save(array $data, array $images = []): Product
    {
        DB::beginTransaction();
        try {
            $record = Product::create($data);

            foreach ($images as $image) {
                $fileName = $this->upload($image, config('product.images_folder'));
                $record->images()->create(['image' => $fileName]);
            }

            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data, array $images = []): Product
    {
        DB::beginTransaction();
        try {
            $record = $this->findById($id);
            $record->update($data);

            foreach ($images as $image) {
                $fileName = $this->upload($image, config('product.images_folder'));
                $record->images()->create(['image' => $fileName]);
            }

            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteImage(int $imageId): void
    {
        $image = ProductImage::findOrFail($imageId);
        File::delete(public_path('uploads/' . config('product.images_folder') . '/' . $this->getImageName(config('product.images_folder'), $image->getOriginal('image'))));
        $image->delete();
    }

    public function activate(int $id): void
    {
        $record = $this->findById($id);
        $record->is_active = !$record->is_active;
        $record->save();
    }

    public function delete(int $id): void
    {
        $record = $this->findById($id, ['images']);

        foreach ($record->images as $image) {
            File::delete(public_path('uploads/' . config('product.images_folder') . '/' . $this->getImageName(config('product.images_folder'), $image->getOriginal('image'))));
        }

        $record->delete();
    }
}
