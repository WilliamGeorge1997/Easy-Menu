<?php

namespace Modules\Branch\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Branch\Models\Branch;
use Modules\Admin\Models\Admin;
use Spatie\Permission\Models\Role;
use Modules\Common\Helpers\UploadHelper;

class BranchService
{
    use UploadHelper;

    public function findAll(array $data = [], array $relations = []): Collection|LengthAwarePaginator
    {
        $query = Branch::query()->with($relations)->filter($data)->latest();
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Branch
    {
        return Branch::with($relations)->findOrFail($id);
    }

    public function active(array $relations = []): Collection
    {
        return Branch::active()->with($relations)->get();
    }

    public function save(array $data): Branch
    {
        DB::beginTransaction();
        try {
            if (request()->hasFile('image')) {
                $data['image'] = $this->upload(request()->file('image'), 'branches');
            }
            $data['slug'] = $this->generateUniqueSlug($data['slug']);

            // Extract admin fields from request (not from DTO data array)
            $adminData = [
                'name'      => request('admin_name'),
                'email'     => request('admin_email'),
                'phone'     => request('admin_phone'),
                'password'  => bcrypt(request('admin_password')),
                'is_active' => 1,
                'lang'      => config('app.locale', 'en'),
            ];

            $branch = Branch::create($data);

            // Create the Branch Manager admin and assign branch + role
            $adminData['branch_id'] = $branch->id;
            $admin = Admin::create($adminData);
            $role = Role::where('name', config('admin.roles.branch_manager'))
                ->where('guard_name', 'admin')
                ->firstOrFail();
            $admin->assignRole($role);

            DB::commit();
            return $branch;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateUniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug      = $base;
        $counter   = 2;

        while (
            Branch::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    public function update(int $id, array $data): Branch
    {
        DB::beginTransaction();
        try {
            $record = $this->findById($id);
            if (request()->hasFile('image')) {
                if ($record->getRawOriginal('image')) {
                    File::delete(public_path('uploads/branches/' . $this->getImageName('branches', $record->image)));
                }
                $data['image'] = $this->upload(request()->file('image'), 'branches');
            }
            $record->update($data);
            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function activate(int $id): void
    {
        $record = $this->findById($id);
        $record->is_active = !$record->is_active;
        $record->save();
    }

    public function delete(int $id): void
    {
        $record = $this->findById($id);
        if ($record->getRawOriginal('image')) {
            File::delete(public_path('uploads/branches/' . $this->getImageName('branches', $record->image)));
        }
        $record->delete();
    }
}
