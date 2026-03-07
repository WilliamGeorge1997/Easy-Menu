@extends('common::layouts.master')

@section('title', __('dashboard/products.edit_product'))

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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('dashboard/products.edit_product') }}</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/products.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Title EN --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.title_en') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                                   value="{{ old('title_en', $product->getTranslation('title', 'en')) }}"
                                   placeholder="{{ __('dashboard/products.title_en') }}">
                            @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Title AR --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.title_ar') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror"
                                   value="{{ old('title_ar', $product->getTranslation('title', 'ar')) }}"
                                   placeholder="{{ __('dashboard/products.title_ar') }}" dir="rtl">
                            @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description EN --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.description_en') }}</label>
                            <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror"
                                      rows="3" placeholder="{{ __('dashboard/products.description_en') }}">{{ old('description_en', $product->getTranslation('description', 'en')) }}</textarea>
                            @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description AR --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.description_ar') }}</label>
                            <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                                      rows="3" placeholder="{{ __('dashboard/products.description_ar') }}" dir="rtl">{{ old('description_ar', $product->getTranslation('description', 'ar')) }}</textarea>
                            @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Price --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('dashboard/products.price') }} <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $product->price) }}">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Order --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('dashboard/products.order') }}</label>
                            <input type="number" name="order" min="1"
                                   class="form-control @error('order') is-invalid @enderror"
                                   value="{{ old('order', $product->order) }}">
                            @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Is Active --}}
                        <div class="col-md-4 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                       {{ old('is_active', $product->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">{{ __('dashboard/products.is_active') }}</label>
                            </div>
                        </div>

                        {{-- Branch (Super Admin only) --}}
                        @if(auth()->guard('admin')->user()->hasRole(config('product.roles.super_admin')))
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard/products.branch') }} <span class="text-danger">*</span></label>
                                <select name="branch_id" id="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                                    <option value="">-- {{ __('dashboard/products.branch') }} --</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $product->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->getTranslation('title', 'en') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        {{-- Category --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.category') }} <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">-- {{ __('dashboard/products.category') }} --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->getTranslation('title', 'en') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Add New Images --}}
                        <div class="col-12">
                            <label class="form-label">{{ __('dashboard/products.images') }}</label>
                            <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror"
                                   multiple accept="image/jpg,image/jpeg,image/png,image/webp">
                            @error('images.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2 btn-loader">
                            <i class="bx bx-save me-1"></i> {{ __('dashboard/products.save') }}
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            {{ __('dashboard/products.cancel') }}
                        </a>
                    </div>

                </form>

                {{-- Current Images — OUTSIDE the main form to avoid nested form HTML issue --}}
                @if($product->images->count())
                    <div class="mt-4">
                        <label class="form-label fw-semibold">{{ __('dashboard/products.current_images') }}</label>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            @foreach($product->images as $image)
                                <div class="position-relative" id="image-wrapper-{{ $image->id }}">
                                    <img src="{{ $image->image }}" alt="product image"
                                         class="rounded" style="width:100px;height:100px;object-fit:cover;">
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
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
<div class="content-backdrop fade"></div>
@endsection

@push('scripts')
<script>
    {{-- Image delete via fetch (fixes nested form HTML issue) --}}
    document.querySelectorAll('.delete-image-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm(this.dataset.confirm)) return;

            const url     = this.dataset.url;
            const token   = this.dataset.token;
            const wrapper = document.getElementById('image-wrapper-' + this.dataset.id);

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
            })
            .then(res => {
                if (res.ok || res.redirected) {
                    wrapper.remove();
                } else {
                    alert('{{ __('dashboard/products.image_delete_failed') }}');
                }
            })
            .catch(() => {
                alert('{{ __('dashboard/products.image_delete_failed') }}');
            });
        });
    });

    @if(auth()->guard('admin')->user()->hasRole(config('product.roles.super_admin')))
    {{-- Branch → Category AJAX (Super Admin only) --}}
    const categoriesByBranchUrl = "{{ url('api/v1/products/categories-by-branch/__BRANCH_ID__') }}";
    const oldCategoryId = "{{ old('category_id', $product->category_id) }}";

    function loadCategories(branchId, selectedId) {
        const categorySelect = document.getElementById('category_id');
        categorySelect.innerHTML = '<option value="">-- {{ __('dashboard/products.category') }} --</option>';

        if (!branchId) return;

        fetch(categoriesByBranchUrl.replace('__BRANCH_ID__', branchId))
            .then(res => res.json())
            .then(response => {
                (response.data || []).forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.id;
                    opt.text  = cat.name;
                    if (String(cat.id) === String(selectedId)) opt.selected = true;
                    categorySelect.appendChild(opt);
                });
            });
    }

    document.getElementById('branch_id').addEventListener('change', function () {
        loadCategories(this.value, '');
    });

    const initialBranch = document.getElementById('branch_id').value;
    if (initialBranch) {
        loadCategories(initialBranch, oldCategoryId);
    }
    @endif
</script>
@endpush
