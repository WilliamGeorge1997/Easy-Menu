@extends('common::layouts.master')

@section('title', __('dashboard/attributes.product_attributes') . ' - ' . $product->getTranslation('title', app()->getLocale()))

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

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm bg-label-primary rounded d-flex align-items-center justify-content-center">
                        <i class="bx bx-list-check"></i>
                    </span>
                    <span>{{ __('dashboard/attributes.product_attributes') }}:</span>
                    <span class="text-primary">{{ $product->getTranslation('title', app()->getLocale()) }}</span>
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/attributes.attribute_subtitle') }}
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <i class="bx bx-arrow-back"></i>
                    <span>{{ __('dashboard/attributes.back') }}</span>
                </a>
                @can('create', [\Modules\Product\Models\Attribute::class, $product])
                    <a href="{{ route('admin.products.attributes.create', $product->id) }}" class="btn btn-primary d-flex align-items-center gap-1">
                        <i class="bx bx-plus"></i>
                        <span>{{ __('dashboard/attributes.create_attribute') }}</span>
                    </a>
                @endcan
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('dashboard/attributes.attributes') }}</h5>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard/attributes.title') }}</th>
                            <th>{{ __('dashboard/attributes.type') }}</th>
                            <th>{{ __('dashboard/attributes.required') }}</th>
                            <th>{{ __('dashboard/attributes.multi_select') }}</th>
                            <th>{{ __('dashboard/attributes.override_price') }}</th>
                            <th>{{ __('dashboard/attributes.order') }}</th>
                            <th>{{ __('dashboard/attributes.values') }}</th>
                            <th>{{ __('dashboard/attributes.status') }}</th>
                            <th>{{ __('dashboard/attributes.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($attributes as $attribute)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">{{ $attribute->getTranslation('title', 'en') }}</span>
                                        <span class="text-muted mx-1">-</span>
                                        <span>{{ $attribute->getTranslation('title', 'ar') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-info text-capitalize">
                                        {{ __('dashboard/attributes.type_' . $attribute->type) ?? $attribute->type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $attribute->required ? 'bg-label-warning' : 'bg-label-secondary' }}">
                                        {{ $attribute->required ? __('dashboard/attributes.yes') : __('dashboard/attributes.no') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $attribute->multi_select ? 'bg-label-primary' : 'bg-label-secondary' }}">
                                        {{ $attribute->multi_select ? __('dashboard/attributes.yes') : __('dashboard/attributes.no') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $attribute->override_price ? 'bg-label-success' : 'bg-label-secondary' }}">
                                        {{ $attribute->override_price ? __('dashboard/attributes.yes') : __('dashboard/attributes.no') }}
                                    </span>
                                </td>
                                <td>{{ $attribute->order }}</td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $attribute->values->count() }}</span>
                                </td>
                                <td>
                                    @can('activate', $attribute)
                                        <form action="{{ route('admin.products.attributes.activate', [$product->id, $attribute->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="badge border-0 btn-loader {{ $attribute->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                                {{ $attribute->is_active ? __('dashboard/attributes.active') : __('dashboard/attributes.inactive') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge {{ $attribute->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $attribute->is_active ? __('dashboard/attributes.active') : __('dashboard/attributes.inactive') }}
                                        </span>
                                    @endcan
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        @can('update', $attribute)
                                            <a href="{{ route('admin.products.attributes.edit', [$product->id, $attribute->id]) }}"
                                               class="btn btn-sm btn-icon btn-outline-primary"
                                               title="{{ __('dashboard/attributes.edit_attribute') }}">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $attribute)
                                            <form action="{{ route('admin.products.attributes.destroy', [$product->id, $attribute->id]) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('{{ __('dashboard/attributes.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-icon btn-outline-danger btn-loader"
                                                        title="{{ __('dashboard/attributes.delete_attribute') }}">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">{{ __('dashboard/attributes.no_attributes') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attributes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    {{ $attributes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
<div class="content-backdrop fade"></div>
@endsection
