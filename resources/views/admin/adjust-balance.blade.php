@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back! Here's what's happening with your store today.</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-outline-secondary">
                <i class="bi bi-download me-2"></i>Export
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activateSubscriberModal">
                <i class="bi bi-plus-lg me-2"></i>Activate Subscriber
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-12">
            <div class="content-card">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Recent Activity</h5>
                    <a href="#" class="view-all">View All <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Created Date</td>
                                <td>SIM</td>
                                <td>ZIP</td>
                                <td>PLAN SOC</td>
                                <td>IMEI</td>
                                <td>LABEL</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscribers as $index => $subscriber)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $subscriber->created_at->format('m/d/Y') }}</td>
                                    <td>{{ $subscriber->sim }}</td>
                                    <td>{{ $subscriber->zip }}</td>
                                    <td>{{ $subscriber->plan_soc }}</td>
                                    <td>{{ $subscriber->imei }}</td>
                                    <td>{{ $subscriber->label }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary p-1 px-2"
                                            onclick="viewSubscriber({{ $subscriber->id }})" data-bs-toggle="modal"
                                            data-bs-target="#viewSubscriberModal">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No subscribers found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Subscriber Modal -->
    <div class="modal fade" id="activateSubscriberModal" tabindex="-1" aria-labelledby="activateSubscriberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activateSubscriberModalLabel">Activate Subscriber</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="activateSubscriberForm" method="POST" action="{{ route('admin.activate-subscriber.store') }}">
                    @csrf
                    <div class="modal-body">
                        <!-- Error Alert -->
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row g-3">
                            <!-- Subscriber Information -->
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">Adjust Balance Information</h6>
                            </div>

                            <div class="col-md-6">
                                <label for="msisdn" class="form-label">MSISDN</label>
                                <input type="text" class="form-control" id="msisdn" name="msisdn"
                                    placeholder="Enter MSISDN" value="{{ old('msisdn') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="iccid" class="form-label">ICCID</label>
                                <input type="text" class="form-control" id="iccid" name="iccid"
                                    placeholder="Enter ICCID" value="{{ old('iccid') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="uom" class="form-label">UOM</label>
                                <input type="text" class="form-control" id="uom" name="uom"
                                    placeholder="Enter UOM" value="{{ old('uom') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="bucketValue" class="form-label">Bucket Value</label>
                                <input type="number" class="form-control" id="bucketValue" name="bucketValue"
                                    placeholder="Enter Bucket Value" value="{{ old('bucketValue') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="bucketForDataTopUp" class="form-label">Bucket For Data Top Up</label>
                                <input type="text" class="form-control" id="bucketForDataTopUp" name="bucketForDataTopUp"
                                    placeholder="Enter Bucket For Data Top Up" value="{{ old('bucketForDataTopUp') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
