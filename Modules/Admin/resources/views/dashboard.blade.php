@extends('common::layouts.master')

@section('title', __('dashboard/dashboard.title'))

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">{{ __('dashboard/dashboard.overview') }}</h4>
                    <p class="mb-0 text-muted">
                        {{ $isSuperAdmin ? __('dashboard/dashboard.global_statistics') : __('dashboard/dashboard.branch_snapshot') }}
                    </p>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <span class="d-block text-muted mb-1">{{ __('dashboard/dashboard.products') }}</span>
                            <h3 class="mb-1">{{ $stats['products'] }}</h3>
                            <small class="text-success">{{ __('dashboard/dashboard.active') }}: {{ $stats['active_products'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <span class="d-block text-muted mb-1">{{ __('dashboard/dashboard.inactive_products') }}</span>
                            <h3 class="mb-1">{{ $stats['inactive_products'] }}</h3>
                            <small class="text-muted">{{ __('dashboard/dashboard.needs_attention') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <span class="d-block text-muted mb-1">{{ __('dashboard/dashboard.categories') }}</span>
                            <h3 class="mb-1">{{ $stats['categories'] }}</h3>
                            <small class="text-info">{{ __('dashboard/dashboard.active') }}: {{ $stats['active_categories'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <span class="d-block text-muted mb-1">{{ $isSuperAdmin ? __('dashboard/dashboard.branches') : __('dashboard/dashboard.managed_branches') }}</span>
                            <h3 class="mb-1">{{ $stats['branches'] }}</h3>
                            <small class="text-muted">{{ $isSuperAdmin ? __('dashboard/dashboard.across_admins') : __('dashboard/dashboard.your_scope') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ __('dashboard/dashboard.products_last_6_months') }}</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="productsMonthlyChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('dashboard/dashboard.products_by_category') }}</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryDistributionChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('dashboard/dashboard.last_added_products') }}</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">{{ __('dashboard/dashboard.view_all') }}</a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('dashboard/dashboard.product') }}</th>
                                <th>{{ __('dashboard/dashboard.category') }}</th>
                                @if($isSuperAdmin)
                                    <th>{{ __('dashboard/dashboard.branch') }}</th>
                                @endif
                                <th>{{ __('dashboard/dashboard.status') }}</th>
                                <th>{{ __('dashboard/dashboard.created_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProducts as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->getTranslation('title', app()->getLocale()) }}</td>
                                    <td>{{ $product->category?->getTranslation('title', app()->getLocale()) ?? '-' }}</td>
                                    @if($isSuperAdmin)
                                        <td>{{ $product->branch?->getTranslation('title', app()->getLocale()) ?? '-' }}</td>
                                    @endif
                                    <td>
                                        <span class="badge {{ $product->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $product->is_active ? __('dashboard/dashboard.active') : __('dashboard/dashboard.inactive') }}
                                        </span>
                                    </td>
                                    <td>{{ $product->created_at?->format('Y-m-d h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isSuperAdmin ? 6 : 5 }}" class="text-center py-4">{{ __('dashboard/dashboard.no_products_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="content-backdrop fade"></div>
    </div>
@endsection

@section('js')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyLabels = @json($monthlyProducts->pluck('label'));
        const monthlyCounts = @json($monthlyProducts->pluck('count'));
        const categoryLabels = @json($categoriesWithProductCount->map(fn($category) => $category->getTranslation('title', app()->getLocale())));
        const categoryCounts = @json($categoriesWithProductCount->pluck('products_count'));

        const monthlyCtx = document.getElementById('productsMonthlyChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: @json(__('dashboard/dashboard.products')),
                        data: monthlyCounts,
                        borderColor: '#696cff',
                        backgroundColor: 'rgba(105, 108, 255, 0.15)',
                        fill: true,
                        tension: 0.35
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        const categoryCtx = document.getElementById('categoryDistributionChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryCounts,
                        backgroundColor: ['#696cff', '#71dd37', '#03c3ec', '#ffab00', '#ff3e1d', '#8592a3']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
@endsection
