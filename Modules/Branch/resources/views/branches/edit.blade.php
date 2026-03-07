@extends('common::layouts.master')

@section('title', __('dashboard/branches.edit_branch'))

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('dashboard/branches.edit_branch') }}</h5>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/branches.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Title EN --}}
                        <div class="col-md-6">
                            <label class="form-label" for="title_en">{{ __('dashboard/branches.title_en') }} <span class="text-danger">*</span></label>
                            <input type="text" id="title_en" name="title_en"
                                class="form-control @error('title_en') is-invalid @enderror"
                                value="{{ old('title_en', $branch->getTranslation('title', 'en')) }}"
                                placeholder="{{ __('dashboard/branches.title_en') }}" />
                            @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Title AR --}}
                        <div class="col-md-6">
                            <label class="form-label" for="title_ar">{{ __('dashboard/branches.title_ar') }} <span class="text-danger">*</span></label>
                            <input type="text" id="title_ar" name="title_ar"
                                class="form-control @error('title_ar') is-invalid @enderror"
                                value="{{ old('title_ar', $branch->getTranslation('title', 'ar')) }}"
                                placeholder="{{ __('dashboard/branches.title_ar') }}" dir="rtl" />
                            @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label class="form-label" for="phone">{{ __('dashboard/branches.phone') }}</label>
                            <input type="text" id="phone" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $branch->phone) }}"
                                placeholder="{{ __('dashboard/branches.phone') }}" />
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Address EN --}}
                        <div class="col-md-6">
                            <label class="form-label" for="address_en">{{ __('dashboard/branches.address_en') }}</label>
                            <input type="text" id="address_en" name="address_en"
                                class="form-control @error('address_en') is-invalid @enderror"
                                value="{{ old('address_en', $branch->getTranslation('address', 'en')) }}"
                                placeholder="{{ __('dashboard/branches.address_en') }}" />
                            @error('address_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Address AR --}}
                        <div class="col-md-6">
                            <label class="form-label" for="address_ar">{{ __('dashboard/branches.address_ar') }}</label>
                            <input type="text" id="address_ar" name="address_ar"
                                class="form-control @error('address_ar') is-invalid @enderror"
                                value="{{ old('address_ar', $branch->getTranslation('address', 'ar')) }}"
                                placeholder="{{ __('dashboard/branches.address_ar') }}" dir="rtl" />
                            @error('address_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Current Image --}}
                        @if($branch->getRawOriginal('image'))
                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('dashboard/branches.current_image') }}</label>
                                <div class="mt-2">
                                    <img src="{{ $branch->image }}" alt="branch image"
                                         class="rounded" style="width:100px;height:100px;object-fit:cover;" />
                                </div>
                            </div>
                        @endif

                        {{-- Image --}}
                        <div class="col-md-6">
                            <label class="form-label" for="image">{{ __('dashboard/branches.image') }}</label>
                            <input type="file" id="image" name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/webp" />
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Is Active --}}
                        <div class="col-md-6 d-flex align-items-end pb-1">
                            @can('activate', $branch)
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }} />
                                    <label class="form-check-label" for="is_active">{{ __('dashboard/branches.is_active') }}</label>
                                </div>
                            @else
                                <span class="badge {{ $branch->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                    {{ $branch->is_active ? __('dashboard/branches.active') : __('dashboard/branches.inactive') }}
                                </span>
                            @endcan
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2 btn-loader">
                            <i class="bx bx-save me-1"></i> {{ __('dashboard/branches.save') }}
                        </button>
                        <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">
                            {{ __('dashboard/branches.cancel') }}
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
<div class="content-backdrop fade"></div>
@endsection
