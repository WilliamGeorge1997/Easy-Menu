@extends('common::layouts.master')

@section('title', __('dashboard/branches.branch_settings'))

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                <h5 class="mb-0">
                    {{ __('dashboard/branches.branch_settings') }} -
                    {{ $branch->getTranslation('title', app()->getLocale()) }}
                </h5>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/branches.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.branches.settings.update', $branch->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="email">{{ __('dashboard/branches.email') }}</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $setting->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="lang">{{ __('dashboard/branches.default_language') }}</label>
                            <select id="lang" name="lang" class="form-select @error('lang') is-invalid @enderror">
                                <option value="en" {{ old('lang', $setting->lang ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ old('lang', $setting->lang ?? 'en') === 'ar' ? 'selected' : '' }}>العربية</option>
                            </select>
                            @error('lang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @if(!empty($setting->logo))
                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('dashboard/branches.current_logo') }}</label>
                                <div class="mt-2">
                                    <img src="{{ asset('uploads/branch-settings/' . $setting->logo) }}" alt="branch logo"
                                         class="rounded" style="width:100px;height:100px;object-fit:cover;" />
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label" for="logo">{{ __('dashboard/branches.logo') }}</label>
                            <input type="file" id="logo" name="logo" accept="image/jpg,image/jpeg,image/png,image/webp"
                                class="form-control @error('logo') is-invalid @enderror">
                            @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="currency_en">{{ __('dashboard/branches.currency_en') }}</label>
                            <input type="text" id="currency_en" name="currency_en" class="form-control @error('currency_en') is-invalid @enderror"
                                value="{{ old('currency_en', $setting->getTranslation('currency', 'en', false)) }}">
                            @error('currency_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="currency_ar">{{ __('dashboard/branches.currency_ar') }}</label>
                            <input type="text" id="currency_ar" name="currency_ar" dir="rtl" class="form-control @error('currency_ar') is-invalid @enderror"
                                value="{{ old('currency_ar', $setting->getTranslation('currency', 'ar', false)) }}">
                            @error('currency_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="about_en">{{ __('dashboard/branches.about_en') }}</label>
                            <textarea id="about_en" name="about_en" rows="3"
                                class="form-control @error('about_en') is-invalid @enderror">{{ old('about_en', $setting->getTranslation('about', 'en', false)) }}</textarea>
                            @error('about_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="about_ar">{{ __('dashboard/branches.about_ar') }}</label>
                            <textarea id="about_ar" name="about_ar" rows="3" dir="rtl"
                                class="form-control @error('about_ar') is-invalid @enderror">{{ old('about_ar', $setting->getTranslation('about', 'ar', false)) }}</textarea>
                            @error('about_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="terms_en">{{ __('dashboard/branches.terms_en') }}</label>
                            <textarea id="terms_en" name="terms_en" rows="3"
                                class="form-control @error('terms_en') is-invalid @enderror">{{ old('terms_en', $setting->getTranslation('terms', 'en', false)) }}</textarea>
                            @error('terms_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="terms_ar">{{ __('dashboard/branches.terms_ar') }}</label>
                            <textarea id="terms_ar" name="terms_ar" rows="3" dir="rtl"
                                class="form-control @error('terms_ar') is-invalid @enderror">{{ old('terms_ar', $setting->getTranslation('terms', 'ar', false)) }}</textarea>
                            @error('terms_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="facebook">Facebook</label>
                            <input type="url" id="facebook" name="facebook" class="form-control @error('facebook') is-invalid @enderror"
                                value="{{ old('facebook', $setting->facebook) }}">
                            @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="youtube">YouTube</label>
                            <input type="url" id="youtube" name="youtube" class="form-control @error('youtube') is-invalid @enderror"
                                value="{{ old('youtube', $setting->youtube) }}">
                            @error('youtube')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="instagram">Instagram</label>
                            <input type="url" id="instagram" name="instagram" class="form-control @error('instagram') is-invalid @enderror"
                                value="{{ old('instagram', $setting->instagram) }}">
                            @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="x">X</label>
                            <input type="url" id="x" name="x" class="form-control @error('x') is-invalid @enderror"
                                value="{{ old('x', $setting->x) }}">
                            @error('x')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="snapchat">Snapchat</label>
                            <input type="url" id="snapchat" name="snapchat" class="form-control @error('snapchat') is-invalid @enderror"
                                value="{{ old('snapchat', $setting->snapchat) }}">
                            @error('snapchat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="tiktok">TikTok</label>
                            <input type="url" id="tiktok" name="tiktok" class="form-control @error('tiktok') is-invalid @enderror"
                                value="{{ old('tiktok', $setting->tiktok) }}">
                            @error('tiktok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="whatsapp">{{ __('dashboard/branches.whatsapp') }}</label>
                            <input type="text" id="whatsapp" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                                value="{{ old('whatsapp', $setting->whatsapp) }}">
                            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="telegram">{{ __('dashboard/branches.telegram') }}</label>
                            <input type="text" id="telegram" name="telegram" class="form-control @error('telegram') is-invalid @enderror"
                                value="{{ old('telegram', $setting->telegram) }}">
                            @error('telegram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="wifi_username">{{ __('dashboard/branches.wifi_username') }}</label>
                            <input type="text" id="wifi_username" name="wifi_username" class="form-control @error('wifi_username') is-invalid @enderror"
                                value="{{ old('wifi_username', $setting->wifi_username) }}">
                            @error('wifi_username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="wifi_password">{{ __('dashboard/branches.wifi_password') }}</label>
                            <input type="text" id="wifi_password" name="wifi_password" class="form-control @error('wifi_password') is-invalid @enderror"
                                value="{{ old('wifi_password', $setting->wifi_password) }}">
                            @error('wifi_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
