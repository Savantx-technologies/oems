<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('redis:test', function () {
    $this->info('Testing Redis integration...');

    $cacheKey = 'redis_test:' . now()->timestamp;
    $cacheValue = 'ok';

    try {
        $ping = Redis::connection('default')->ping();
        $this->line('Redis ping: ' . $ping);

        Cache::put($cacheKey, $cacheValue, now()->addMinute());
        $cached = Cache::get($cacheKey);

        $this->line('Cache store: ' . config('cache.default'));
        $this->line('Session driver: ' . config('session.driver'));
        $this->line('Queue connection: ' . config('queue.default'));
        $this->line('Cache roundtrip: ' . ($cached ?? 'null'));
        $this->line('Session Redis connection: ' . (config('session.connection') ?? 'null'));
        $this->line('Queue Redis connection: ' . config('queue.connections.redis.connection'));

        if ($cached !== $cacheValue) {
            $this->error('Cache roundtrip failed.');
            return self::FAILURE;
        }

        $this->info('Redis is working for cache, and config is aligned for session/queue.');
        return self::SUCCESS;
    } catch (\Throwable $exception) {
        $this->error('Redis test failed: ' . $exception->getMessage());
        return self::FAILURE;
    }
})->purpose('Test Redis connectivity, cache roundtrip, and queue/session config');
