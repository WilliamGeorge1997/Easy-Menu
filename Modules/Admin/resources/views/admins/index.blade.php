@extends('common::layouts.master')

@section('title', __('dashboard/admins.admins'))

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

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
                <h5 class="mb-0">{{ __('dashboard/admins.admins') }}</h5>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard/admins.name') }}</th>
                            <th>{{ __('dashboard/admins.role') }}</th>
                            <th>{{ __('dashboard/admins.branch') }}</th>
                            <th>{{ __('dashboard/admins.status') }}</th>
                            <th>{{ __('dashboard/admins.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($admins as $admin)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="d-flex align-items-center justify-content-center rounded-circle bg-label-primary flex-shrink-0" style="width:45px;height:45px;">
                                            <i class="bx bx-user fs-4"></i>
                                        </span>
                                        <div>
                                            <span class="fw-semibold d-block">{{ $admin->name }}</span>
                                            <small class="text-muted">{{ $admin->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @foreach($admin->roles as $role)
                                        <span class="badge bg-label-info">{{ $role->display ?? $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $admin->branch?->getTranslation('title', app()->getLocale()) ?? '-' }}</td>
                                <td>
                                    @can('activate', $admin)
                                        <form action="{{ route('admin.admins.activate', $admin->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="badge border-0 btn-loader {{ $admin->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                                {{ $admin->is_active ? __('dashboard/admins.active') : __('dashboard/admins.inactive') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge {{ $admin->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $admin->is_active ? __('dashboard/admins.active') : __('dashboard/admins.inactive') }}
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
                                            <a class="dropdown-item" href="{{ route('admin.admins.edit', $admin->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> {{ __('dashboard/admins.edit_admin') }}
                                            </a>
                                            <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST"
                                                onsubmit="return confirm('{{ __('dashboard/admins.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger btn-loader">
                                                    <i class="bx bx-trash me-1"></i> {{ __('dashboard/admins.delete_admin') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">{{ __('dashboard/admins.no_admins') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($admins instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>

    </div>
    <div class="content-backdrop fade"></div>
</div>
@endsection
