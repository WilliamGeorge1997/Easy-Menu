@extends('common::layouts.master')

@section('title', __('dashboard/categories.categories'))

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('dashboard/categories.categories') }}</h5>
                @can('create', \Modules\Category\Models\Category::class)
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i> {{ __('dashboard/categories.create_category') }}
                    </a>
                @endcan
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard/categories.category') }}</th>
                            @can('viewAny', \Modules\Category\Models\Category::class)
                                @if(auth('admin')->user()->hasRole(config('category.roles.super_admin')))
                                    <th>{{ __('dashboard/categories.branch') }}</th>
                                @endif
                            @endcan
                            <th>{{ __('dashboard/categories.order') }}</th>
                            <th>{{ __('dashboard/categories.status') }}</th>
                            <th>{{ __('dashboard/categories.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($category->getRawOriginal('image'))
                                            <img src="{{ $category->image }}"
                                                alt="{{ $category->getTranslation('title', 'en') }}"
                                                width="45" height="45"
                                                class="rounded-circle object-fit-cover flex-shrink-0" />
                                        @else
                                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-label-secondary flex-shrink-0" style="width:45px;height:45px;">
                                                <i class="bx bx-category fs-4"></i>
                                            </span>
                                        @endif
                                        <div>
                                            <span class="fw-semibold">{{ $category->getTranslation('title', 'en') }}</span>
                                            <span class="text-muted mx-1">-</span>
                                            <span>{{ $category->getTranslation('title', 'ar') }}</span>
                                        </div>
                                    </div>
                                </td>
                                @if(auth('admin')->user()->hasRole(config('category.roles.super_admin')))
                                    <td>{{ $category->branch?->getTranslation('title', app()->getLocale()) ?? '-' }}</td>
                                @endif
                                <td>{{ $category->order }}</td>
                                <td>
                                    @can('activate', $category)
                                        <form action="{{ route('admin.categories.activate', $category->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="badge border-0 btn-loader {{ $category->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                                {{ $category->is_active ? __('dashboard/categories.active') : __('dashboard/categories.inactive') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge {{ $category->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $category->is_active ? __('dashboard/categories.active') : __('dashboard/categories.inactive') }}
                                        </span>
                                    @endcan
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('update', $category)
                                                <a class="dropdown-item" href="{{ route('admin.categories.edit', $category->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> {{ __('dashboard/categories.edit_category') }}
                                                </a>
                                            @endcan
                                            @can('delete', $category)
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                                    onsubmit="return confirm('{{ __('dashboard/categories.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger btn-loader">
                                                        <i class="bx bx-trash me-1"></i> {{ __('dashboard/categories.delete_category') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">{{ __('dashboard/categories.no_categories') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

    </div>

    <div class="content-backdrop fade"></div>
</div>
@endsection
