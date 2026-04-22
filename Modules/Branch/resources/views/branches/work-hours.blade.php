@extends('common::layouts.master')

@section('title', __('dashboard/branches.working_hours'))

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
                    {{ __('dashboard/branches.working_hours') }} -
                    {{ $branch->getTranslation('title', app()->getLocale()) }}
                </h5>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('dashboard/branches.cancel') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.branches.work-hours.update', $branch->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        @foreach($days as $index => $day)
                            @php
                                $stored = $workHours->get($day);
                                $oldRow = old("hours.$index", []);
                                $isClosed = (bool) ($oldRow['is_closed'] ?? ($stored ? 0 : 1));
                                $from = $oldRow['from'] ?? ($stored?->from ? \Illuminate\Support\Str::substr($stored->from, 0, 5) : '');
                                $to = $oldRow['to'] ?? ($stored?->to ? \Illuminate\Support\Str::substr($stored->to, 0, 5) : '');
                            @endphp

                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('dashboard/branches.' . $day) }}</label>
                                            <input type="hidden" name="hours[{{ $index }}][day]" value="{{ $day }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="from_{{ $day }}" class="form-label">{{ __('dashboard/branches.from') }}</label>
                                            <input
                                                type="time"
                                                id="from_{{ $day }}"
                                                name="hours[{{ $index }}][from]"
                                                value="{{ $from }}"
                                                class="form-control @error("hours.$index.from") is-invalid @enderror"
                                            >
                                            @error("hours.$index.from")<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="to_{{ $day }}" class="form-label">{{ __('dashboard/branches.to') }}</label>
                                            <input
                                                type="time"
                                                id="to_{{ $day }}"
                                                name="hours[{{ $index }}][to]"
                                                value="{{ $to }}"
                                                class="form-control @error("hours.$index.to") is-invalid @enderror"
                                            >
                                            @error("hours.$index.to")<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check mt-4">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    value="1"
                                                    id="is_closed_{{ $day }}"
                                                    name="hours[{{ $index }}][is_closed]"
                                                    {{ $isClosed ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label" for="is_closed_{{ $day }}">
                                                    {{ __('dashboard/branches.closed') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
