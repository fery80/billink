<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card col-md-5 mx-auto shadow-lg">
        <div class="card-body">
            <h3 class="text-center mb-4">Login</h3>

            @if ($errors->has('loginError'))
                <div class="alert alert-danger">{{ $errors->first('loginError') }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="gmail" class="form-label">Gmail</label>
                    <input type="email" name="gmail" class="form-control" value="{{ old('gmail') }}">
                    @error('gmail') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control">
                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
