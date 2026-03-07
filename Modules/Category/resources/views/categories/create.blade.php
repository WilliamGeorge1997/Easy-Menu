@extends('common::layouts.master')

@section('title', __('dashboard/categories.create_category'))

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
                <h5 class="mb-0">{{ __('dashboard/categories.create_category') }}</h5>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/categories.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- Title EN --}}
                        <div class="col-md-6">
                            <label class="form-label" for="title_en">{{ __('dashboard/categories.title_en') }} <span class="text-danger">*</span></label>
                            <input type="text" id="title_en" name="title_en"
                                class="form-control @error('title_en') is-invalid @enderror"
                                value="{{ old('title_en') }}"
                                placeholder="{{ __('dashboard/categories.title_en') }}" />
                            @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Title AR --}}
                        <div class="col-md-6">
                            <label class="form-label" for="title_ar">{{ __('dashboard/categories.title_ar') }} <span class="text-danger">*</span></label>
                            <input type="text" id="title_ar" name="title_ar"
                                class="form-control @error('title_ar') is-invalid @enderror"
                                value="{{ old('title_ar') }}"
                                placeholder="{{ __('dashboard/categories.title_ar') }}" dir="rtl" />
                            @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Description EN --}}
                        <div class="col-md-6">
                            <label class="form-label" for="description_en">{{ __('dashboard/categories.description_en') }}</label>
                            <textarea id="description_en" name="description_en" rows="3"
                                class="form-control @error('description_en') is-invalid @enderror"
                                placeholder="{{ __('dashboard/categories.description_en') }}">{{ old('description_en') }}</textarea>
                            @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Description AR --}}
                        <div class="col-md-6">
                            <label class="form-label" for="description_ar">{{ __('dashboard/categories.description_ar') }}</label>
                            <textarea id="description_ar" name="description_ar" rows="3"
                                class="form-control @error('description_ar') is-invalid @enderror"
                                placeholder="{{ __('dashboard/categories.description_ar') }}" dir="rtl">{{ old('description_ar') }}</textarea>
                            @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Branch (Super Admin only) --}}
                        @if(auth('admin')->user()->hasRole(config('category.roles.super_admin')))
                            <div class="col-md-6">
                                <label class="form-label" for="branch_id">{{ __('dashboard/categories.branch') }} <span class="text-danger">*</span></label>
                                <select id="branch_id" name="branch_id"
                                    class="form-select @error('branch_id') is-invalid @enderror">
                                    <option value="">-- {{ __('dashboard/categories.branch') }} --</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->getTranslation('title', app()->getLocale()) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @endif

                        {{-- Order --}}
                        <div class="col-md-6">
                            <label class="form-label" for="order">{{ __('dashboard/categories.order') }}</label>
                            <input type="number" id="order" name="order" min="0"
                                class="form-control @error('order') is-invalid @enderror"
                                value="{{ old('order', 0) }}" />
                            @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Image --}}
                        <div class="col-md-6">
                            <label class="form-label" for="image">{{ __('dashboard/categories.image') }}</label>
                            <input type="file" id="image" name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/webp" />
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Is Active --}}
                        <div class="col-md-6 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active"
                                    name="is_active" value="1"
                                    {{ old('is_active', 1) ? 'checked' : '' }} />
                                <label class="form-check-label" for="is_active">{{ __('dashboard/categories.is_active') }}</label>
                            </div>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2 btn-loader">
                            <i class="bx bx-save me-1"></i> {{ __('dashboard/categories.save') }}
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            {{ __('dashboard/categories.cancel') }}
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
<div class="content-backdrop fade"></div>
@endsection
