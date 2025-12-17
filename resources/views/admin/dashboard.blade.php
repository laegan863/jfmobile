@extends('layouts.admin')

@section('title', 'Dashboard')
@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.activate-subscriber') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Activate Subscriber
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon bg-primary-soft">
                    <i class="bi bi-people text-primary"></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-label">Total Subscribers</span>
                    <h3 class="stat-value">{{ number_format($totalSubscribers) }}</h3>
                    <span class="stat-change positive">
                        <i class="bi bi-database"></i> All time
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon bg-success-soft">
                    <i class="bi bi-calendar-check text-success"></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-label">Today's Activations</span>
                    <h3 class="stat-value">{{ number_format($todaySubscribers) }}</h3>
                    <span class="stat-change positive">
                        <i class="bi bi-clock"></i> Today
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon bg-warning-soft">
                    <i class="bi bi-calendar-week text-warning"></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-label">This Week</span>
                    <h3 class="stat-value">{{ number_format($weekSubscribers) }}</h3>
                    <span class="stat-change positive">
                        <i class="bi bi-calendar"></i> Week
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon bg-info-soft">
                    <i class="bi bi-calendar-month text-info"></i>
                </div>
                <div class="stat-card-content">
                    <span class="stat-label">This Month</span>
                    <h3 class="stat-value">{{ number_format($monthSubscribers) }}</h3>
                    <span class="stat-change positive">
                        <i class="bi bi-calendar-range"></i> {{ now()->format('F') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4 mb-4">
        <!-- Recent Subscribers -->
        <div class="col-12">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Recent Activations</h5>
                    <a href="{{ route('admin.activate-subscriber') }}" class="view-all">View All <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SIM</th>
                                <th>MSISDN</th>
                                <th>Plan SOC</th>
                                <th>Label</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSubscribers as $index => $subscriber)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="order-id">{{ Str::limit($subscriber->sim, 15) }}</span></td>
                                    <td><strong>{{ $subscriber->msisdn ?? 'N/A' }}</strong></td>
                                    <td>{{ $subscriber->plan_soc }}</td>
                                    <td>{{ $subscriber->label }}</td>
                                    <td>
                                        @if ($subscriber->api_status)
                                            <span
                                                class="status-badge status-completed">{{ $subscriber->api_status }}</span>
                                        @else
                                            <span class="status-badge status-pending">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $subscriber->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                            No subscribers found. <a
                                                href="{{ route('admin.activate-subscriber') }}">Activate your first
                                                subscriber</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
