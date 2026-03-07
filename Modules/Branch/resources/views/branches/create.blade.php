@extends('common::layouts.master')

@section('title', __('dashboard/branches.create_branch'))

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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('dashboard/branches.create_branch') }}</h5>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/branches.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.branches.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- Title EN --}}
                        <div class="col-md-6">
                            <label class="form-label" for="title_en">{{ __('dashboard/branches.title_en') }} <span class="text-danger">*</span></label>
                            <input type="text" id="title_en" name="title_en"
                                class="form-control @error('title_en') is-invalid @enderror"
                                value="{{ old('title_en') }}"
                                placeholder="{{ __('dashboard/branches.title_en') }}" />
                            @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Title AR --}}
                        <div class="col-md-6">
                            <label class="form-label" for="title_ar">{{ __('dashboard/branches.title_ar') }} <span class="text-danger">*</span></label>
                            <input type="text" id="title_ar" name="title_ar"
                                class="form-control @error('title_ar') is-invalid @enderror"
                                value="{{ old('title_ar') }}"
                                placeholder="{{ __('dashboard/branches.title_ar') }}" dir="rtl" />
                            @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label class="form-label" for="phone">{{ __('dashboard/branches.phone') }}</label>
                            <input type="text" id="phone" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}"
                                placeholder="{{ __('dashboard/branches.phone') }}" />
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Address EN --}}
                        <div class="col-md-6">
                            <label class="form-label" for="address_en">{{ __('dashboard/branches.address_en') }}</label>
                            <input type="text" id="address_en" name="address_en"
                                class="form-control @error('address_en') is-invalid @enderror"
                                value="{{ old('address_en') }}"
                                placeholder="{{ __('dashboard/branches.address_en') }}" />
                            @error('address_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Address AR --}}
                        <div class="col-md-6">
                            <label class="form-label" for="address_ar">{{ __('dashboard/branches.address_ar') }}</label>
                            <input type="text" id="address_ar" name="address_ar"
                                class="form-control @error('address_ar') is-invalid @enderror"
                                value="{{ old('address_ar') }}"
                                placeholder="{{ __('dashboard/branches.address_ar') }}" dir="rtl" />
                            @error('address_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

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
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', 1) ? 'checked' : '' }} />
                                <label class="form-check-label" for="is_active">{{ __('dashboard/branches.is_active') }}</label>
                            </div>
                        </div>

                        {{-- Branch Manager Admin Section --}}
                        <div class="col-12">
                            <hr class="my-2" />
                            <h6 class="mb-3 text-muted">
                                <i class="bx bx-user me-1"></i> {{ __('dashboard/branches.branch_manager_account') }}
                            </h6>
                        </div>

                        {{-- Admin Name --}}
                        <div class="col-md-6">
                            <label class="form-label" for="admin_name">{{ __('dashboard/branches.admin_name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="admin_name" name="admin_name"
                                class="form-control @error('admin_name') is-invalid @enderror"
                                value="{{ old('admin_name') }}"
                                placeholder="{{ __('dashboard/branches.admin_name') }}" />
                            @error('admin_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Admin Email --}}
                        <div class="col-md-6">
                            <label class="form-label" for="admin_email">{{ __('dashboard/branches.admin_email') }} <span class="text-danger">*</span></label>
                            <input type="email" id="admin_email" name="admin_email"
                                class="form-control @error('admin_email') is-invalid @enderror"
                                value="{{ old('admin_email') }}"
                                placeholder="{{ __('dashboard/branches.admin_email') }}" />
                            @error('admin_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Admin Phone --}}
                        <div class="col-md-6">
                            <label class="form-label" for="admin_phone">{{ __('dashboard/branches.admin_phone') }}</label>
                            <input type="text" id="admin_phone" name="admin_phone"
                                class="form-control @error('admin_phone') is-invalid @enderror"
                                value="{{ old('admin_phone') }}"
                                placeholder="{{ __('dashboard/branches.admin_phone') }}" />
                            @error('admin_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Admin Password --}}
                        <div class="col-md-6">
                            <label class="form-label" for="admin_password">{{ __('dashboard/branches.admin_password') }} <span class="text-danger">*</span></label>
                            <input type="password" id="admin_password" name="admin_password"
                                class="form-control @error('admin_password') is-invalid @enderror"
                                placeholder="{{ __('dashboard/branches.admin_password') }}" />
                            @error('admin_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Admin Password Confirmation --}}
                        <div class="col-md-6">
                            <label class="form-label" for="admin_password_confirmation">{{ __('dashboard/branches.admin_password_confirmation') }} <span class="text-danger">*</span></label>
                            <input type="password" id="admin_password_confirmation" name="admin_password_confirmation"
                                class="form-control"
                                placeholder="{{ __('dashboard/branches.admin_password_confirmation') }}" />
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
