<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class UrlShortenerService
{
    protected const CODE_LENGTH = 6;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateShortUrl($userid, $clientid, $longUrl){
        $existingShortUrl = ShortUrl::where('user_id', $userid)->where('client_id', $clientid)->where('original_url', $longUrl)->first();
        if($existingShortUrl){
            return $existingShortUrl;
        }

        do {
            $shortCode = Str::random(self::CODE_LENGTH); 
        } while (ShortUrl::where('short_url', $shortCode)->exists());

        return ShortUrl::create([
            'user_id'    => $userid,
            'client_id'  => $clientid??$userid,
            'original_url' => $longUrl,
            'short_url' => $shortCode
        ]);
    }

    public function resolveShortUrl($shortCode, $ipAddress, $userAgent)
    {
        // Use caching to improve performance
        $cacheKey = "short_url_{$shortCode}";
        
        $shortUrl = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($shortCode) {
            return ShortUrl::where('short_url', $shortCode)->first();
        });
        // dd($shortUrl);
        if (!$shortUrl) {
            return null; // Short URL not found
        }

        // Log the hit
        $shortUrl->increment('count');

        return $shortUrl->original_url;
    }
}
