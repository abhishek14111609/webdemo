@extends('admin.layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
                <h4 class="page-title">Site Settings</h4>
            </div>
        </div>
    </div>

    @include('admin.partials.alert')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        
                        <ul class="nav nav-tabs nav-bordered mb-3">
                            <li class="nav-item">
                                <a href="#general" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                    <i class="fas fa-cog me-1"></i> General
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#localization" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="fas fa-globe me-1"></i> Localization
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#maintenance" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="fas fa-tools me-1"></i> Maintenance
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- General Settings -->
                            <div class="tab-pane show active" id="general">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                                                   id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
                                            @error('site_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="site_email" class="form-label">Site Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('site_email') is-invalid @enderror" 
                                                   id="site_email" name="site_email" value="{{ old('site_email', $settings['site_email']) }}" required>
                                            @error('site_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="site_phone" class="form-label">Site Phone</label>
                                            <input type="text" class="form-control @error('site_phone') is-invalid @enderror" 
                                                   id="site_phone" name="site_phone" value="{{ old('site_phone', $settings['site_phone']) }}">
                                            @error('site_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="items_per_page" class="form-label">Items Per Page <span class="text-danger">*</span></label>
                                            <input type="number" min="5" max="100" class="form-control @error('items_per_page') is-invalid @enderror" 
                                                   id="items_per_page" name="items_per_page" value="{{ old('items_per_page', $settings['items_per_page']) }}" required>
                                            @error('items_per_page')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="site_address" class="form-label">Site Address</label>
                                    <textarea class="form-control @error('site_address') is-invalid @enderror" 
                                              id="site_address" name="site_address" rows="2">{{ old('site_address', $settings['site_address']) }}</textarea>
                                    @error('site_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Localization Settings -->
                            <div class="tab-pane" id="localization">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="timezone" class="form-label">Timezone <span class="text-danger">*</span></label>
                                            <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone" required>
                                                @foreach($timezones as $timezone)
                                                    <option value="{{ $timezone }}" {{ old('timezone', $settings['timezone']) == $timezone ? 'selected' : '' }}>
                                                        {{ $timezone }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('timezone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="currency" class="form-label">Currency Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('currency') is-invalid @enderror" 
                                                   id="currency" name="currency" value="{{ old('currency', $settings['currency']) }}" 
                                                   maxlength="3" required>
                                            @error('currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="currency_symbol" class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('currency_symbol') is-invalid @enderror" 
                                                   id="currency_symbol" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" 
                                                   maxlength="5" required>
                                            @error('currency_symbol')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="date_format" class="form-label">Date Format <span class="text-danger">*</span></label>
                                            <select class="form-select @error('date_format') is-invalid @enderror" id="date_format" name="date_format" required>
                                                <option value="Y-m-d" {{ old('date_format', $settings['date_format']) == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                                <option value="m/d/Y" {{ old('date_format', $settings['date_format']) == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                                <option value="d/m/Y" {{ old('date_format', $settings['date_format']) == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                                <option value="d-m-Y" {{ old('date_format', $settings['date_format']) == 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                                            </select>
                                            @error('date_format')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="time_format" class="form-label">Time Format <span class="text-danger">*</span></label>
                                            <select class="form-select @error('time_format') is-invalid @enderror" id="time_format" name="time_format" required>
                                                <option value="H:i:s" {{ old('time_format', $settings['time_format']) == 'H:i:s' ? 'selected' : '' }}>24-hour (14:30:00)</option>
                                                <option value="h:i:s A" {{ old('time_format', $settings['time_format']) == 'h:i:s A' ? 'selected' : '' }}>12-hour (02:30:00 PM)</option>
                                            </select>
                                            @error('time_format')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Maintenance Settings -->
                            <div class="tab-pane" id="maintenance">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="maintenance_mode" 
                                               name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="maintenance_mode">Enable Maintenance Mode</label>
                                    </div>
                                    <small class="text-muted">When enabled, only administrators can access the site.</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="maintenance_message" class="form-label">Maintenance Message</label>
                                    <textarea class="form-control @error('maintenance_message') is-invalid @enderror" 
                                              id="maintenance_message" name="maintenance_message" rows="3">{{ old('maintenance_message', $settings['maintenance_message']) }}</textarea>
                                    @error('maintenance_message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize form validation
    (function() {
        'use strict';
        
        // Fetch the form element we want to validate
        const form = document.querySelector('form');
        
        // Add validation on form submission
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    })();
</script>
@endpush