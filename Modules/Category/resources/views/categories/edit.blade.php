@extends('common::layouts.master')

@section('title', __('dashboard/categories.edit_category'))

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
                    {{ __('dashboard/categories.edit_category') }}
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/categories.title_en') }} / {{ __('dashboard/categories.title_ar') }}
                </p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-arrow-back"></i>
                <span>{{ __('dashboard/categories.cancel') }}</span>
            </a>
        </div>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-info rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-category"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/categories.edit_category') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/categories.description_en') }} / {{ __('dashboard/categories.description_ar') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="title_en">{{ __('dashboard/categories.title_en') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_en') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" id="title_en" name="title_en"
                                            class="form-control @error('title_en') is-invalid @enderror"
                                            value="{{ old('title_en', $category->getTranslation('title', 'en')) }}"
                                            placeholder="{{ __('dashboard/categories.title_en') }}" dir="ltr" />
                                        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="title_ar">{{ __('dashboard/categories.title_ar') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_ar') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" id="title_ar" name="title_ar"
                                            class="form-control @error('title_ar') is-invalid @enderror"
                                            value="{{ old('title_ar', $category->getTranslation('title', 'ar')) }}"
                                            placeholder="{{ __('dashboard/categories.title_ar') }}" dir="rtl" />
                                        @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="description_en">{{ __('dashboard/categories.description_en') }}</label>
                                    <textarea id="description_en" name="description_en" rows="3"
                                        class="form-control @error('description_en') is-invalid @enderror"
                                        placeholder="{{ __('dashboard/categories.description_en') }}">{{ old('description_en', $category->getTranslation('description', 'en')) }}</textarea>
                                    @error('description_en')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="description_ar">{{ __('dashboard/categories.description_ar') }}</label>
                                    <textarea id="description_ar" name="description_ar" rows="3"
                                        class="form-control @error('description_ar') is-invalid @enderror"
                                        placeholder="{{ __('dashboard/categories.description_ar') }}" dir="rtl">{{ old('description_ar', $category->getTranslation('description', 'ar')) }}</textarea>
                                    @error('description_ar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                @if(auth('admin')->user()->hasRole(config('category.roles.super_admin')))
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium" for="branch_id">{{ __('dashboard/categories.branch') }} <span class="text-danger">*</span></label>
                                        <div class="input-group @error('branch_id') has-validation @enderror">
                                            <span class="input-group-text"><i class="bx bx-store-alt"></i></span>
                                            <select id="branch_id" name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                                                <option value="">-- {{ __('dashboard/categories.branch') }} --</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ old('branch_id', $category->branch_id) == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->getTranslation('title', app()->getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="order">{{ __('dashboard/categories.order') }}</label>
                                    <div class="input-group @error('order') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-sort"></i></span>
                                        <input type="number" id="order" name="order" min="0"
                                            class="form-control @error('order') is-invalid @enderror"
                                            value="{{ old('order', $category->order) }}" />
                                        @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-end pb-1">
                                    @can('activate', $category)
                                        <div class="d-flex align-items-center gap-3 p-3 rounded border w-100" style="background: var(--bs-light, #f8f9fa);">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" id="is_active"
                                                    name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                                    style="width: 2.5rem; height: 1.3rem;" />
                                            </div>
                                            <div>
                                                <label class="form-check-label fw-medium mb-0" for="is_active">{{ __('dashboard/categories.is_active') }}</label>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge {{ $category->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $category->is_active ? __('dashboard/categories.active') : __('dashboard/categories.inactive') }}
                                        </span>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-success rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-image-add"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/categories.image') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/categories.current_image') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <input type="file" id="image" name="image"
                                class="@error('image') is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/webp"
                                style="position:absolute;width:1px;height:1px;opacity:0;pointer-events:none;" />

                            <label for="image" id="image-drop-zone"
                                style="display:flex;flex-direction:column;align-items:center;justify-content:center;border:2.5px dashed #a0aec0;border-radius:.75rem;min-height:220px;cursor:pointer;transition:border-color .2s, background .2s;background:var(--bs-light, #f8f9fa);padding:1.5rem;text-align:center;user-select:none;width:100%;margin-bottom:0;">
                                <div id="drop-zone-content" @if($category->getRawOriginal('image')) style="display:none;" @endif>
                                    <div class="mb-3">
                                        <span style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:rgba(105,108,255,.12);">
                                            <i class="bx bx-cloud-upload" style="font-size:2rem;color:#696cff;"></i>
                                        </span>
                                    </div>
                                    <p class="fw-semibold mb-1" style="font-size:.95rem;">{{ __('dashboard/categories.image') }}</p>
                                    <p class="text-muted mb-3" style="font-size:.8rem;">{{ __('dashboard/categories.image_upload_hint') }}</p>
                                    <span class="badge bg-label-secondary px-3 py-2" style="font-size:.75rem;">JPG • JPEG • PNG • WEBP</span>
                                </div>
                                <div id="image-preview-wrap" @if($category->getRawOriginal('image')) style="display:block;width:100%;" @else style="display:none;width:100%;" @endif>
                                    <img id="image-preview" src="{{ $category->image ?: '#' }}" alt="Preview" class="rounded-3 w-100" style="max-height:200px;object-fit:cover;" />
                                </div>
                            </label>

                            <button type="button" id="remove-image"
                                class="btn btn-sm btn-danger mt-2 w-100 d-flex align-items-center justify-content-center gap-1"
                                @if(!$category->getRawOriginal('image')) disabled @endif>
                                <i class="bx bx-trash"></i> {{ __('dashboard/categories.remove_image') }}
                            </button>

                            @error('image')
                                <div class="text-danger mt-2" style="font-size:.85rem;">
                                    <i class="bx bx-error-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2 btn-loader d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-save fs-5"></i>
                                <span>{{ __('dashboard/categories.save') }}</span>
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-x"></i>
                                <span>{{ __('dashboard/categories.cancel') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<div class="content-backdrop fade"></div>

@push('scripts')
<script>
(function () {
    var dropZone = document.getElementById('image-drop-zone');
    var fileInput = document.getElementById('image');
    var previewWrap = document.getElementById('image-preview-wrap');
    var preview = document.getElementById('image-preview');
    var content = document.getElementById('drop-zone-content');
    var removeBtn = document.getElementById('remove-image');
    var dragCounter = 0;

    if (!dropZone || !fileInput) return;

    function showPreview(file) {
        if (!file || !file.type.startsWith('image/')) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            previewWrap.style.display = 'block';
            content.style.display = 'none';
            removeBtn.disabled = false;
            dropZone.style.borderColor = '#696cff';
            dropZone.style.background = 'rgba(105,108,255,.06)';
        };
        reader.readAsDataURL(file);
    }

    function resetDropZone() {
        preview.src = '#';
        previewWrap.style.display = 'none';
        content.style.display = 'block';
        removeBtn.disabled = true;
        fileInput.value = '';
        dropZone.style.borderColor = '#a0aec0';
        dropZone.style.background = 'var(--bs-light, #f8f9fa)';
    }

    fileInput.addEventListener('change', function () {
        if (fileInput.files && fileInput.files.length) showPreview(fileInput.files[0]);
    });

    removeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        resetDropZone();
    });

    dropZone.addEventListener('dragenter', function (e) {
        e.preventDefault();
        dragCounter++;
        dropZone.style.borderColor = '#696cff';
        dropZone.style.background = 'rgba(105,108,255,.06)';
    });

    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
    });

    dropZone.addEventListener('dragleave', function () {
        dragCounter--;
        if (dragCounter <= 0) {
            dragCounter = 0;
            dropZone.style.borderColor = '#a0aec0';
            dropZone.style.background = 'var(--bs-light, #f8f9fa)';
        }
    });

    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dragCounter = 0;
        dropZone.style.borderColor = '#a0aec0';
        dropZone.style.background = 'var(--bs-light, #f8f9fa)';
        var file = e.dataTransfer && e.dataTransfer.files[0];
        if (file) {
            try {
                var dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
            } catch (err) {}
            showPreview(file);
        }
    });
})();
</script>
@endpush
@endsection
