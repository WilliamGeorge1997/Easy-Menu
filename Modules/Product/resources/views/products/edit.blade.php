@extends('common::layouts.master')

@section('title', __('dashboard/products.edit_product'))

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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-check-circle fs-5"></i>
                    {{ session('success') }}
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
                    {{ __('dashboard/products.edit_product') }}
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/products.title_en') }} / {{ __('dashboard/products.title_ar') }}
                </p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-arrow-back"></i>
                <span>{{ __('dashboard/products.cancel') }}</span>
            </a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-info rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-food-menu"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/products.edit_product') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/products.description_en') }} / {{ __('dashboard/products.description_ar') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="title_en">{{ __('dashboard/products.title_en') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_en') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" id="title_en" name="title_en"
                                            class="form-control @error('title_en') is-invalid @enderror"
                                            value="{{ old('title_en', $product->getTranslation('title', 'en')) }}"
                                            placeholder="{{ __('dashboard/products.title_en') }}" />
                                        @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="title_ar">{{ __('dashboard/products.title_ar') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('title_ar') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" id="title_ar" name="title_ar"
                                            class="form-control @error('title_ar') is-invalid @enderror"
                                            value="{{ old('title_ar', $product->getTranslation('title', 'ar')) }}"
                                            placeholder="{{ __('dashboard/products.title_ar') }}" dir="rtl" />
                                        @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="description_en">{{ __('dashboard/products.description_en') }}</label>
                                    <textarea id="description_en" name="description_en" rows="3"
                                        class="form-control @error('description_en') is-invalid @enderror"
                                        placeholder="{{ __('dashboard/products.description_en') }}">{{ old('description_en', $product->getTranslation('description', 'en')) }}</textarea>
                                    @error('description_en') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="description_ar">{{ __('dashboard/products.description_ar') }}</label>
                                    <textarea id="description_ar" name="description_ar" rows="3"
                                        class="form-control @error('description_ar') is-invalid @enderror"
                                        placeholder="{{ __('dashboard/products.description_ar') }}" dir="rtl">{{ old('description_ar', $product->getTranslation('description', 'ar')) }}</textarea>
                                    @error('description_ar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-warning rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-slider-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/products.price') }} &amp; {{ __('dashboard/products.category') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/products.order') }} / {{ __('dashboard/products.branch') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium" for="price">{{ __('dashboard/products.price') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('price') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-money"></i></span>
                                        <input type="number" id="price" name="price" step="0.01" min="0"
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price', $product->price) }}">
                                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium" for="order">{{ __('dashboard/products.order') }}</label>
                                    <div class="input-group @error('order') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-sort"></i></span>
                                        <input type="number" id="order" name="order" min="1"
                                            class="form-control @error('order') is-invalid @enderror"
                                            value="{{ old('order', $product->order) }}">
                                        @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 d-flex align-items-end pb-1">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border w-100" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                                {{ old('is_active', $product->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                                style="width: 2.5rem; height: 1.3rem;">
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="is_active">{{ __('dashboard/products.is_active') }}</label>
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->guard('admin')->user()->hasRole(config('product.roles.super_admin')))
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium" for="branch_id">{{ __('dashboard/products.branch') }} <span class="text-danger">*</span></label>
                                        <div class="input-group @error('branch_id') has-validation @enderror">
                                            <span class="input-group-text"><i class="bx bx-store-alt"></i></span>
                                            <select name="branch_id" id="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                                                <option value="">-- {{ __('dashboard/products.branch') }} --</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ old('branch_id', $product->branch_id) == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->getTranslation('title', 'en') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="category_id">{{ __('dashboard/products.category') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('category_id') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-category"></i></span>
                                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                            <option value="">-- {{ __('dashboard/products.category') }} --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->getTranslation('title', 'en') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium" for="addon_ids">{{ __('dashboard/addons.addons') }}</label>
                                    <div class="input-group @error('addon_ids') has-validation @enderror">
                                        @php($selectedAddonIds = old('addon_ids', $product->addons->pluck('id')->toArray()))
                                        <select name="addon_ids[]" id="addon_ids" multiple class="form-select @error('addon_ids') is-invalid @enderror" size="5">
                                            @foreach($addons as $addon)
                                                <option value="{{ $addon->id }}" {{ in_array($addon->id, $selectedAddonIds) ? 'selected' : '' }}>
                                                    {{ $addon->getTranslation('title', app()->getLocale()) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('addon_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <small class="text-muted">{{ __('dashboard/addons.multiple_selection_allowed') }}</small>
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
                                    <i class="bx bx-images"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/products.images') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/products.current_images') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <input type="file" id="images" name="images[]"
                                class="@error('images.*') is-invalid @enderror"
                                multiple accept="image/jpg,image/jpeg,image/png,image/webp"
                                style="position:absolute;width:1px;height:1px;opacity:0;pointer-events:none;">

                            <label for="images" id="images-drop-zone"
                                style="display:flex;flex-direction:column;align-items:center;justify-content:center;border:2.5px dashed #a0aec0;border-radius:.75rem;min-height:220px;cursor:pointer;transition:border-color .2s, background .2s;background:var(--bs-light, #f8f9fa);padding:1.5rem;text-align:center;user-select:none;width:100%;margin-bottom:0;">
                                <div id="images-drop-zone-content">
                                    <div class="mb-3">
                                        <span style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:rgba(105,108,255,.12);">
                                            <i class="bx bx-cloud-upload" style="font-size:2rem;color:#696cff;"></i>
                                        </span>
                                    </div>
                                    <p class="fw-semibold mb-1" style="font-size:.95rem;">{{ __('dashboard/products.images') }}</p>
                                    <p class="text-muted mb-3" style="font-size:.8rem;">{{ __('dashboard/products.images_upload_hint') }}</p>
                                    <span class="badge bg-label-secondary px-3 py-2" style="font-size:.75rem;">JPG • JPEG • PNG • WEBP</span>
                                </div>
                                <div id="images-preview-wrap" style="display:none;width:100%;">
                                    <div id="images-preview-grid" class="row g-2"></div>
                                </div>
                            </label>

                            <button type="button" id="remove-images"
                                class="btn btn-sm btn-danger mt-2 w-100 d-flex align-items-center justify-content-center gap-1"
                                disabled>
                                <i class="bx bx-trash"></i> {{ __('dashboard/products.remove_images') }}
                            </button>

                            @error('images.*')
                                <div class="text-danger mt-2" style="font-size:.85rem;">
                                    <i class="bx bx-error-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror

                            @if($product->images->count())
                                <hr class="my-4">
                                <label class="form-label fw-semibold">{{ __('dashboard/products.current_images') }}</label>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($product->images as $image)
                                        <div class="position-relative" id="image-wrapper-{{ $image->id }}">
                                            <img src="{{ $image->image }}" alt="product image" class="rounded" style="width:90px;height:90px;object-fit:cover;">
                                            <button type="button"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 delete-image-btn"
                                                    style="width:20px;height:20px;line-height:1;"
                                                    data-id="{{ $image->id }}"
                                                    data-url="{{ route('admin.products.images.destroy', $image->id) }}"
                                                    data-confirm="{{ __('dashboard/products.confirm_delete_image') }}"
                                                    data-token="{{ csrf_token() }}">
                                                <i class="bx bx-x" style="font-size:14px;"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2 btn-loader d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-save fs-5"></i>
                                <span>{{ __('dashboard/products.save') }}</span>
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-x"></i>
                                <span>{{ __('dashboard/products.cancel') }}</span>
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
    document.querySelectorAll('.delete-image-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm(this.dataset.confirm)) return;

            var url = this.dataset.url;
            var token = this.dataset.token;
            var wrapper = document.getElementById('image-wrapper-' + this.dataset.id);

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
            })
            .then(function (res) {
                if (res.ok || res.redirected) {
                    if (wrapper) wrapper.remove();
                } else {
                    alert('{{ __('dashboard/products.image_delete_failed') }}');
                }
            })
            .catch(function () {
                alert('{{ __('dashboard/products.image_delete_failed') }}');
            });
        });
    });

    (function () {
        var dropZone = document.getElementById('images-drop-zone');
        var fileInput = document.getElementById('images');
        var previewWrap = document.getElementById('images-preview-wrap');
        var previewGrid = document.getElementById('images-preview-grid');
        var content = document.getElementById('images-drop-zone-content');
        var removeBtn = document.getElementById('remove-images');
        var dragCounter = 0;

        if (!dropZone || !fileInput || !previewWrap || !previewGrid || !content || !removeBtn) return;

        function resetZoneVisual() {
            dropZone.style.borderColor = '#a0aec0';
            dropZone.style.background = 'var(--bs-light, #f8f9fa)';
        }

        function renderPreview(files) {
            previewGrid.innerHTML = '';
            var imageFiles = Array.from(files || []).filter(function (file) {
                return file.type && file.type.startsWith('image/');
            });

            if (!imageFiles.length) {
                previewWrap.style.display = 'none';
                content.style.display = 'block';
                removeBtn.disabled = true;
                resetZoneVisual();
                return;
            }

            imageFiles.forEach(function (file) {
                var col = document.createElement('div');
                col.className = 'col-4';

                var img = document.createElement('img');
                img.className = 'rounded-3 w-100';
                img.style.height = '90px';
                img.style.objectFit = 'cover';
                img.alt = file.name;

                var reader = new FileReader();
                reader.onload = function (e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);

                col.appendChild(img);
                previewGrid.appendChild(col);
            });

            previewWrap.style.display = 'block';
            content.style.display = 'none';
            removeBtn.disabled = false;
            dropZone.style.borderColor = '#696cff';
            dropZone.style.background = 'rgba(105,108,255,.06)';
        }

        function clearFiles() {
            fileInput.value = '';
            previewGrid.innerHTML = '';
            previewWrap.style.display = 'none';
            content.style.display = 'block';
            removeBtn.disabled = true;
            resetZoneVisual();
        }

        fileInput.addEventListener('change', function () {
            renderPreview(fileInput.files);
        });

        removeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            clearFiles();
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
                if (!fileInput.files || !fileInput.files.length) resetZoneVisual();
            }
        });

        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            dragCounter = 0;
            var files = e.dataTransfer ? e.dataTransfer.files : null;
            if (!files || !files.length) {
                resetZoneVisual();
                return;
            }

            try {
                var dt = new DataTransfer();
                Array.from(files).forEach(function (file) {
                    if (file.type && file.type.startsWith('image/')) dt.items.add(file);
                });
                fileInput.files = dt.files;
            } catch (err) {}

            renderPreview(fileInput.files);
        });
    })();

    @if(auth()->guard('admin')->user()->hasRole(config('product.roles.super_admin')))
    var categoriesByBranchUrl = "{{ url('api/v1/products/categories-by-branch/__BRANCH_ID__') }}";
    var addonsByBranchUrl = "{{ url('api/v1/products/addons-by-branch/__BRANCH_ID__') }}";
    var oldCategoryId = "{{ old('category_id', $product->category_id) }}";
    var oldAddonIds = @json(old('addon_ids', $product->addons->pluck('id')->toArray()));

    function loadCategories(branchId, selectedId) {
        var categorySelect = document.getElementById('category_id');
        categorySelect.innerHTML = '<option value="">-- {{ __('dashboard/products.category') }} --</option>';
        if (!branchId) return;

        fetch(categoriesByBranchUrl.replace('__BRANCH_ID__', branchId))
            .then(function (res) { return res.json(); })
            .then(function (response) {
                (response.data || []).forEach(function (cat) {
                    var opt = document.createElement('option');
                    opt.value = cat.id;
                    opt.text = cat.name;
                    if (String(cat.id) === String(selectedId)) opt.selected = true;
                    categorySelect.appendChild(opt);
                });
            });
    }

    function loadAddons(branchId, selectedIds) {
        var addonSelect = document.getElementById('addon_ids');
        addonSelect.innerHTML = '';
        if (!branchId) return;

        fetch(addonsByBranchUrl.replace('__BRANCH_ID__', branchId))
            .then(function (res) { return res.json(); })
            .then(function (response) {
                (response.data || []).forEach(function (addon) {
                    var opt = document.createElement('option');
                    opt.value = addon.id;
                    opt.text = addon.name;
                    if ((selectedIds || []).map(String).includes(String(addon.id))) opt.selected = true;
                    addonSelect.appendChild(opt);
                });
            });
    }

    document.getElementById('branch_id').addEventListener('change', function () {
        loadCategories(this.value, '');
        loadAddons(this.value, []);
    });

    var initialBranch = document.getElementById('branch_id').value;
    if (initialBranch) {
        loadCategories(initialBranch, oldCategoryId);
        loadAddons(initialBranch, oldAddonIds);
    }
    @endif
</script>
@endpush
