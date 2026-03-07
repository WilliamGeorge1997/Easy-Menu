@extends('common::layouts.master')

@section('title', __('dashboard/admins.edit_admin'))

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
                <h5 class="mb-0">{{ __('dashboard/admins.edit_admin') }}</h5>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/admins.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- Role (read-only badge) --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">{{ __('dashboard/admins.role') }}</label>
                            <div class="mt-1">
                                @foreach($admin->roles as $role)
                                    <span class="badge bg-label-info fs-6">{{ $role->display ?? $role->name }}</span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label" for="name">{{ __('dashboard/admins.name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $admin->name) }}"
                                placeholder="{{ __('dashboard/admins.name') }}" />
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label class="form-label" for="email">{{ __('dashboard/admins.email') }} <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $admin->email) }}"
                                placeholder="{{ __('dashboard/admins.email') }}" />
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label class="form-label" for="phone">{{ __('dashboard/admins.phone') }}</label>
                            <input type="text" id="phone" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $admin->phone) }}"
                                placeholder="{{ __('dashboard/admins.phone') }}" />
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Is Active --}}
                        <div class="col-md-6 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active"
                                    name="is_active" value="1"
                                    {{ old('is_active', $admin->is_active) ? 'checked' : '' }} />
                                <label class="form-check-label" for="is_active">{{ __('dashboard/admins.is_active') }}</label>
                            </div>
                        </div>

                        {{-- Password Section --}}
                        <div class="col-12">
                            <hr class="my-2" />
                            <h6 class="mb-3 text-muted">
                                <i class="bx bx-lock me-1"></i> {{ __('dashboard/admins.password') }}
                                <small class="text-muted fw-normal ms-1">({{ __('dashboard/admins.password_hint') }})</small>
                            </h6>
                        </div>

                        {{-- New Password --}}
                        <div class="col-md-6">
                            <label class="form-label" for="password">{{ __('dashboard/admins.password') }}</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="{{ __('dashboard/admins.password') }}" />
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-md-6">
                            <label class="form-label" for="password_confirmation">{{ __('dashboard/admins.password_confirmation') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control"
                                placeholder="{{ __('dashboard/admins.password_confirmation') }}" />
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2 btn-loader">
                            <i class="bx bx-save me-1"></i> {{ __('dashboard/admins.save') }}
                        </button>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                            {{ __('dashboard/admins.cancel') }}
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
    <div class="content-backdrop fade"></div>
</div>
@endsection
