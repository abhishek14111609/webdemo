@extends('admin.layout')

@section('title', 'View About Us Content')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">About Us Content Details</h4>
                    <div>
                        <a href="{{ route('admin.about.edit', $about) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.about.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>{{ $about->title }}</h3>
                                @if($about->subtitle)
                                    <h5 class="text-muted">{{ $about->subtitle }}</h5>
                                @endif
                            </div>

                            @if($about->description)
                                <div class="mb-4">
                                    <h6>Description</h6>
                                    <p class="text-muted">{{ $about->description }}</p>
                                </div>
                            @endif

                            <div class="row mb-4">
                                @if($about->mission)
                                    <div class="col-md-6">
                                        <h6>Mission</h6>
                                        <p class="text-muted">{{ $about->mission }}</p>
                                    </div>
                                @endif
                                @if($about->vision)
                                    <div class="col-md-6">
                                        <h6>Vision</h6>
                                        <p class="text-muted">{{ $about->vision }}</p>
                                    </div>
                                @endif
                            </div>

                            @if($about->history)
                                <div class="mb-4">
                                    <h6>History</h6>
                                    <p class="text-muted">{{ $about->history }}</p>
                                </div>
                            @endif

                            @if($about->values && count($about->values) > 0)
                                <div class="mb-4">
                                    <h6>Our Values</h6>
                                    <div class="row">
                                        @foreach($about->values as $value)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-left-primary">
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ $value['title'] }}</h6>
                                                        <p class="card-text text-muted">{{ $value['description'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($about->team_info && count($about->team_info) > 0)
                                <div class="mb-4">
                                    <h6>Team Members</h6>
                                    <div class="row">
                                        @foreach($about->team_info as $member)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card">
                                                    @if(isset($member['image']) && $member['image'])
                                                        <img src="{{ asset('storage/' . $member['image']) }}"
                                                             class="card-img-top" alt="{{ $member['name'] }}"
                                                             style="height: 200px; object-fit: cover;">
                                                    @endif
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ $member['name'] }}</h6>
                                                        <p class="card-text text-primary">{{ $member['position'] }}</p>
                                                        @if(isset($member['bio']) && $member['bio'])
                                                            <p class="card-text text-muted">{{ $member['bio'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            @if($about->image)
                                <div class="mb-4">
                                    <h6>Featured Image</h6>
                                    <img src="{{ asset('storage/' . $about->image) }}" alt="{{ $about->title }}"
                                         class="img-fluid rounded">
                                </div>
                            @endif

                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Content Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <strong>Status:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="badge {{ $about->status ? 'bg-success' : 'bg-danger' }} fs-6">
                                                {{ $about->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <strong>Created:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            {{ $about->created_at->format('M d, Y \a\t h:i A') }}
                                        </div>
                                    </div>

                                    @if($about->updated_at != $about->created_at)
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <strong>Updated:</strong>
                                            </div>
                                            <div class="col-sm-8">
                                                {{ $about->updated_at->format('M d, Y \a\t h:i A') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
