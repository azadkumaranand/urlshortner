@extends('layouts.custom')


@section('content')
    <div class="container mt-5">
        {{--  --}}
        <div>
            <div class="d-flex justify-content-between mb-4" >
                <div class="d-flex">
                    <h3 class="fs-3 font-bold text-primary">Generated Short URLs</h3>
                    <a href="{{ route('url.shortner.form') }}" class="text-decoration-none">
                    <button class="btn btn-outline-primary px-4 rounded-0 mx-4">
                        Generate
                    </button>
                </a>
                </div>
                <div>
                    <div class="d-flex mx-4">
                        <div class="mx-4">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('client-member/dashboard?q=tm') }}">This Month</a></li>
                                    <li><a class="dropdown-item" href="{{ url('client-member/dashboard?q=lm') }}">Last Month</a></li>
                                    <li><a class="dropdown-item" href="{{ url('client-member/dashboard?q=lw') }}">Last Week</a></li>
                                    <li><a class="dropdown-item" href="{{ url('client-member/dashboard?q=today') }}">Today</a></li>
                                </ul>
                            </div>   
                        </div>
                        <a href="{{ route('client-member.download') }}" class="text-decoration-none">
                            <button class="btn btn-outline-primary px-4 rounded-0">
                                Download
                            </button>
                        </a>
                    </div>
                    
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
                        <th scope="col">Created On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($createdShortUrls as $createdShortUrl)
                        <tr>
                            <td>{{ url($createdShortUrl->short_url) }}</td>
                            <td>{{ substr($createdShortUrl->original_url, 0, 20) }}...</td>
                            <td>{{ $createdShortUrl->count }}</td>
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
    </div>
@endsection
