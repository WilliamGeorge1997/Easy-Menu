@extends('common::layouts.master')

@section('title', __('dashboard/sidebar.roles'))

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('dashboard/sidebar.roles') }}</h5>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard/admins.role') }}</th>
                            <th>{{ __('dashboard/roles.guard') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration + (($roles->currentPage() - 1) * $roles->perPage()) }}</td>
                                <td>
                                    <span class="badge bg-label-info">{{ $role->display ?? $role->name }}</span>
                                </td>
                                <td>{{ $role->guard_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">{{ __('dashboard/roles.no_roles') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
    <div class="content-backdrop fade"></div>
</div>
@endsection
