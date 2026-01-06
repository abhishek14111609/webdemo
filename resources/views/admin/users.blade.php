@extends('admin.layout')

@section('title', 'Manage Users')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h4 class="mb-4">All Users</h4>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>NO.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="d-flex align-items-center">
                            <img src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://randomuser.me/api/portraits/men/32.jpg' }}" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;">
                            {{ $user->name }}
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm" title="View"><i class="fas fa-eye"></i></a>
                            @if($user->is_admin)
                                <span class="badge bg-warning text-dark"><i class="fas shield me-1"></i> Admin</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
