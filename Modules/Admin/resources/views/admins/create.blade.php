@extends('common::layouts.master')

@section('title', __('dashboard/admins.create_admin'))

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
                        <i class="bx bx-user-plus"></i>
                    </span>
                    {{ __('dashboard/admins.create_admin') }}
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    {{ __('dashboard/admins.name') }} / {{ __('dashboard/admins.email') }} / {{ __('dashboard/admins.phone') }}
                </p>
            </div>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bx bx-arrow-back"></i>
                <span>{{ __('dashboard/admins.cancel') }}</span>
            </a>
        </div>

        <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-label-info rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-id-card"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/admins.create_admin') }}</h6>
                                    <small class="text-muted">{{ __('dashboard/admins.role') }} / {{ __('dashboard/admins.is_active') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="name">{{ __('dashboard/admins.name') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('name') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}"
                                            placeholder="{{ __('dashboard/admins.name') }}" />
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="email">{{ __('dashboard/admins.email') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('email') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                        <input type="email" id="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}"
                                            placeholder="{{ __('dashboard/admins.email') }}" />
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="phone">{{ __('dashboard/admins.phone') }}</label>
                                    <div class="input-group @error('phone') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                        <input type="text" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}"
                                            placeholder="{{ __('dashboard/admins.phone') }}" />
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="role_id">{{ __('dashboard/admins.role') }} <span class="text-danger">*</span></label>
                                    <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror">
                                        <option value="">{{ __('dashboard/admins.select_role') }}</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ (string) old('role_id') === (string) $role->id ? 'selected' : '' }}>
                                                {{ $role->display ?? $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 d-flex align-items-end pb-1">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded border w-100" style="background: var(--bs-light, #f8f9fa);">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                                value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                                                style="width: 2.5rem; height: 1.3rem;" />
                                        </div>
                                        <div>
                                            <label class="form-check-label fw-medium mb-0" for="is_active">{{ __('dashboard/admins.is_active') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="my-2" />
                                    <h6 class="mb-3 text-muted">
                                        <i class="bx bx-lock me-1"></i> {{ __('dashboard/admins.password') }}
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="password">{{ __('dashboard/admins.password') }} <span class="text-danger">*</span></label>
                                    <div class="input-group @error('password') has-validation @enderror">
                                        <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="{{ __('dashboard/admins.password') }}" />
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" tabindex="-1">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium" for="password_confirmation">{{ __('dashboard/admins.password_confirmation') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-lock-open-alt"></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control"
                                            placeholder="{{ __('dashboard/admins.password_confirmation') }}" />
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation" tabindex="-1">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
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
                                    <h6 class="mb-0 fw-semibold">{{ __('dashboard/admins.image') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <input type="file" id="image" name="image"
                                class="@error('image') is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/webp"
                                style="position:absolute;width:1px;height:1px;opacity:0;pointer-events:none;" />

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

                                <div id="image-preview-wrap" style="display:none;width:100%;">
                                    <img id="image-preview" src="#" alt="Preview"
                                        class="rounded-3 w-100"
                                        style="max-height:200px;object-fit:cover;" />
                                </div>
                            </label>

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

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2 btn-loader d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-save fs-5"></i>
                                <span>{{ __('dashboard/admins.save') }}</span>
                            </button>
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="padding:.75rem;">
                                <i class="bx bx-x"></i>
                                <span>{{ __('dashboard/admins.cancel') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="content-backdrop fade"></div>
</div>

@push('scripts')
<script>
(function () {
    function init() {
        var dropZone = document.getElementById('image-drop-zone');
        var fileInput = document.getElementById('image');
        var previewWrap = document.getElementById('image-preview-wrap');
        var preview = document.getElementById('image-preview');
        var content = document.getElementById('drop-zone-content');
        var removeBtn = document.getElementById('remove-image');
        var dragCounter = 0;

        if (dropZone && fileInput) {
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
                if (fileInput.files && fileInput.files.length) {
                    showPreview(fileInput.files[0]);
                }
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
        }

        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.getAttribute('data-target'));
                var icon = btn.querySelector('i');
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
@endsection
