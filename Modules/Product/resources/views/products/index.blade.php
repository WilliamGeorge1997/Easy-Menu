@extends('common::layouts.master')

@section('title', __('dashboard/products.products'))

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
                <h5 class="mb-0">{{ __('dashboard/products.products') }}</h5>
                @can('create', \Modules\Product\Models\Product::class)
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i> {{ __('dashboard/products.create_product') }}
                    </a>
                @endcan
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard/products.product') }}</th>
                            <th>{{ __('dashboard/products.category') }}</th>
                            @if(auth('admin')->user()->hasRole(config('product.roles.super_admin')))
                                <th>{{ __('dashboard/products.branch') }}</th>
                            @endif
                            <th>{{ __('dashboard/products.price') }}</th>
                            <th>{{ __('dashboard/products.order') }}</th>
                            <th>{{ __('dashboard/products.images') }}</th>
                            <th>{{ __('dashboard/products.status') }}</th>
                            <th>{{ __('dashboard/products.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($product->images->count())
                                            <img src="{{ $product->images->first()->image }}"
                                                alt="{{ $product->getTranslation('title', 'en') }}"
                                                width="45" height="45"
                                                class="rounded-circle object-fit-cover flex-shrink-0" />
                                        @else
                                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-label-secondary flex-shrink-0" style="width:45px;height:45px;">
                                                <i class="bx bx-package fs-4"></i>
                                            </span>
                                        @endif
                                        <div>
                                            <span class="fw-semibold">{{ $product->getTranslation('title', 'en') }}</span>
                                            <span class="text-muted mx-1">-</span>
                                            <span>{{ $product->getTranslation('title', 'ar') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->category?->getTranslation('title', app()->getLocale()) ?? '-' }}</td>
                                @if(auth('admin')->user()->hasRole(config('product.roles.super_admin')))
                                    <td>{{ $product->branch?->getTranslation('title', app()->getLocale()) ?? '-' }}</td>
                                @endif
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->order }}</td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $product->images->count() }}</span>
                                </td>
                                <td>
                                    @can('activate', $product)
                                        <form action="{{ route('admin.products.activate', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="badge border-0 btn-loader {{ $product->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                                {{ $product->is_active ? __('dashboard/products.active') : __('dashboard/products.inactive') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge {{ $product->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $product->is_active ? __('dashboard/products.active') : __('dashboard/products.inactive') }}
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
                                            @can('update', $product)
                                                <a class="dropdown-item" href="{{ route('admin.products.edit', $product->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> {{ __('dashboard/products.edit_product') }}
                                                </a>
                                            @endcan
                                            @can('delete', $product)
                                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                                    onsubmit="return confirm('{{ __('dashboard/products.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger btn-loader">
                                                        <i class="bx bx-trash me-1"></i> {{ __('dashboard/products.delete_product') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">{{ __('dashboard/products.no_products') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>

    <div class="content-backdrop fade"></div>
</div>
@endsection
