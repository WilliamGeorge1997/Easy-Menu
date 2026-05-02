@extends('common::layouts.master')

@section('title', __('dashboard/addons.edit_addon'))

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
                        <i class="bx bx-edit"></i>
                    </span>
                    {{ __('dashboard/addons.edit_addon') }}
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/addons.update_details') }}
                </p>
            </div>
            <a href="{{ route('admin.addons.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-arrow-back"></i>
                <span>{{ __('dashboard/addons.back') }}</span>
            </a>
        </div>

        <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-info rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-package"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/addons.addon_details') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/addons.title_and_branch') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">{{ __('dashboard/addons.title_en') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_en') has-validation @enderror" dir="ltr">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $addon->getTranslation('title', 'en')) }}">
                                        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">{{ __('dashboard/addons.title_ar') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_ar') has-validation @enderror" dir="rtl">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar', $addon->getTranslation('title', 'ar')) }}" dir="rtl">
                                        @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                @if(auth()->guard('admin')->user()->hasRole(config('product.roles.super_admin')))
                                    <div class="col-md-8">
                                        <label class="form-label fw-medium">{{ __('dashboard/addons.branch') }} <span class="text-danger">*</span></label>
                                        <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                                            <option value="">{{ __('dashboard/addons.select_branch') }}</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ old('branch_id', $addon->branch_id) == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->getTranslation('title', app()->getLocale()) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('branch_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                @endif

                                <div class="col-md-4 d-flex align-items-end pb-1">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border w-100" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $addon->is_active ? '1' : '0') == '1' ? 'checked' : '' }} style="width: 2.5rem; height: 1.3rem;">
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="is_active">{{ __('dashboard/addons.is_active') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-warning rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-list-ul"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/addons.addon_values') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/addons.values_help') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div id="values-wrapper"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-value-row">{{ __('dashboard/addons.add_value') }}</button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2 btn-loader d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-save fs-5"></i>
                                <span>{{ __('dashboard/addons.update') }}</span>
                            </button>
                            <a href="{{ route('admin.addons.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-x"></i>
                                <span>{{ __('dashboard/addons.cancel') }}</span>
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

@push('scripts')
<script>
    (function () {
        var wrapper = document.getElementById('values-wrapper');
        var addButton = document.getElementById('add-value-row');
        var index = 0;
        @php
            $existingValues = old('values', $addon->values->map(function ($value) {
                return [
                    'title_en' => $value->getTranslation('title', 'en'),
                    'title_ar' => $value->getTranslation('title', 'ar'),
                    'price' => $value->price,
                    'image' => $value->image,
                ];
            })->values()->all());
        @endphp
        var existingValues = @json($existingValues);

        function rowTemplate(i, value) {
            var safeExistingImage = value.image || '';
            return `
                <div class="row g-2 border rounded p-3 mb-2">
                    <div class="col-md-3"><input class="form-control" name="values[${i}][title_en]" value="${value.title_en || ''}" placeholder="{{ __('dashboard/addons.value_title_en') }}" required dir="ltr"></div>
                    <div class="col-md-3"><input class="form-control" name="values[${i}][title_ar]" value="${value.title_ar || ''}" placeholder="{{ __('dashboard/addons.value_title_ar') }}" required dir="rtl"></div>
                    <div class="col-md-2"><input type="number" step="0.01" min="0" class="form-control" name="values[${i}][price]" value="${value.price || 0}" placeholder="{{ __('dashboard/addons.price') }}" required dir="ltr"></div>
                    <div class="col-md-3">
                        <input type="hidden" name="values[${i}][existing_image]" value="${safeExistingImage}">
                        <input type="file" class="form-control" name="values[${i}][image]" accept="image/jpg,image/jpeg,image/png,image/webp">
                    </div>
                    <div class="col-md-1 d-flex align-items-center justify-content-end">
                        <button type="button" class="btn btn-sm btn-danger remove-row" title="{{ __('dashboard/common.remove') }}" aria-label="{{ __('dashboard/common.remove') }}">×</button>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2 mt-1 addon-value-image-preview">
                            <small class="text-muted">{{ __('dashboard/products.current_images') }}:</small>
                            ${value.image
                                ? `<img src="${value.image}" alt="value image" class="rounded border" style="width:56px;height:56px;object-fit:cover;">`
                                : `<span class="text-muted small">{{ __('dashboard/common.no_image') }}</span>`
                            }
                        </div>
                    </div>
                </div>`;
        }

        function addRow(value) {
            wrapper.insertAdjacentHTML('beforeend', rowTemplate(index, value || {}));
            index++;
        }

        addButton.addEventListener('click', function () {
            addRow({});
        });

        wrapper.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('.row').remove();
            }
        });

        wrapper.addEventListener('change', function (e) {
            if (!e.target.matches('input[type="file"][name^="values["][name$="[image]"]')) {
                return;
            }

            var preview = e.target.closest('.row').querySelector('.addon-value-image-preview');
            if (!preview) return;

            if (!e.target.files || !e.target.files[0]) {
                preview.innerHTML = `
                    <small class="text-muted">{{ __('dashboard/products.current_images') }}:</small>
                    <span class="text-muted small">{{ __('dashboard/common.no_image') }}</span>
                `;
                return;
            }

            var file = e.target.files[0];
            var reader = new FileReader();
            reader.onload = function (event) {
                preview.innerHTML = `
                    <small class="text-muted">{{ __('dashboard/products.current_images') }}:</small>
                    <img src="${event.target.result}" alt="value image" class="rounded border" style="width:56px;height:56px;object-fit:cover;">
                `;
            };
            reader.readAsDataURL(file);
        });

        if (existingValues.length) {
            existingValues.forEach(function (value) {
                addRow(value);
            });
        } else {
            addRow({});
        }
    })();
</script>
@endpush
