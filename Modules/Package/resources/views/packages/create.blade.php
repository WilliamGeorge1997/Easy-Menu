@extends('common::layouts.master')

@section('title', __('dashboard/packages.create_package'))

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                <div class="d-flex align-items-start gap-2">
                    <i class="bx bx-error-circle fs-5 mt-1"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                        <i class="bx bx-package"></i>
                    </span>
                    {{ __('dashboard/packages.create_package') }}
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/packages.title_en') }} / {{ __('dashboard/packages.title_ar') }}
                </p>
            </div>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-arrow-back"></i>
                <span>{{ __('dashboard/packages.cancel') }}</span>
            </a>
        </div>

        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-info rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-text"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/packages.package') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/packages.title_en') }} / {{ __('dashboard/packages.title_ar') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="title_en">{{ __('dashboard/packages.title_en') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_en') has-validation @enderror" dir="ltr">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" id="title_en" name="title_en"
                                            class="form-control @error('title_en') is-invalid @enderror"
                                            value="{{ old('title_en') }}"
                                            placeholder="{{ __('dashboard/packages.title_en') }}" />
                                        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="title_ar">{{ __('dashboard/packages.title_ar') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_ar') has-validation @enderror" dir="rtl">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" id="title_ar" name="title_ar"
                                            class="form-control @error('title_ar') is-invalid @enderror"
                                            value="{{ old('title_ar') }}"
                                            placeholder="{{ __('dashboard/packages.title_ar') }}"
                                            dir="rtl" />
                                        @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="description_en">{{ __('dashboard/packages.description_en') }}</label>
                                    <div class="@error('description_en') has-validation @enderror">
                                        <textarea id="description_en" name="description_en" class="form-control @error('description_en') is-invalid @enderror"
                                            rows="3" placeholder="{{ __('dashboard/packages.description_en') }}">{{ old('description_en') }}</textarea>
                                        @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="description_ar">{{ __('dashboard/packages.description_ar') }}</label>
                                    <div class="@error('description_ar') has-validation @enderror">
                                        <textarea id="description_ar" name="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                                            rows="3" placeholder="{{ __('dashboard/packages.description_ar') }}" dir="rtl">{{ old('description_ar') }}</textarea>
                                        @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-warning rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-money"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/packages.price') }} &amp; {{ __('dashboard/packages.months') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/packages.discounted_price') }} / {{ __('dashboard/packages.is_active') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-medium" for="price">{{ __('dashboard/packages.price') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('price') has-validation @enderror" dir="ltr">
                                        <span class="input-group-text"><i class="bx bx-money"></i></span>
                                        <input type="number" id="price" name="price" step="0.01" min="0"
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price', 0) }}">
                                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-medium" for="discounted_price">{{ __('dashboard/packages.discounted_price') }}</label>
                                    <div class="input-group @error('discounted_price') has-validation @enderror" dir="ltr">
                                        <span class="input-group-text"><i class="bx bx-purchase-tag-alt"></i></span>
                                        <input type="number" id="discounted_price" name="discounted_price" step="0.01" min="0"
                                            class="form-control @error('discounted_price') is-invalid @enderror"
                                            value="{{ old('discounted_price') }}">
                                        @error('discounted_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-medium" for="months">{{ __('dashboard/packages.months') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('months') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                        <select name="months" id="months" class="form-select @error('months') is-invalid @enderror">
                                            <option value="">-- {{ __('dashboard/packages.months') }} --</option>
                                            @for($m = 1; $m <= 24; $m++)
                                                <option value="{{ $m }}" {{ (int) old('months') === $m ? 'selected' : '' }}>
                                                    {{ $m }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-3 d-flex align-items-end pb-1">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border w-100" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                                {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                                style="width: 2.5rem; height: 1.3rem;">
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="is_active">{{ __('dashboard/packages.is_active') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2 btn-loader d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-save fs-5"></i>
                                <span>{{ __('dashboard/packages.save') }}</span>
                            </button>
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-x"></i>
                                <span>{{ __('dashboard/packages.cancel') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<div class="content-backdrop fade"></div>
@endsection

