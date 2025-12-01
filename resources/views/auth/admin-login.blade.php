<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Tikako</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: #212529; /* Dark Background */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .login-header {
            background: #ffc107; /* Tikako Gold Color */
            padding: 30px 20px;
            text-align: center;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        }
        .btn-dark-custom {
            background-color: #212529;
            color: #fff;
            border: none;
            padding: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .btn-dark-custom:hover {
            background-color: #000;
            color: #ffc107;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h3 class="fw-bold m-0 text-dark"><i class="bi bi-shield-lock-fill me-2"></i>ADMIN PANEL</h3>
            <p class="small mb-0 text-dark opacity-75">Tikako Management System</p>
        </div>
        
        <div class="login-body">
            
            {{-- Display Flash Messages (e.g., from adminLogout) --}}
            @if (session('success'))
                <div class="alert alert-success small mb-3">{{ session('success') }}</div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                
                {{-- Email Field --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Admin Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="admin@tikako.com" 
                               value="{{ old('email') }}"
                               required 
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Password Field --}}
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="••••••••" 
                               required>
                    </div>
                    {{-- Note: General password error handled by email validation logic in Controller --}}
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark-custom rounded-pill">
                        LOGIN TO SYSTEM
                    </button>
                </div>
                
                {{-- Return to Public Site Link --}}
                <div class="text-center mt-4">
                    <a href="/" class="text-decoration-none small text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Back to Website
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>