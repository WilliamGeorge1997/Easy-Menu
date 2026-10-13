@extends('common::layouts.master')

@section('title', __('dashboard/attributes.edit_attribute') . ' - ' . $product->getTranslation('title', app()->getLocale()))

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
                    {{ __('dashboard/attributes.edit_attribute') }} - <span class="text-primary">{{ $product->getTranslation('title', app()->getLocale()) }}</span>
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/attributes.attribute_subtitle') }}
                </p>
            </div>
            <a href="{{ route('admin.products.attributes.index', $product->id) }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-arrow-back"></i>
                <span>{{ __('dashboard/attributes.back_to_attributes') }}</span>
            </a>
        </div>

        <form action="{{ route('admin.products.attributes.update', [$product->id, $attribute->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-8">
                    {{-- Attribute Details Card --}}
                    <div class="card mb-4">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-info rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-slider-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/attributes.attribute_details') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">{{ __('dashboard/attributes.title_en') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_en') has-validation @enderror" dir="ltr">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $attribute->getTranslation('title', 'en')) }}" placeholder="e.g. Size" required>
                                        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">{{ __('dashboard/attributes.title_ar') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_ar') has-validation @enderror" dir="rtl">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar', $attribute->getTranslation('title', 'ar')) }}" placeholder="مثال: الحجم" dir="rtl" required>
                                        @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">{{ __('dashboard/attributes.type') }} <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="size" {{ old('type', $attribute->type) == 'size' ? 'selected' : '' }}>{{ __('dashboard/attributes.type_size') }}</option>
                                        <option value="color" {{ old('type', $attribute->type) == 'color' ? 'selected' : '' }}>{{ __('dashboard/attributes.type_color') }}</option>
                                        <option value="weight" {{ old('type', $attribute->type) == 'weight' ? 'selected' : '' }}>{{ __('dashboard/attributes.type_weight') }}</option>
                                        <option value="flavor" {{ old('type', $attribute->type) == 'flavor' ? 'selected' : '' }}>{{ __('dashboard/attributes.type_flavor') }}</option>
                                        <option value="other" {{ old('type', $attribute->type) == 'other' ? 'selected' : '' }}>{{ __('dashboard/attributes.type_other') }}</option>
                                    </select>
                                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">{{ __('dashboard/attributes.order') }}</label>
                                    <div class="input-group @error('order') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-sort-up"></i></span>
                                        <input type="number" min="1" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $attribute->order) }}">
                                        @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="required" id="required" value="1" {{ old('required', $attribute->required ? '1' : '0') == '1' ? 'checked' : '' }} style="width: 2.5rem; height: 1.3rem;">
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="required">{{ __('dashboard/attributes.required') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="multi_select" id="multi_select" value="1" {{ old('multi_select', $attribute->multi_select ? '1' : '0') == '1' ? 'checked' : '' }} style="width: 2.5rem; height: 1.3rem;">
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="multi_select">{{ __('dashboard/attributes.multi_select') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="override_price" id="override_price" value="1" {{ old('override_price', $attribute->override_price ? '1' : '0') == '1' ? 'checked' : '' }} style="width: 2.5rem; height: 1.3rem;">
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="override_price">{{ __('dashboard/attributes.override_price') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $attribute->is_active ? '1' : '0') == '1' ? 'checked' : '' }} style="width: 2.5rem; height: 1.3rem;">
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="is_active">{{ __('dashboard/attributes.active') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Repeatable Attribute Values Card --}}
                    <div class="card">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-warning rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-list-ul"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/attributes.attribute_values') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/attributes.values_help') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div id="values-wrapper"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-value-row">
                                <i class="bx bx-plus me-1"></i> {{ __('dashboard/attributes.add_value') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2 btn-loader d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-save fs-5"></i>
                                <span>{{ __('dashboard/attributes.update') }}</span>
                            </button>
                            <a href="{{ route('admin.products.attributes.index', $product->id) }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-x"></i>
                                <span>{{ __('dashboard/attributes.cancel') }}</span>
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
            $existingValues = old('values', $attribute->values->map(function ($val) {
                return [
                    'value_en' => $val->getTranslation('value', 'en'),
                    'value_ar' => $val->getTranslation('value', 'ar'),
                    'price' => $val->price,
                ];
            })->values()->all());
        @endphp
        var existingValues = @json($existingValues);

        function rowTemplate(i, val) {
            val = val || {};
            var valEn = val.value_en ? String(val.value_en).replace(/"/g, '&quot;') : '';
            var valAr = val.value_ar ? String(val.value_ar).replace(/"/g, '&quot;') : '';
            var price = (val.price !== undefined && val.price !== null) ? val.price : 0;

            return `
                <div class="row g-2 border rounded p-3 mb-2 align-items-center">
                    <div class="col-md-5">
                        <label class="form-label small mb-1">{{ __('dashboard/attributes.value_en') }} <span class="text-danger">*</span></label>
                        <input class="form-control" name="values[${i}][value_en]" value="${valEn}" placeholder="e.g. Small" required dir="ltr">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small mb-1">{{ __('dashboard/attributes.value_ar') }} <span class="text-danger">*</span></label>
                        <input class="form-control" name="values[${i}][value_ar]" value="${valAr}" placeholder="مثال: صغير" required dir="rtl">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1">{{ __('dashboard/attributes.price') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="values[${i}][price]" value="${price}" placeholder="0.00" required dir="ltr">
                    </div>
                    <div class="col-md-1 d-flex align-items-end justify-content-end" style="height: 100%;">
                        <button type="button" class="btn btn-sm btn-danger remove-row mt-3" title="{{ __('dashboard/common.remove') }}" aria-label="{{ __('dashboard/common.remove') }}">×</button>
                    </div>
                </div>`;
        }

        function addRow(val) {
            wrapper.insertAdjacentHTML('beforeend', rowTemplate(index, val));
            index++;
        }

        addButton.addEventListener('click', function () {
            addRow({});
        });

        wrapper.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                if (wrapper.querySelectorAll('.row').length > 1) {
                    e.target.closest('.row').remove();
                } else {
                    alert('{{ __('dashboard/attributes.values_required') }}');
                }
            }
        });

        if (Array.isArray(existingValues) && existingValues.length > 0) {
            existingValues.forEach(function (val) {
                addRow(val);
            });
        } else {
            addRow({});
        }
    })();
</script>
@endpush
