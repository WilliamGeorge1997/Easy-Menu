@extends('common::layouts.master')

@section('title', __('dashboard/branches.qr_code_builder'))

@section('content')
@php
    $isSuperAdmin = auth('admin')->user()?->hasRole(config('admin.roles.super_admin'));
    $backUrl = $isSuperAdmin ? route('admin.branches.index') : route('admin.dashboard');
@endphp
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    {{ __('dashboard/branches.qr_code_builder') }} -
                    {{ $branch->getTranslation('title', app()->getLocale()) }}
                </h5>
                <a href="{{ $backUrl }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/branches.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="mb-0">{{ __('dashboard/branches.qr_preview') }}</h6>
                                <span class="badge bg-label-primary">{{ $branch->slug }}</span>
                            </div>

                            <div class="d-flex justify-content-center align-items-center bg-light rounded p-3" style="min-height: 370px;">
                                <div id="qr-preview"></div>
                            </div>

                            <div class="row g-2 mt-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="download-name">{{ __('dashboard/branches.file_name') }}</label>
                                    <input type="text" id="download-name" class="form-control" value="branch-{{ $branch->slug }}-qr">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="download-ext">{{ __('dashboard/branches.file_type') }}</label>
                                    <select id="download-ext" class="form-select">
                                        <option value="png">PNG</option>
                                        <option value="jpeg">JPEG</option>
                                        <option value="webp">WEBP</option>
                                        <option value="svg">SVG</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" id="download-qr" class="btn btn-primary w-100">
                                        <i class="bx bx-download me-1"></i> {{ __('dashboard/branches.download_qr') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="border rounded p-3">
                            <h6 class="mb-3">{{ __('dashboard/branches.qr_options') }}</h6>
                            <div class="mb-3">
                                <label class="form-label" for="qr-data-display">{{ __('dashboard/branches.branch_url') }}</label>
                                <input type="url" id="qr-data-display" class="form-control" value="{{ $branchUrl }}" readonly>
                            </div>

                            <div class="accordion" id="qrOptionsAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingBasic">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBasic" aria-expanded="true" aria-controls="collapseBasic">
                                            {{ __('dashboard/branches.basic_options') }}
                                        </button>
                                    </h2>
                                    <div id="collapseBasic" class="accordion-collapse collapse show" aria-labelledby="headingBasic" data-bs-parent="#qrOptionsAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label" for="qr-width">{{ __('dashboard/branches.width') }}</label>
                                                    <input type="number" id="qr-width" class="form-control" min="120" max="1200" value="300">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="qr-height">{{ __('dashboard/branches.height') }}</label>
                                                    <input type="number" id="qr-height" class="form-control" min="120" max="1200" value="300">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="qr-margin">{{ __('dashboard/branches.margin') }}</label>
                                                    <input type="number" id="qr-margin" class="form-control" min="0" max="50" value="8">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="qr-shape">{{ __('dashboard/branches.qr_shape') }}</label>
                                                    <select id="qr-shape" class="form-select">
                                                        <option value="square">Square</option>
                                                        <option value="dots">Dots</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingDots">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDots" aria-expanded="false" aria-controls="collapseDots">
                                            {{ __('dashboard/branches.dots_and_corners') }}
                                        </button>
                                    </h2>
                                    <div id="collapseDots" class="accordion-collapse collapse" aria-labelledby="headingDots" data-bs-parent="#qrOptionsAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label" for="dots-color">{{ __('dashboard/branches.dots_color') }}</label>
                                                    <input type="color" id="dots-color" class="form-control form-control-color w-100" value="#111827">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="dots-type">{{ __('dashboard/branches.dots_type') }}</label>
                                                    <select id="dots-type" class="form-select">
                                                        <option value="square">Square</option>
                                                        <option value="rounded">Rounded</option>
                                                        <option value="dots">Dots</option>
                                                        <option value="classy">Classy</option>
                                                        <option value="classy-rounded">Classy Rounded</option>
                                                        <option value="extra-rounded">Extra Rounded</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch mt-1">
                                                        <input class="form-check-input" type="checkbox" id="dots-gradient-enabled">
                                                        <label class="form-check-label" for="dots-gradient-enabled">{{ __('dashboard/branches.enable_dots_gradient') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="dots-gradient-start">{{ __('dashboard/branches.gradient_start_color') }}</label>
                                                    <input type="color" id="dots-gradient-start" class="form-control form-control-color w-100" value="#111827">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="dots-gradient-end">{{ __('dashboard/branches.gradient_end_color') }}</label>
                                                    <input type="color" id="dots-gradient-end" class="form-control form-control-color w-100" value="#4f46e5">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="dots-gradient-type">{{ __('dashboard/branches.gradient_type') }}</label>
                                                    <select id="dots-gradient-type" class="form-select">
                                                        <option value="linear">Linear</option>
                                                        <option value="radial">Radial</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="dots-gradient-rotation">{{ __('dashboard/branches.gradient_rotation') }}</label>
                                                    <input type="number" id="dots-gradient-rotation" class="form-control" min="0" max="360" value="0">
                                                </div>

                                                <div class="col-6">
                                                    <label class="form-label" for="corner-square-color">{{ __('dashboard/branches.corner_square_color') }}</label>
                                                    <input type="color" id="corner-square-color" class="form-control form-control-color w-100" value="#111827">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-square-type">{{ __('dashboard/branches.corner_square_type') }}</label>
                                                    <select id="corner-square-type" class="form-select">
                                                        <option value="square">Square</option>
                                                        <option value="dot">Dot</option>
                                                        <option value="extra-rounded">Extra Rounded</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch mt-1">
                                                        <input class="form-check-input" type="checkbox" id="corner-square-gradient-enabled">
                                                        <label class="form-check-label" for="corner-square-gradient-enabled">{{ __('dashboard/branches.enable_corner_square_gradient') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-square-gradient-start">{{ __('dashboard/branches.gradient_start_color') }}</label>
                                                    <input type="color" id="corner-square-gradient-start" class="form-control form-control-color w-100" value="#111827">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-square-gradient-end">{{ __('dashboard/branches.gradient_end_color') }}</label>
                                                    <input type="color" id="corner-square-gradient-end" class="form-control form-control-color w-100" value="#06b6d4">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-square-gradient-type">{{ __('dashboard/branches.gradient_type') }}</label>
                                                    <select id="corner-square-gradient-type" class="form-select">
                                                        <option value="linear">Linear</option>
                                                        <option value="radial">Radial</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-square-gradient-rotation">{{ __('dashboard/branches.gradient_rotation') }}</label>
                                                    <input type="number" id="corner-square-gradient-rotation" class="form-control" min="0" max="360" value="0">
                                                </div>

                                                <div class="col-6">
                                                    <label class="form-label" for="corner-dot-color">{{ __('dashboard/branches.corner_dot_color') }}</label>
                                                    <input type="color" id="corner-dot-color" class="form-control form-control-color w-100" value="#111827">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-dot-type">{{ __('dashboard/branches.corner_dot_type') }}</label>
                                                    <select id="corner-dot-type" class="form-select">
                                                        <option value="square">Square</option>
                                                        <option value="dot">Dot</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch mt-1">
                                                        <input class="form-check-input" type="checkbox" id="corner-dot-gradient-enabled">
                                                        <label class="form-check-label" for="corner-dot-gradient-enabled">{{ __('dashboard/branches.enable_corner_dot_gradient') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-dot-gradient-start">{{ __('dashboard/branches.gradient_start_color') }}</label>
                                                    <input type="color" id="corner-dot-gradient-start" class="form-control form-control-color w-100" value="#111827">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-dot-gradient-end">{{ __('dashboard/branches.gradient_end_color') }}</label>
                                                    <input type="color" id="corner-dot-gradient-end" class="form-control form-control-color w-100" value="#ef4444">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-dot-gradient-type">{{ __('dashboard/branches.gradient_type') }}</label>
                                                    <select id="corner-dot-gradient-type" class="form-select">
                                                        <option value="linear">Linear</option>
                                                        <option value="radial">Radial</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="corner-dot-gradient-rotation">{{ __('dashboard/branches.gradient_rotation') }}</label>
                                                    <input type="number" id="corner-dot-gradient-rotation" class="form-control" min="0" max="360" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingBackground">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBackground" aria-expanded="false" aria-controls="collapseBackground">
                                            {{ __('dashboard/branches.background_and_logo') }}
                                        </button>
                                    </h2>
                                    <div id="collapseBackground" class="accordion-collapse collapse" aria-labelledby="headingBackground" data-bs-parent="#qrOptionsAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label" for="background-color">{{ __('dashboard/branches.background_color') }}</label>
                                                    <input type="color" id="background-color" class="form-control form-control-color w-100" value="#FFFFFF">
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check form-switch mt-1">
                                                        <input class="form-check-input" type="checkbox" id="background-gradient-enabled">
                                                        <label class="form-check-label" for="background-gradient-enabled">{{ __('dashboard/branches.enable_background_gradient') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="background-gradient-start">{{ __('dashboard/branches.gradient_start_color') }}</label>
                                                    <input type="color" id="background-gradient-start" class="form-control form-control-color w-100" value="#FFFFFF">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="background-gradient-end">{{ __('dashboard/branches.gradient_end_color') }}</label>
                                                    <input type="color" id="background-gradient-end" class="form-control form-control-color w-100" value="#f3f4f6">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="background-gradient-type">{{ __('dashboard/branches.gradient_type') }}</label>
                                                    <select id="background-gradient-type" class="form-select">
                                                        <option value="linear">Linear</option>
                                                        <option value="radial">Radial</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="background-gradient-rotation">{{ __('dashboard/branches.gradient_rotation') }}</label>
                                                    <input type="number" id="background-gradient-rotation" class="form-control" min="0" max="360" value="0">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label" for="image-size">{{ __('dashboard/branches.logo_size') }}</label>
                                                    <input type="number" id="image-size" class="form-control" min="0" max="1" step="0.05" value="0.4">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label" for="logo-url">{{ __('dashboard/branches.center_logo_url') }}</label>
                                                    <input type="url" id="logo-url" class="form-control" placeholder="https://example.com/logo.png">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label" for="logo-file">{{ __('dashboard/branches.center_logo_upload') }}</label>
                                                    <input type="file" id="logo-file" class="form-control" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml">
                                                    <small class="text-muted d-block mt-1">{{ __('dashboard/branches.logo_upload_hint') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-backdrop fade"></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/qr-code-styling@1.9.2/lib/qr-code-styling.js"></script>
<script>
(function () {
    var preview = document.getElementById('qr-preview');
    var downloadButton = document.getElementById('download-qr');
    var qrData = @json($branchUrl);
    var uploadedLogoDataUrl = null;
    var controlIds = [
        'qr-width', 'qr-height', 'qr-margin', 'qr-shape',
        'dots-color', 'dots-type', 'corner-square-color', 'corner-square-type',
        'corner-dot-color', 'corner-dot-type', 'background-color',
        'logo-url', 'image-size',
        'dots-gradient-enabled', 'dots-gradient-start', 'dots-gradient-end', 'dots-gradient-type', 'dots-gradient-rotation',
        'corner-square-gradient-enabled', 'corner-square-gradient-start', 'corner-square-gradient-end', 'corner-square-gradient-type', 'corner-square-gradient-rotation',
        'corner-dot-gradient-enabled', 'corner-dot-gradient-start', 'corner-dot-gradient-end', 'corner-dot-gradient-type', 'corner-dot-gradient-rotation',
        'background-gradient-enabled', 'background-gradient-start', 'background-gradient-end', 'background-gradient-type', 'background-gradient-rotation'
    ];

    if (!preview || typeof QRCodeStyling === 'undefined') {
        return;
    }

    function element(id) {
        return document.getElementById(id);
    }

    function options() {
        var logoUrl = element('logo-url').value.trim();
        var selectedImage = uploadedLogoDataUrl || (logoUrl.length ? logoUrl : undefined);
        var dotsGradientEnabled = element('dots-gradient-enabled').checked;
        var cornerSquareGradientEnabled = element('corner-square-gradient-enabled').checked;
        var cornerDotGradientEnabled = element('corner-dot-gradient-enabled').checked;
        var backgroundGradientEnabled = element('background-gradient-enabled').checked;

        return {
            width: Number(element('qr-width').value || 300),
            height: Number(element('qr-height').value || 300),
            type: element('qr-shape').value,
            data: qrData,
            margin: Number(element('qr-margin').value || 0),
            image: selectedImage,
            imageOptions: {
                hideBackgroundDots: true,
                imageSize: Number(element('image-size').value || 0.4),
                margin: 4,
                crossOrigin: 'anonymous'
            },
            dotsOptions: {
                color: element('dots-color').value,
                type: element('dots-type').value,
                gradient: dotsGradientEnabled ? {
                    type: element('dots-gradient-type').value,
                    rotation: Number(element('dots-gradient-rotation').value || 0),
                    colorStops: [
                        { offset: 0, color: element('dots-gradient-start').value },
                        { offset: 1, color: element('dots-gradient-end').value }
                    ]
                } : undefined
            },
            cornersSquareOptions: {
                color: element('corner-square-color').value,
                type: element('corner-square-type').value,
                gradient: cornerSquareGradientEnabled ? {
                    type: element('corner-square-gradient-type').value,
                    rotation: Number(element('corner-square-gradient-rotation').value || 0),
                    colorStops: [
                        { offset: 0, color: element('corner-square-gradient-start').value },
                        { offset: 1, color: element('corner-square-gradient-end').value }
                    ]
                } : undefined
            },
            cornersDotOptions: {
                color: element('corner-dot-color').value,
                type: element('corner-dot-type').value,
                gradient: cornerDotGradientEnabled ? {
                    type: element('corner-dot-gradient-type').value,
                    rotation: Number(element('corner-dot-gradient-rotation').value || 0),
                    colorStops: [
                        { offset: 0, color: element('corner-dot-gradient-start').value },
                        { offset: 1, color: element('corner-dot-gradient-end').value }
                    ]
                } : undefined
            },
            backgroundOptions: {
                color: element('background-color').value,
                gradient: backgroundGradientEnabled ? {
                    type: element('background-gradient-type').value,
                    rotation: Number(element('background-gradient-rotation').value || 0),
                    colorStops: [
                        { offset: 0, color: element('background-gradient-start').value },
                        { offset: 1, color: element('background-gradient-end').value }
                    ]
                } : undefined
            }
        };
    }

    var qrCode = new QRCodeStyling(options());
    qrCode.append(preview);

    function updateQrCode() {
        qrCode.update(options());
    }

    controlIds.forEach(function (id) {
        var input = element(id);
        if (!input) {
            return;
        }

        var eventName = (input.tagName === 'SELECT') ? 'change' : 'input';
        input.addEventListener(eventName, updateQrCode);
    });

    var logoFileInput = element('logo-file');
    if (logoFileInput) {
        logoFileInput.addEventListener('change', function (event) {
            var file = event.target.files && event.target.files[0];
            if (!file) {
                uploadedLogoDataUrl = null;
                updateQrCode();
                return;
            }

            var reader = new FileReader();
            reader.onload = function (loadEvent) {
                uploadedLogoDataUrl = String(loadEvent.target && loadEvent.target.result ? loadEvent.target.result : '');
                updateQrCode();
            };
            reader.onerror = function () {
                uploadedLogoDataUrl = null;
                updateQrCode();
            };
            reader.readAsDataURL(file);
        });
    }

    downloadButton.addEventListener('click', function () {
        var extension = element('download-ext').value;
        var fileName = element('download-name').value.trim() || 'branch-qr';

        qrCode.download({
            extension: extension,
            name: fileName
        });
    });
})();
</script>
@endpush
