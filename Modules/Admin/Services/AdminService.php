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
        $query = $this->model->with($relations)->orderByDesc($orderBy);
        return getCaseCollection($query, $data);
    }
}
