<?php

namespace Modules\Admin\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Admin\Models\Admin;
use Modules\Common\Helpers\UploadHelper;
use Spatie\Permission\Models\Role;

class AdminService
{
    use UploadHelper;

    private Admin $model;

    public function __construct()
    {
        $this->model = new Admin;
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

    public function save(array $data): Admin
    {
        DB::beginTransaction();
        try {
            $roleId = $data['role_id'] ?? null;
            unset($data['role_id']);

            if (request()->hasFile('image')) {
                $data['image'] = $this->upload(request()->file('image'), 'admins');
            }

            $admin = Admin::create($data);

            if ($roleId) {
                $role = Role::query()
                    ->where('id', $roleId)
                    ->where('guard_name', 'admin')
                    ->firstOrFail();
                $admin->assignRole($role);
            }

            DB::commit();

            return $admin;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Admin
    {
        $admin = $this->findById($id);
        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (request()->hasFile('image')) {
            if ($admin->getRawOriginal('image')) {
                File::delete(public_path('uploads/admins/'.$this->getImageName('admins', $admin->image)));
            }
            $data['image'] = $this->upload(request()->file('image'), 'admins');
        }

        $admin->update($data);

        return $admin;
    }

    public function activate(int $id): void
    {
        $admin = $this->findById($id);
        $admin->is_active = ! $admin->is_active;
        $admin->save();
    }

    public function delete(int $id): void
    {
        $admin = $this->findById($id);
        $admin->delete();
    }
}
