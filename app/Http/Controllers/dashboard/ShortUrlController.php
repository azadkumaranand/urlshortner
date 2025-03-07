<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use App\Services\UrlShortenerService;

class ShortUrlController extends Controller
{
    protected $urlShortenerService;

    public function __construct(UrlShortenerService $urlShortenerService)
    {
        $this->urlShortenerService = $urlShortenerService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'long_url' => 'required|url|max:2048',
        ]);

        $user = auth()->user();

        // Generate short URL
        $shortUrl = $this->urlShortenerService->generateShortUrl(
            $user->id, $user->client_id, $request->long_url
        );
        // return response()->json(['short_url' => url("/" . $shortUrl->short_url), 'long_url' => $shortUrl->original_url, 'success' => 'URL shortened successfully']);
        return redirect()->back()->withInput()->with(['short_url' => url("/short-url/" . $shortUrl->short_url), 'long_url' => $shortUrl->original_url, 'success' => 'URL shortened successfully']);
    }

    /**
     * Redirect from short URL to original long URL.
     */
    public function show($shortCode, Request $request)
    {
        $longUrl = $this->urlShortenerService->resolveShortUrl(
            $shortCode, 
            $request->ip(), 
            $request->header('User-Agent')
        );

        if (!$longUrl) {
            return response()->json(['error' => 'Short URL not found'], 404);
        }

        return redirect()->to($longUrl);
    }
}
