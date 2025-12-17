@extends('layouts.admin')

@section('title', 'Subscriber Details')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Subscriber Details</h1>
            <p class="page-subtitle">View subscriber information</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.activate-subscriber') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Subscriber Information Card -->
        <div class="col-md-6">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Subscriber Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">SIM</label>
                            <p class="mb-0">{{ $subscriber->sim }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">ZIP</label>
                            <p class="mb-0">{{ $subscriber->zip }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Plan SOC</label>
                            <p class="mb-0">{{ $subscriber->plan_soc }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">IMEI</label>
                            <p class="mb-0">{{ $subscriber->imei ?? 'N/A' }}</p>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Label</label>
                            <p class="mb-0">{{ $subscriber->label }}</p>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Created At</label>
                            <p class="mb-0">{{ $subscriber->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- E911 Address Card -->
        <div class="col-md-6">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">E911 Address</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Street Address 1</label>
                            <p class="mb-0">{{ $subscriber->e911_address_street1 }}</p>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Street Address 2</label>
                            <p class="mb-0">{{ $subscriber->e911_address_street2 ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">City</label>
                            <p class="mb-0">{{ $subscriber->e911_address_city }}</p>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted">State</label>
                            <p class="mb-0">{{ $subscriber->e911_address_state }}</p>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted">ZIP Code</label>
                            <p class="mb-0">{{ $subscriber->e911_address_zip }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Response Data Card -->
        <div class="col-12">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">API Response Data</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted">Transaction ID</label>
                            <p class="mb-0">{{ $subscriber->transaction_id ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted">MSISDN</label>
                            <p class="mb-0">{{ $subscriber->msisdn ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted">ICCID</label>
                            <p class="mb-0">{{ $subscriber->iccid ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted">Account ID</label>
                            <p class="mb-0">{{ $subscriber->account_id ?? 'N/A' }}</p>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">API Status</label>
                            <p class="mb-0">
                                @if ($subscriber->api_status)
                                    <span class="badge bg-success">{{ $subscriber->api_status }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
