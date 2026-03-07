@extends('common::layouts.master')

@section('title', __('dashboard/branches.branches'))

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
                <h5 class="mb-0">{{ __('dashboard/branches.branches') }}</h5>
                @can('create', \Modules\Branch\Models\Branch::class)
                    <a href="{{ route('admin.branches.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i> {{ __('dashboard/branches.create_branch') }}
                    </a>
                @endcan
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard/branches.title') }}</th>
                            <th>{{ __('dashboard/branches.slug') }}</th>
                            <th>{{ __('dashboard/branches.phone') }}</th>
                            <th>{{ __('dashboard/branches.status') }}</th>
                            <th>{{ __('dashboard/branches.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($branches as $branch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($branch->getRawOriginal('image'))
                                            <img src="{{ $branch->image }}" alt="{{ $branch->getTranslation('title','en') }}"
                                                width="45" height="45" class="rounded-circle object-fit-cover flex-shrink-0" />
                                        @else
                                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-label-secondary flex-shrink-0" style="width:45px;height:45px;">
                                                <i class="bx bx-store fs-4"></i>
                                            </span>
                                        @endif
                                        <div>
                                            <span class="fw-semibold">{{ $branch->getTranslation('title', 'en') }}</span>
                                            <span class="text-muted mx-1">-</span>
                                            <span>{{ $branch->getTranslation('title', 'ar') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><code>{{ $branch->slug }}</code></td>
                                <td>{{ $branch->phone ?? '-' }}</td>
                                <td>
                                    @can('activate', $branch)
                                        <form action="{{ route('admin.branches.activate', $branch->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="badge border-0 btn-loader {{ $branch->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                                {{ $branch->is_active ? __('dashboard/branches.active') : __('dashboard/branches.inactive') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge {{ $branch->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $branch->is_active ? __('dashboard/branches.active') : __('dashboard/branches.inactive') }}
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
                                            @can('update', $branch)
                                                <a class="dropdown-item" href="{{ route('admin.branches.edit', $branch->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> {{ __('dashboard/branches.edit_branch') }}
                                                </a>
                                            @endcan
                                            @can('delete', $branch)
                                                <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST"
                                                    onsubmit="return confirm('{{ __('dashboard/branches.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger btn-loader">
                                                        <i class="bx bx-trash me-1"></i> {{ __('dashboard/branches.delete_branch') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">{{ __('dashboard/branches.no_branches') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($branches instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    {{ $branches->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="content-backdrop fade"></div>
</div>
@endsection
