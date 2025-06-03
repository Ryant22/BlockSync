<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MojangApiService
{
    /**
     * Get the Minecraft username for a given UUID using the Mojang API.
     * Optionally caches the result for 1 day.
     */
    public function getUsernameFromUuid(string $uuid): ?string
    {
        $cacheKey = 'mojang_username_' . $uuid;
        return Cache::remember($cacheKey, 86400, function () use ($uuid) {
            $response = Http::withOptions(['verify' => false])
                ->get("https://sessionserver.mojang.com/session/minecraft/profile/{$uuid}");
            if ($response->successful()) {
                $data = $response->json();
                return $data['name'] ?? null;
            }
            return null;
        });
    }
}
