<?php

namespace Tests\Unit\Providers;

use App\Features\Ai\Contracts\AiClientInterface;
use App\Features\Ai\Services\OpenAiStockGeneratorService;
use App\Features\Stock\Contracts\StockRepositoryInterface;
use App\Features\Stock\Repositories\StockRepository;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_bindings(): void
    {
        $repo = app(StockRepositoryInterface::class);
        $this->assertInstanceOf(StockRepository::class, $repo);

        $ai = app(AiClientInterface::class);
        $this->assertInstanceOf(OpenAiStockGeneratorService::class, $ai);
    }

    public function test_force_https_configuration(): void
    {
        config(['app.force_https' => true]);

        $provider = new AppServiceProvider($this->app);
        $provider->boot();

        $generated = URL::to('/test');
        $this->assertStringStartsWith('https://', $generated);

        // Reset
        config(['app.force_https' => false]);
    }

    public function test_rate_limiters(): void
    {
        $guestRequest = Request::create('/test', 'GET');

        $user = User::factory()->create();
        $authRequest = Request::create('/test', 'GET');
        $authRequest->setUserResolver(fn () => $user);

        $limiter1000 = RateLimiter::limiter('1000-per-second');
        $this->assertNotNull($limiter1000);
        $this->assertEquals(1000, $limiter1000($guestRequest)->maxAttempts);
        $this->assertEquals(1000, $limiter1000($authRequest)->maxAttempts);

        $limiterGlobal = RateLimiter::limiter('global');
        $this->assertNotNull($limiterGlobal);
        $this->assertEquals(1000, $limiterGlobal($guestRequest)->maxAttempts);
        $this->assertEquals(1000, $limiterGlobal($authRequest)->maxAttempts);

        $limiterApi = RateLimiter::limiter('api');
        $this->assertNotNull($limiterApi);
        $this->assertEquals(1000, $limiterApi($guestRequest)->maxAttempts);
        $this->assertEquals(1000, $limiterApi($authRequest)->maxAttempts);
    }
}

