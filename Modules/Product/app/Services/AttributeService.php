<?php

namespace Modules\Product\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Attribute;
use Modules\Product\Models\Product;

class AttributeService
{
    public function findByProduct(Product $product, array $data = [], array $relations = ['values']): Collection|LengthAwarePaginator
    {
        $query = Attribute::query()
            ->where('product_id', $product->id)
            ->with($relations)
            ->filter($data)
            ->orderBy('order')
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Attribute
    {
        return Attribute::with($relations)->findOrFail($id);
    }

    public function save(array $data): Attribute
    {
        DB::beginTransaction();
        try {
            $attribute = Attribute::create([
                'product_id' => $data['product_id'],
                'branch_id' => $data['branch_id'],
                'title' => $data['title'],
                'required' => $data['required'] ?? false,
                'multi_select' => $data['multi_select'] ?? false,
                'override_price' => $data['override_price'] ?? false,
                'is_active' => $data['is_active'] ?? 1,
                'order' => $data['order'] ?? 1,
                'type' => $data['type'] ?? 'size',
            ]);

            $this->syncValues($attribute, $data['values'] ?? []);

            DB::commit();

            return $attribute;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Attribute
    {
        DB::beginTransaction();
        try {
            $attribute = $this->findById($id);
            $attribute->update([
                'title' => $data['title'],
                'required' => $data['required'] ?? false,
                'multi_select' => $data['multi_select'] ?? false,
                'override_price' => $data['override_price'] ?? false,
                'is_active' => $data['is_active'] ?? 1,
                'order' => $data['order'] ?? 1,
                'type' => $data['type'] ?? 'size',
            ]);

            $attribute->values()->delete();
            $this->syncValues($attribute, $data['values'] ?? []);

            DB::commit();

            return $attribute;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function activate(int $id): void
    {
        $attribute = $this->findById($id);
        $attribute->is_active = ! $attribute->is_active;
        $attribute->save();
    }

    public function delete(int $id): void
    {
        $attribute = $this->findById($id);
        $attribute->delete();
    }

    private function syncValues(Attribute $attribute, array $values): void
    {
        foreach ($values as $value) {
            $attribute->values()->create([
                'value' => $value['value'],
                'price' => $value['price'] ?? 0.00,
            ]);
        }
    }
}
