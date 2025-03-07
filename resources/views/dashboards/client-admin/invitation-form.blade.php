@extends('layouts.custom')


@section('content')
    <div class="container d-flex justify-content-center align-items-center mt-5">
        <div class="card" style="width: 50rem;">
            <div class="card-body">
                <form method="POST" action="{{ route('client-admin.send-invitation') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="role" class="col-form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="client_members">member</option>
                            <option value="client_admin">admin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-outline-primary mt-3 rounded-0">Send Invitation</button>
                </form>
            </div>
        </div>
    </div>
@endsection
