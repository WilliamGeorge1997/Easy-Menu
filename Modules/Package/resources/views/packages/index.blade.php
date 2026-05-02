@extends('common::layouts.master')

@section('title', __('dashboard/packages.packages'))

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
                <h5 class="mb-0">{{ __('dashboard/packages.packages') }}</h5>
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="bx bx-plus"></i>
                    <span>{{ __('dashboard/packages.create_package') }}</span>
                </a>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard/packages.package') }}</th>
                            <th>{{ __('dashboard/packages.price') }}</th>
                            <th>{{ __('dashboard/packages.discounted_price') }}</th>
                            <th>{{ __('dashboard/packages.months') }}</th>
                            <th>{{ __('dashboard/packages.status') }}</th>
                            <th>{{ __('dashboard/packages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($packages as $package)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">{{ $package->getTranslation('title', 'en') }}</span>
                                        <span class="text-muted mx-1">-</span>
                                        <span>{{ $package->getTranslation('title', 'ar') }}</span>
                                    </div>
                                </td>
                                <td>{{ number_format($package->price, 2) }}</td>
                                <td>{{ $package->discounted_price !== null ? number_format($package->discounted_price, 2) : '-' }}</td>
                                <td>{{ $package->months }}</td>
                                <td>
                                    <form action="{{ route('admin.packages.activate', $package->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="badge border-0 btn-loader {{ $package->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $package->is_active ? __('dashboard/packages.active') : __('dashboard/packages.inactive') }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.packages.edit', $package->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> {{ __('dashboard/packages.edit_package') }}
                                            </a>
                                            <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
                                                onsubmit="return confirm('{{ __('dashboard/packages.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger btn-loader">
                                                    <i class="bx bx-trash me-1"></i> {{ __('dashboard/packages.delete_package') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">{{ __('dashboard/packages.no_packages') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($packages instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>

    </div>

    <div class="content-backdrop fade"></div>
</div>
@endsection

