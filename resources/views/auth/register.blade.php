@extends('layouts.auth')

@section('title', 'Register')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Register</h4>
        </div>

        <div class="card-body">
            <form method="POST" class="needs-validation"
            novalidate="">
            @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name"
                        type="text"
                        class="form-control"
                        name="name" value="{{ old('name') }}" required>
                    <div class="invalid-feedback">
                        please fill in your name
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                        type="email"
                        class="form-control"
                        name="email" required>
                    @error('email')
                    <div style="font-size: 80%; color:#dc3545">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="invalid-feedback">
                        please fill in your email
                    </div>
                </div>

                <div class="form-group">
                    <label for="password"
                        class="d-block">Password</label>
                    <input id="password"
                        type="password"
                        class="form-control pwstrength"
                        data-indicator="pwindicator"
                        name="password" required>
                    <div class="invalid-feedback">
                        please fill in your password
                    </div>
                    <div id="pwindicator"
                        class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="outlet-name">Outlet Name</label>
                    <input id="outlet-name"
                        type="text"
                        class="form-control"
                        name="outlet_name" value="{{ old('outlet_name') }}" required>
                    <div class="invalid-feedback">
                        please fill in your outlet name
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
@endpush
