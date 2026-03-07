<?php

namespace Modules\Admin\Services;

use Modules\Admin\Models\Admin;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminService
{
    private Admin $model;

    public function __construct()
    {
        $this->model = new Admin();
    }

    public function findAll(array $data = [], array $relations = [], string $orderBy = 'id'): Collection|LengthAwarePaginator
    {
        $query = $this->model->with($relations)
            ->where('id', '!=', auth('admin')->id())
            ->orderByDesc($orderBy);
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Admin
    {
        return Admin::with($relations)->findOrFail($id);
    }

    public function update(int $id, array $data): Admin
    {
        $admin = $this->findById($id);
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $admin->update($data);
        return $admin;
    }

    public function activate(int $id): void
    {
        $admin = $this->findById($id);
        $admin->is_active = !$admin->is_active;
        $admin->save();
    }

    public function delete(int $id): void
    {
        $admin = $this->findById($id);
        $admin->delete();
    }
}
