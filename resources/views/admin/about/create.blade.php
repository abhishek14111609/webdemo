@extends('admin.layout')

@section('title', 'Create About Us Content')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create About Us Content</h4>
                    <a href="{{ route('admin.about.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.about.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="subtitle" class="form-label">Subtitle</label>
                                    <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                                           id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                                    @error('subtitle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mission" class="form-label">Mission</label>
                                            <textarea class="form-control @error('mission') is-invalid @enderror"
                                                      id="mission" name="mission" rows="3">{{ old('mission') }}</textarea>
                                            @error('mission')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="vision" class="form-label">Vision</label>
                                            <textarea class="form-control @error('vision') is-invalid @enderror"
                                                      id="vision" name="vision" rows="3">{{ old('vision') }}</textarea>
                                            @error('vision')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="history" class="form-label">History</label>
                                    <textarea class="form-control @error('history') is-invalid @enderror"
                                              id="history" name="history" rows="4">{{ old('history') }}</textarea>
                                    @error('history')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Featured Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Max file size: 2MB. Formats: JPEG, PNG, JPG, GIF</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                                               {{ old('status', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Values Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Our Values</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-value">
                                    <i class="fas fa-plus"></i> Add Value
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="values-container">
                                    <div class="value-item mb-3">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="values[0][title]"
                                                       placeholder="Value Title" value="{{ old('values.0.title') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <textarea class="form-control" name="values[0][description]"
                                                          placeholder="Value Description" rows="2">{{ old('values.0.description') }}</textarea>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-value">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Members Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Team Members</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-team-member">
                                    <i class="fas fa-plus"></i> Add Team Member
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="team-container">
                                    <div class="team-member-item mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" name="team_members[0][name]"
                                                       placeholder="Full Name" value="{{ old('team_members.0.name') }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" name="team_members[0][position]"
                                                       placeholder="Position" value="{{ old('team_members.0.position') }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="file" class="form-control" name="team_members[0][image]"
                                                       accept="image/*">
                                            </div>
                                            <div class="col-md-2">
                                                <textarea class="form-control" name="team_members[0][bio]"
                                                          placeholder="Short Bio" rows="2">{{ old('team_members.0.bio') }}</textarea>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-team-member">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.about.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create About Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('add-value').addEventListener('click', function() {
    const container = document.getElementById('values-container');
    const index = container.children.length;
    const valueItem = document.createElement('div');
    valueItem.className = 'value-item mb-3';
    valueItem.innerHTML = `
        <div class="row">
            <div class="col-md-5">
                <input type="text" class="form-control" name="values[${index}][title]" placeholder="Value Title">
            </div>
            <div class="col-md-6">
                <textarea class="form-control" name="values[${index}][description]" placeholder="Value Description" rows="2"></textarea>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-value">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(valueItem);
});

document.getElementById('add-team-member').addEventListener('click', function() {
    const container = document.getElementById('team-container');
    const index = container.children.length;
    const teamItem = document.createElement('div');
    teamItem.className = 'team-member-item mb-3';
    teamItem.innerHTML = `
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" name="team_members[${index}][name]" placeholder="Full Name" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="team_members[${index}][position]" placeholder="Position" required>
            </div>
            <div class="col-md-3">
                <input type="file" class="form-control" name="team_members[${index}][image]" accept="image/*">
            </div>
            <div class="col-md-2">
                <textarea class="form-control" name="team_members[${index}][bio]" placeholder="Short Bio" rows="2"></textarea>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-team-member">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(teamItem);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-value') || e.target.closest('.remove-value')) {
        e.target.closest('.value-item').remove();
    }
    if (e.target.classList.contains('remove-team-member') || e.target.closest('.remove-team-member')) {
        e.target.closest('.team-member-item').remove();
    }
});
</script>
@endsection
