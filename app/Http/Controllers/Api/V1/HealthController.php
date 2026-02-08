<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class HealthController extends Controller
{
    private const string STATUS_OK = 'ok';
    private const string STATUS_DEGRADED = 'degraded';
    private const string STATUS_FAILED = 'failed';

    private const string REDIS_HEALTH_KEY_PREFIX = 'health:check:';
    private const int REDIS_HEALTH_TTL = 1;
    private const string REDIS_HEALTH_VALUE = 'ok';

    private const string CHECK_DATABASE = 'database';
    private const string CHECK_REDIS = 'redis';

    public function __invoke(): JsonResponse
    {
        $healthResults = [
            self::CHECK_DATABASE => $this->checkDatabase(),
            self::CHECK_REDIS => $this->checkRedis(),
        ];

        $checks = $this->mapResultsToStatuses($healthResults);
        $status = $this->determineOverallStatus($checks);
        $httpStatus = $this->getHttpStatusCode($status);

        return response()->json([
            'status' => $status,
            'checks' => $checks,
        ], $httpStatus);
    }

    private function mapResultsToStatuses(array $results): array
    {
        return array_map(
            fn(bool $isHealthy): string => $isHealthy ? self::STATUS_OK : self::STATUS_FAILED,
            $results
        );
    }

    private function determineOverallStatus(array $checks): string
    {
        $failedChecks = array_filter(
            $checks,
            fn(string $status): bool => $status === self::STATUS_FAILED
        );

        if (empty($failedChecks)) {
            return self::STATUS_OK;
        }

        if (count($failedChecks) === count($checks)) {
            return self::STATUS_FAILED;
        }

        return self::STATUS_DEGRADED;
    }

    private function getHttpStatusCode(string $status): int
    {
        return match ($status) {
            self::STATUS_OK => Response::HTTP_OK,
            self::STATUS_DEGRADED => Response::HTTP_OK,
            self::STATUS_FAILED => Response::HTTP_SERVICE_UNAVAILABLE,
        };
    }

    private function checkDatabase(): bool
    {
        try {
            DB::select('SELECT 1');

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function checkRedis(): bool
    {
        try {
            $redis = Cache::store('redis');
            $key = self::REDIS_HEALTH_KEY_PREFIX . time();

            $redis->put($key, self::REDIS_HEALTH_VALUE, self::REDIS_HEALTH_TTL);
            $value = $redis->get($key);
            $redis->forget($key);

            return $value === self::REDIS_HEALTH_VALUE;
        } catch (\Throwable) {
            return false;
        }
    }
}
