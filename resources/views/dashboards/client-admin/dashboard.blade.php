@extends('layouts.custom')


@section('content')
    <div class="container mt-5">
        {{--  --}}
        <div>
            <div class="d-flex justify-content-between mb-4" >
                <div class="d-flex">
                    <h3 class="fs-3 font-bold text-primary">Generated Short URLs</h3>
                    <button class="btn btn-outline-primary px-4 rounded-0 mx-4">
                        <a href="{{ route('url.shortner.form') }}" class="text-decoration-none">Generate</a>
                    </button>
                </div>
                <div>
                    <button class="btn btn-outline-primary px-4 rounded-0">
                        <a href="{{ route('client-admin.download') }}" class="text-decoration-none">Download</a>
                    </button>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success mt-4 mb-3">
                    {{ session('success') }}
                </div>
            @endif
            <table class="table table-white">
                <thead>
                    <tr>
                        <th scope="col">Short URL</th>
                        <th scope="col">Long URL</th>
                        <th scope="col">Hits</th>
                        <th scope="col">Name</th>
                        <th scope="col">Created On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($createdShortUrls as $createdShortUrl)
                        <tr>
                            <td>{{ url($createdShortUrl->short_url) }}</td>
                            <td>{{ substr($createdShortUrl->original_url, 0, 20) }}...</td>
                            <td>{{ $createdShortUrl->count }}</td>
                            <td>{{ $createdShortUrl->user->name }}</td>
                            <td>{{ date('d M y', strtotime($createdShortUrl->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($createdShortUrls)==0)
                <p class="fs-6 font-bold text-center">No Data found</p>
            @endif
            {{ $createdShortUrls->links() }} 
        </div>

        {{-- Total team members --}}
        <div class="mt-6 py-6">
            <div class="d-flex justify-content-between mb-4" >
                <h3 class="fs-3 font-bold mt-5 text-primary">Team Members</h3>
                <div>
                    <button class="btn btn-outline-primary px-4 rounded-0">
                        <a href="{{ route('client-admin.invitation-form') }}" class="text-decoration-none">Invite</a>
                    </button>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success mt-4 mb-3">
                    {{ session('success') }}
                </div>
            @endif
            <table class="table table-white">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Total Generated URLs</th>
                        <th scope="col">Total URL Hits</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role?ucfirst(explode('_',$user->role)[1]):'' }}</td>
                            <td>{{ $user->short_url_genereted_by_member_count }}</td>
                            <td>{{ $user->short_url_count_by_member_sum_count??0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($users)==0)
                <p class="fs-6 font-bold text-center">No Data found</p>
            @endif
            {{ $users->links() }} 
        </div>
    </div>
@endsection
