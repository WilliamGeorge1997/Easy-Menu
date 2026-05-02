<?php

namespace Modules\Product\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Common\Helpers\UploadHelper;
use Modules\Product\Models\Addon;

class AddonService
{
    use UploadHelper;

    public function findAll(array $data = [], array $relations = []): Collection|LengthAwarePaginator
    {
        $query = Addon::query()
            ->with($relations)
            ->filter($data)
            ->latest();

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Addon
    {
        return Addon::with($relations)->findOrFail($id);
    }

    public function activeByBranch(int $branchId): Collection
    {
        return Addon::query()
            ->active()
            ->where('branch_id', $branchId)
            ->with(['values' => fn ($query) => $query->where('is_active', 1)])
            ->latest()
            ->get();
    }

    public function save(array $data): Addon
    {
        DB::beginTransaction();
        try {
            $addon = Addon::create([
                'branch_id' => $data['branch_id'],
                'title' => $data['title'],
                'is_active' => $data['is_active'],
            ]);

            $this->syncValues($addon, $data['values']);

            DB::commit();

            return $addon;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Addon
    {
        DB::beginTransaction();
        try {
            $addon = $this->findById($id, ['values']);
            $addon->update([
                'branch_id' => $data['branch_id'],
                'title' => $data['title'],
                'is_active' => $data['is_active'],
            ]);

            $imagesToKeep = collect($data['values'])
                ->map(function (array $value) {
                    if (! empty($value['image'])) {
                        return null;
                    }

                    return $this->extractImageName($value['existing_image'] ?? null);
                })
                ->filter()
                ->values()
                ->all();

            $this->deleteValueImages($addon->values, $imagesToKeep);
            $addon->values()->delete();
            $this->syncValues($addon, $data['values']);

            DB::commit();

            return $addon;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function activate(int $id): void
    {
        $record = $this->findById($id);
        $record->is_active = ! $record->is_active;
        $record->save();
    }

    public function delete(int $id): void
    {
        $addon = $this->findById($id, ['values']);
        $this->deleteValueImages($addon->values);
        $addon->delete();
    }

    private function syncValues(Addon $addon, array $values): void
    {
        foreach ($values as $value) {
            $image = null;
            if (! empty($value['image'])) {
                $image = $this->upload($value['image'], config('product.addon_values_images_folder'));
            } elseif (! empty($value['existing_image'])) {
                $image = $this->extractImageName($value['existing_image']);
            }

            $addon->values()->create([
                'title' => $value['title'],
                'price' => $value['price'],
                'is_active' => $value['is_active'],
                'image' => $image,
            ]);
        }
    }

    private function deleteValueImages(Collection $values, array $imagesToKeep = []): void
    {
        $keep = collect($imagesToKeep)->filter()->flip();

        foreach ($values as $value) {
            if (! empty($value->getOriginal('image'))) {
                if ($keep->has($value->getOriginal('image'))) {
                    continue;
                }

                File::delete(public_path('uploads/'.config('product.addon_values_images_folder').'/'.$this->getImageName(config('product.addon_values_images_folder'), $value->getOriginal('image'))));
            }
        }
    }

    private function extractImageName(?string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        if (! str_contains($imagePath, '/')) {
            return $imagePath;
        }

        if (str_contains($imagePath, config('product.addon_values_images_folder').'/')) {
            return $this->getImageName(config('product.addon_values_images_folder'), $imagePath);
        }

        return basename(parse_url($imagePath, PHP_URL_PATH) ?: $imagePath);
    }
}
