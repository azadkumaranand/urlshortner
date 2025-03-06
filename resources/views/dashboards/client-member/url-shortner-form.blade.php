@extends('layouts.custom')


@section('content')
    <div class="container d-flex justify-content-center align-items-center mt-5">
        
        <div class="card" style="width: 50rem;">
            <div class="card-body p-5">
                <h2 class="font-bold fs-3 mb-4 mt-2">Generate Short Url</h2>
                <form method="POST" action="{{ route('short_url') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="long_url" class="col-form-label">Long Url</label>
                        <input type="text" class="form-control @error('long_url') is-invalid @enderror" id="long_url"
                            name="long_url" value="{{ old('long_url') }}">
                        @error('long_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-outline-primary mt-3 rounded-0">Generate</button>
                </form>
                @if(session()->has('short_url') && session()->has('long_url') && session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        <p><strong>Success!</strong> {{ session()->get('success') }}</p>
                        <br>
                        <p><strong>Long URL:</strong> {{ session()->get('long_url') }}</p>
                        <br>
                        <p><strong>Short URL:</strong> {{ session()->get('short_url') }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
