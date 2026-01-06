@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit User: {{ $user->name }}</h1>
        <p class="text-muted mb-0">Update user information</p>
    </div>
    <div>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to User
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="profile_pic" class="form-label">Profile Picture</label>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="{{ $user->profile_pic_url }}" alt="Current Avatar" class="rounded-circle" width="64" height="64">
                            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" class="form-control w-auto @error('profile_pic') is-invalid @enderror">
                        </div>
                        @error('profile_pic')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Accepted: jpeg, png, jpg, gif, webp. Max 4MB.</div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Admin Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" 
                                               id="is_admin" name="is_admin" value="1"
                                               {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_admin">
                                            Administrator Privileges
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Administrators have full access to the admin panel and all its features.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                        
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="if(confirm('Are you sure you want to delete this user?')) { document.getElementById('delete-form').submit(); }">
                            <i class="fas fa-trash-alt me-1"></i> Delete User
                        </button>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle switch labels
    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.id === 'is_active') {
                label.textContent = this.checked ? 'Active' : 'Inactive';
            }
        });
    });
</script>
@endpush
@endsection
