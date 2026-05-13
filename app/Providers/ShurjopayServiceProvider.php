<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ShurjopayPlugin\Shurjopay;
use ShurjopayPlugin\ShurjopayEnvReader;

class ShurjopayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Shurjopay::class, function ($app) {
            // The official way: use ShurjopayEnvReader to read from .env
            $env = new ShurjopayEnvReader(base_path()); // Looks for .env in project root

            // Or manually create config if EnvReader fails
            try {
                return new Shurjopay($env->getConfig());
            } catch (\Exception $e) {
                // Fallback to manual config
                $config = new \ShurjopayPlugin\ShurjopayConfig();
                $config->username = env('SP_USERNAME');
                $config->password = env('SP_PASSWORD');
                $config->api_endpoint = env('SHURJOPAY_API');
                $config->callback_url = env('SP_CALLBACK');
                $config->order_prefix = env('SP_PREFIX');
                $config->log_path = env('SP_LOG_LOCATION');
                $config->ssl_verifypeer = env('CURLOPT_SSL_VERIFYPEER', 1);

                return new Shurjopay($config);
            }
        });
    }
}
