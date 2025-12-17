@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Activate Subscriber</h1>
            <p class="page-subtitle">Welcome Back! </p>
        </div>
        <div class="page-actions">
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importSubscriberModal">
                <i class="bi bi-upload me-2"></i>Import
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
                                        <a href="{{ route('admin.subscribers.show', $subscriber->id) }}"
                                            class="btn btn-sm btn-primary p-1 px-2">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
                                <h6 class="fw-bold mb-3">Subscriber Information</h6>
                            </div>

                            <div class="col-md-6">
                                <label for="sim" class="form-label">SIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sim" name="sim"
                                    value="{{ old('sim') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="zip" class="form-label">ZIP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="zip" name="zip"
                                    value="{{ old('zip') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="plan_soc" class="form-label">Plan SOC <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="plan_soc" name="plan_soc"
                                    value="{{ old('plan_soc') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="imei" class="form-label">IMEI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="imei" name="imei"
                                    value="{{ old('imei') }}" required>
                            </div>

                            <div class="col-12">
                                <label for="label" class="form-label">Label <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="label" name="label"
                                    value="{{ old('label') }}" required>
                            </div>

                            <!-- E911 Address Information -->
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold mb-3">E911 Address</h6>
                            </div>

                            <div class="col-12">
                                <label for="e911AddressStreet1" class="form-label">Street Address 1 <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="e911AddressStreet1"
                                    name="e911AddressStreet1" value="{{ old('e911AddressStreet1') }}" required>
                            </div>

                            <div class="col-12">
                                <label for="e911AddressStreet2" class="form-label">Street Address 2</label>
                                <input type="text" class="form-control" id="e911AddressStreet2"
                                    name="e911AddressStreet2" value="{{ old('e911AddressStreet2') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="e911AddressCity" class="form-label">City <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="e911AddressCity" name="e911AddressCity"
                                    value="{{ old('e911AddressCity') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label for="e911AddressState" class="form-label">State <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="e911AddressState" name="e911AddressState"
                                    value="{{ old('e911AddressState') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label for="e911AddressZip" class="form-label">ZIP Code <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="e911AddressZip" name="e911AddressZip"
                                    value="{{ old('e911AddressZip') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Activate Subscriber</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Subscriber Modal -->
    <div class="modal fade" id="importSubscriberModal" tabindex="-1" aria-labelledby="importSubscriberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importSubscriberModalLabel">Import Subscribers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importSubscriberForm" method="POST" action="{{ route('admin.activate-subscriber.import') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Import Instructions -->
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Import Instructions</h6>
                            <p class="mb-2">Upload a CSV or Excel file with the following columns:</p>
                            <ul class="mb-2">
                                <li><strong>Customer Name</strong> - Customer name/label (required)</li>
                                <li><strong>SIM Number (ICCID)</strong> - SIM number (required)</li>
                                <li><strong>IMEI</strong> - IMEI number (required)</li>
                                <li><strong>Rate Plan</strong> - Plan SOC (required)</li>
                                <li><strong>E911 Street Address</strong> - Street Address (required)</li>
                                <li><strong>E911 City</strong> - City (required)</li>
                                <li><strong>E911 State</strong> - State (required)</li>
                                <li><strong>E911 ZIP</strong> - ZIP Code (required)</li>
                                <li><strong>Customer Email</strong> - Email (optional)</li>
                            </ul>
                            <a href="{{ route('admin.activate-subscriber.template') }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-2"></i>Download Template
                            </a>
                        </div>

                        <!-- File Upload -->
                        <div class="mb-3">
                            <label for="importFile" class="form-label">Select File <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="importFile" name="import_file"
                                accept=".csv,.xlsx,.xls" required>
                            <div class="form-text">Accepted formats: CSV, Excel (.xlsx, .xls). Maximum file size: 20MB
                            </div>
                        </div>

                        <!-- Import Options -->
                        <div class="mb-3">
                            <label class="form-label">Import Options</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skipDuplicates"
                                    name="skip_duplicates" value="1" checked>
                                <label class="form-check-label" for="skipDuplicates">
                                    Skip duplicate entries (based on SIM number)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="validateOnly" name="validate_only"
                                    value="1">
                                <label class="form-check-label" for="validateOnly">
                                    Validate only (don't import, just check for errors)
                                </label>
                            </div>
                        </div>

                        <!-- Progress Section (hidden by default) -->
                        <div id="importProgress" class="d-none">
                            <hr>
                            <h6 class="fw-bold mb-3">Import Progress</h6>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 0%;" id="importProgressBar">0%</div>
                            </div>
                            <p class="text-muted small" id="importStatus">Preparing to import...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="importBtn">
                            <i class="bi bi-upload me-2"></i>Import Subscribers
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Import form handling
        document.getElementById('importSubscriberForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('importFile');
            const file = fileInput.files[0];

            if (!file) {
                e.preventDefault();
                alert('Please select a file to import.');
                return;
            }

            // Check file size (20MB max)
            const maxSize = 20 * 1024 * 1024;
            if (file.size > maxSize) {
                e.preventDefault();
                alert('File size exceeds 20MB limit. Please select a smaller file.');
                return;
            }

            // Check file type
            const allowedTypes = ['.csv', '.xlsx', '.xls'];
            const fileName = file.name.toLowerCase();
            const isValidType = allowedTypes.some(type => fileName.endsWith(type));

            if (!isValidType) {
                e.preventDefault();
                alert('Invalid file type. Please upload a CSV or Excel file.');
                return;
            }

            // Show progress section
            document.getElementById('importProgress').classList.remove('d-none');
            document.getElementById('importBtn').disabled = true;
            document.getElementById('importBtn').innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';
        });

        // Reset import modal when closed
        document.getElementById('importSubscriberModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('importSubscriberForm').reset();
            document.getElementById('importProgress').classList.add('d-none');
            document.getElementById('importBtn').disabled = false;
            document.getElementById('importBtn').innerHTML = '<i class="bi bi-upload me-2"></i>Import Subscribers';
            document.getElementById('importProgressBar').style.width = '0%';
            document.getElementById('importProgressBar').textContent = '0%';
        });

        // Auto-open modal if there are errors
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('activateSubscriberModal'));
                modal.show();
            });
        @endif
    </script>
@endpush
