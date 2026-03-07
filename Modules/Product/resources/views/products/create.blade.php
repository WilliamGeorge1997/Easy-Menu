@extends('common::layouts.master')

@section('title', __('dashboard/products.create_product'))

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
                <h5 class="mb-0">{{ __('dashboard/products.create_product') }}</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/products.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- Title EN --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.title_en') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                                   value="{{ old('title_en') }}" placeholder="{{ __('dashboard/products.title_en') }}">
                            @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Title AR --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.title_ar') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror"
                                   value="{{ old('title_ar') }}" placeholder="{{ __('dashboard/products.title_ar') }}" dir="rtl">
                            @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description EN --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.description_en') }}</label>
                            <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror"
                                      rows="3" placeholder="{{ __('dashboard/products.description_en') }}">{{ old('description_en') }}</textarea>
                            @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description AR --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard/products.description_ar') }}</label>
                            <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                                      rows="3" placeholder="{{ __('dashboard/products.description_ar') }}" dir="rtl">{{ old('description_ar') }}</textarea>
                            @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Price --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('dashboard/products.price') }} <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', 0) }}">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Order --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('dashboard/products.order') }}</label>
                            <input type="number" name="order" min="1"
                                   class="form-control @error('order') is-invalid @enderror"
                                   value="{{ old('order', 1) }}">
                            @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Is Active --}}
                        <div class="col-md-4 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
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
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                @if(auth()->guard('admin')->user()->hasRole(config('product.roles.super_admin')))
                                    {{-- Super Admin: categories load via AJAX after branch selection --}}
                                    <option value="">-- {{ __('dashboard/products.branch') }} {{ __('dashboard/products.first') }} --</option>
                                @else
                                    {{-- Branch Manager: categories loaded for their own branch --}}
                                    <option value="">-- {{ __('dashboard/products.category') }} --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->getTranslation('title', 'en') }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Images --}}
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
            </div>
        </div>

    </div>
</div>
<div class="content-backdrop fade"></div>
@endsection

@if(auth()->guard('admin')->user()->hasRole(config('product.roles.super_admin')))
@push('scripts')
<script>
    const categoriesByBranchUrl = "{{ url('api/v1/products/categories-by-branch/__BRANCH_ID__') }}";
    const oldCategoryId = "{{ old('category_id') }}";

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

    // On page load: if branch was previously selected (old input after validation fail)
    const initialBranch = document.getElementById('branch_id').value;
    if (initialBranch) {
        loadCategories(initialBranch, oldCategoryId);
    }
</script>
@endpush
@endif
