@extends('common::layouts.master')

@section('title', __('dashboard/branches.create_branch'))

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Alerts --}}
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

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm bg-label-primary rounded d-flex align-items-center justify-content-center">
                        <i class="bx bx-store-alt"></i>
                    </span>
                    {{ __('dashboard/branches.create_branch') }}
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/branches.create_branch_subtitle') }}
                </p>
            </div>
            <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-arrow-back"></i>
                <span>{{ __('dashboard/branches.cancel') }}</span>
            </a>
        </div>

        @php $isAr = app()->isLocale('ar'); @endphp
        <form action="{{ route('admin.branches.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                {{-- Left Column: Branch Info --}}
                <div class="col-lg-8">

                    {{-- Branch Details Card --}}
                    <div class="card mb-4">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-info rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-building-house"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/branches.create_branch') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/branches.branch_name_location_details') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">

                                {{-- Title EN --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="title_en">
                                        {{ __('dashboard/branches.title_en') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div dir="ltr" class="input-group @error('title_en') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" id="title_en" name="title_en"
                                            class="form-control @error('title_en') is-invalid @enderror"
                                            value="{{ old('title_en') }}"
                                            placeholder="{{ __('dashboard/branches.title_en') }}"
                                            dir="ltr" />
                                        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Title AR --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="title_ar">
                                        {{ __('dashboard/branches.title_ar') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div dir="ltr" class="input-group @error('title_ar') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-text"></i></span>
                                        <input type="text" id="title_ar" name="title_ar"
                                            class="form-control @error('title_ar') is-invalid @enderror"
                                            value="{{ old('title_ar') }}"
                                            placeholder="{{ __('dashboard/branches.title_ar') }}"
                                            dir="ltr" />
                                        @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="phone">
                                        {{ __('dashboard/branches.phone') }}
                                    </label>
                                    <div dir="ltr" class="input-group @error('phone') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                        <input type="text" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}"
                                            placeholder="{{ __('dashboard/branches.phone') }}"
                                            dir="ltr" />
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Address EN --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="address_en">
                                        {{ __('dashboard/branches.address_en') }}
                                    </label>
                                    <div dir="ltr" class="input-group @error('address_en') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                                        <input type="text" id="address_en" name="address_en"
                                            class="form-control @error('address_en') is-invalid @enderror"
                                            value="{{ old('address_en') }}"
                                            placeholder="{{ __('dashboard/branches.address_en') }}"
                                            dir="ltr" />
                                        @error('address_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Address AR --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="address_ar">
                                        {{ __('dashboard/branches.address_ar') }}
                                    </label>
                                    <div dir="ltr" class="input-group @error('address_ar') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                                        <input type="text" id="address_ar" name="address_ar"
                                            class="form-control @error('address_ar') is-invalid @enderror"
                                            value="{{ old('address_ar') }}"
                                            placeholder="{{ __('dashboard/branches.address_ar') }}"
                                            dir="ltr" />
                                        @error('address_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Is Active --}}
                                <div class="col-md-6 d-flex align-items-end pb-1">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border w-100" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                                value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                                                style="width: 2.5rem; height: 1.3rem;" />
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="is_active">
                                                {{ __('dashboard/branches.is_active') }}
                                            </label>
                                            <div class="text-muted" style="font-size: 0.78rem;">{{ __('dashboard/branches.enable_branch_visible') }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Branch Manager Card --}}
                    <div class="card">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-warning rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-user-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/branches.branch_manager_account') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/branches.manager_login_credentials') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">

                                {{-- Admin Name --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="admin_name">
                                        {{ __('dashboard/branches.admin_name') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div dir="ltr" class="input-group @error('admin_name') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                        <input type="text" id="admin_name" name="admin_name"
                                            class="form-control @error('admin_name') is-invalid @enderror"
                                            value="{{ old('admin_name') }}"
                                            placeholder="{{ __('dashboard/branches.admin_name') }}"
                                            dir="{{ $isAr ? 'rtl' : 'ltr' }}" />
                                        @error('admin_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Admin Email --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="admin_email">
                                        {{ __('dashboard/branches.admin_email') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div dir="ltr" class="input-group @error('admin_email') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                        <input type="email" id="admin_email" name="admin_email"
                                            class="form-control @error('admin_email') is-invalid @enderror"
                                            value="{{ old('admin_email') }}"
                                            placeholder="{{ __('dashboard/branches.admin_email') }}"
                                            dir="ltr" />
                                        @error('admin_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Admin Phone --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="admin_phone">
                                        {{ __('dashboard/branches.admin_phone') }}
                                    </label>
                                    <div dir="ltr" class="input-group @error('admin_phone') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-mobile-alt"></i></span>
                                        <input type="text" id="admin_phone" name="admin_phone"
                                            class="form-control @error('admin_phone') is-invalid @enderror"
                                            value="{{ old('admin_phone') }}"
                                            placeholder="{{ __('dashboard/branches.admin_phone') }}"
                                            dir="ltr" />
                                        @error('admin_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Admin Password --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="admin_password">
                                        {{ __('dashboard/branches.admin_password') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div dir="ltr" class="input-group @error('admin_password') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                        <input type="password" id="admin_password" name="admin_password"
                                            class="form-control @error('admin_password') is-invalid @enderror"
                                            placeholder="{{ __('dashboard/branches.admin_password') }}"
                                            dir="ltr" />
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="admin_password" tabindex="-1">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                        @error('admin_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- Admin Password Confirmation --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="admin_password_confirmation">
                                        {{ __('dashboard/branches.admin_password_confirmation') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div dir="ltr" class="input-group">
                                        <span class="input-group-text"><i class="bx bx-lock-open-alt"></i></span>
                                        <input type="password" id="admin_password_confirmation" name="admin_password_confirmation"
                                            class="form-control"
                                            placeholder="{{ __('dashboard/branches.admin_password_confirmation') }}"
                                            dir="ltr" />
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="admin_password_confirmation" tabindex="-1">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Image & Actions --}}
                <div class="col-lg-4">

                    {{-- Image Upload Card --}}
                    <div class="card mb-4">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-success rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-image-add"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/branches.image') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/branches.branch_cover_photo') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">

                            {{-- File input (hidden, triggered by label click or JS drop) --}}
                            <input type="file" id="image" name="image"
                                class="@error('image') is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/webp"
                                style="position:absolute;width:1px;height:1px;opacity:0;pointer-events:none;" />

                            {{-- Drop Zone — is a <label> so clicking it natively opens the file dialog --}}
                            <label for="image" id="image-drop-zone"
                                style="
                                    display:flex;
                                    flex-direction:column;
                                    align-items:center;
                                    justify-content:center;
                                    border: 2.5px dashed #a0aec0;
                                    border-radius: .75rem;
                                    min-height: 220px;
                                    cursor: pointer;
                                    transition: border-color .2s, background .2s;
                                    background: var(--bs-light, #f8f9fa);
                                    padding: 1.5rem;
                                    text-align: center;
                                    user-select: none;
                                    width:100%;
                                    margin-bottom:0;
                                ">

                                <div id="drop-zone-content">
                                    <div class="mb-3">
                                        <span style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:rgba(105,108,255,.12);">
                                            <i class="bx bx-cloud-upload" style="font-size:2rem;color:#696cff;"></i>
                                        </span>
                                    </div>
                                    <p class="fw-semibold mb-1" style="font-size:.95rem;">{{ __('dashboard/branches.drop_image_here') }}</p>
                                    <p class="text-muted mb-3" style="font-size:.8rem;">{{ __('dashboard/branches.or_click_to_browse') }}</p>
                                    <span class="badge bg-label-secondary px-3 py-2" style="font-size:.75rem;">
                                        JPG &bull; JPEG &bull; PNG &bull; WEBP
                                    </span>
                                </div>

                                {{-- Preview (hidden until image selected) --}}
                                <div id="image-preview-wrap" style="display:none;width:100%;">
                                    <img id="image-preview" src="#" alt="Preview"
                                        class="rounded-3 w-100"
                                        style="max-height:200px;object-fit:cover;" />
                                </div>
                            </label>

                            {{-- Remove button lives outside the label so it doesn't re-open the dialog --}}
                            <button type="button" id="remove-image"
                                class="btn btn-sm btn-danger mt-2 w-100 d-flex align-items-center justify-content-center gap-1"
                                disabled>
                                <i class="bx bx-trash"></i> {{ __('dashboard/branches.remove_image') }}
                            </button>

                            @error('image')
                                <div class="text-danger mt-2" style="font-size:.85rem;">
                                    <i class="bx bx-error-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror

                        </div>
                    </div>

                    {{-- Actions Card --}}
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2 btn-loader d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-save fs-5"></i>
                                <span>{{ __('dashboard/branches.save') }}</span>
                            </button>
                            <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-x"></i>
                                <span>{{ __('dashboard/branches.cancel') }}</span>
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
    function init() {
        var dropZone    = document.getElementById('image-drop-zone');
        var fileInput   = document.getElementById('image');
        var previewWrap = document.getElementById('image-preview-wrap');
        var preview     = document.getElementById('image-preview');
        var content     = document.getElementById('drop-zone-content');
        var removeBtn   = document.getElementById('remove-image');
        var dragCounter = 0;

        if (!dropZone || !fileInput) return;

        function showPreview(file) {
            if (!file || !file.type.startsWith('image/')) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                previewWrap.style.display  = 'block';
                content.style.display      = 'none';
                removeBtn.disabled         = false;
                dropZone.style.borderColor = '#696cff';
                dropZone.style.background  = 'rgba(105,108,255,.06)';
            };
            reader.readAsDataURL(file);
        }

        function resetDropZone() {
            preview.src                = '#';
            previewWrap.style.display  = 'none';
            content.style.display      = 'block';
            removeBtn.disabled         = true;
            fileInput.value            = '';
            dropZone.style.borderColor = '#a0aec0';
            dropZone.style.background  = 'var(--bs-light, #f8f9fa)';
        }

        // File chosen via native dialog (label click)
        fileInput.addEventListener('change', function () {
            if (fileInput.files && fileInput.files.length) {
                showPreview(fileInput.files[0]);
            }
        });

        // Remove button — stop propagation so label doesn't re-open dialog
        removeBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            resetDropZone();
        });

        // Drag events on the label/drop zone
        dropZone.addEventListener('dragenter', function (e) {
            e.preventDefault();
            dragCounter++;
            dropZone.style.borderColor = '#696cff';
            dropZone.style.background  = 'rgba(105,108,255,.06)';
        });

        dropZone.addEventListener('dragover', function (e) {
            e.preventDefault();
        });

        dropZone.addEventListener('dragleave', function () {
            dragCounter--;
            if (dragCounter <= 0) {
                dragCounter = 0;
                dropZone.style.borderColor = '#a0aec0';
                dropZone.style.background  = 'var(--bs-light, #f8f9fa)';
            }
        });

        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            dragCounter = 0;
            dropZone.style.borderColor = '#a0aec0';
            dropZone.style.background  = 'var(--bs-light, #f8f9fa)';
            var file = e.dataTransfer && e.dataTransfer.files[0];
            if (file) {
                try {
                    var dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;
                } catch(err) {}
                showPreview(file);
            }
        });

        /* ── Password Toggle ───────────────────────────────────── */
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.getAttribute('data-target'));
                var icon  = btn.querySelector('i');
                if (!input) return;
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bx-hide');
                    icon.classList.add('bx-show');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bx-show');
                    icon.classList.add('bx-hide');
                }
            });
        });
    }

    // Run immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush

@endsection
