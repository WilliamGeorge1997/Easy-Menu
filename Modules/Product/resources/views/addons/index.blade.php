@extends('common::layouts.master')

@section('title', __('dashboard/addons.addons'))

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-check-circle fs-5"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-error-circle fs-5"></i>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm bg-label-primary rounded d-flex align-items-center justify-content-center">
                        <i class="bx bx-list-ul"></i>
                    </span>
                    {{ __('dashboard/addons.addons') }}
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/addons.manage_groups') }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('dashboard/addons.addons') }}</h5>
                <a href="{{ route('admin.addons.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i>
                    <span>{{ __('dashboard/addons.create_addon') }}</span>
                </a>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard/addons.title') }}</th>
                            <th>{{ __('dashboard/addons.branch') }}</th>
                            <th>{{ __('dashboard/addons.values') }}</th>
                            <th>{{ __('dashboard/addons.status') }}</th>
                            <th>{{ __('dashboard/addons.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($addons as $addon)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $addon->getTranslation('title', app()->getLocale()) }}</td>
                                <td>
                                    {{ $addon->branch?->getTranslation('title', app()->getLocale()) ?? '-' }}
                                </td>
                                <td><span class="badge bg-label-primary">{{ $addon->values->count() }}</span></td>
                                <td>
                                    <form action="{{ route('admin.addons.activate', $addon->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="badge border-0 btn-loader {{ $addon->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $addon->is_active ? __('dashboard/addons.active') : __('dashboard/addons.inactive') }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.addons.edit', $addon->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> {{ __('dashboard/addons.edit') }}
                                            </a>
                                            <form action="{{ route('admin.addons.destroy', $addon->id) }}" method="POST" onsubmit="return confirm('{{ __('dashboard/addons.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger btn-loader">
                                                    <i class="bx bx-trash me-1"></i> {{ __('dashboard/addons.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">{{ __('dashboard/addons.no_addons') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($addons instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    {{ $addons->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
