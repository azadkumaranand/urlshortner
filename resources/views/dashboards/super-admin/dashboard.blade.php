@extends('layouts.custom')


@section('content')
    <div class="container mt-5">
        <div>
            <div class="d-flex justify-content-between mb-4" >
                <h3 class="fs-3 font-bold mt-5 text-primary">Clients</h3>
                <div>
                    <button class="btn btn-outline-primary px-4 rounded-0">
                        <a href="{{ route('super-admin.invitation-form') }}" class="text-decoration-none">Invite</a>
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
                        <th scope="col">Client Name</th>
                        <th scope="col">Users</th>
                        <th scope="col">Total Generated URLs</th>
                        <th scope="col">Total URL Hits</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}<br>
                                <span class="badge text-bg-secondary">{{ $user->email }}</span>
                            </td>
                            <td>{{ $user->users_under_client_count }}</td>
                            <td>{{ $user->short_url_genereted_by_client_members_count }}</td>
                            <td>{{ $user->short_url_count_by_client_members_sum_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div> 
        <div>
            <div class="d-flex justify-content-between mb-4" >
                <h3 class="fs-3 font-bold mt-5 text-primary">Generated Short URLs</h3>
                {{-- <div>
                    <button class="btn btn-outline-primary px-4 rounded-0">
                        <a href="{{ route('super-admin.invitation-form') }}" class="text-decoration-none">Invite</a>
                    </button>
                </div> --}}
            </div>
            
            <table class="table table-white">
                <thead>
                    <tr>
                        <th scope="col">Short URL</th>
                        <th scope="col">Long URL</th>
                        <th scope="col">Hits</th>
                        <th scope="col">Client</th>
                        <th scope="col">Created On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($createdShortUrls as $createdShortUrl)
                        <tr>
                            <td>{{ url('/short-url/'.$createdShortUrl->short_url) }}</td>
                            <td>{{ substr($createdShortUrl->original_url, 0, 15) }}...</td>
                            <td>{{ $createdShortUrl->count }}</td>
                            <td>{{ $createdShortUrl->client->name }}</td>
                            <td>{{ date('d M y', strtotime($createdShortUrl->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div> 
    </div>
@endsection
