@extends('layouts.admin')

@section('title', 'Change Password - Admin Panel')

@section('content')

    <h1 class="display-6 fw-bold mb-4">Account Security</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-key me-2"></i>Change Password</h5>
                </div>
                <div class="card-body p-4">
                    
                    {{-- Password Update Form --}}
                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf
                        
                        {{-- Current Password --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Current Password</label>
                            <input type="password" 
                                   name="current_password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   required>
                            <div class="form-text text-muted">Enter your current password to proceed.</div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4 border-secondary opacity-10">

                        {{-- New Password --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">New Password</label>
                            <input type="password" 
                                   name="new_password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   required>
                            @error('new_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm New Password --}}
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Confirm New Password</label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   class="form-control" 
                                   required>
                            {{-- Note: 'new_password_confirmation' error is covered by the 'new_password' error above --}}
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-save me-2"></i> Save New Password
                        </button>
                    </form>

                </div>
            </div>
        </div>

        {{-- Security Tips --}}
        <div class="col-md-6 mt-4 mt-md-0">
            <div class="alert alert-warning shadow-sm border-0">
                <h5 class="alert-heading fw-bold"><i class="bi bi-shield-exclamation me-2"></i>Security Tips</h5>
                <p class="mb-0 small">
                    Use a combination of uppercase letters, lowercase letters, numbers, and symbols for a strong password. 
                    Do not share your admin password with anyone.
                </p>
            </div>
        </div>
    </div>

@endsection