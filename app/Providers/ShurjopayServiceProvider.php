<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ShurjopayPlugin\Shurjopay;
use ShurjopayPlugin\ShurjopayConfig;

class ShurjopayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Shurjopay::class, function ($app) {
            $config = new ShurjopayConfig();
            $config->username       = config('shurjopay.username');
            $config->password       = config('shurjopay.password');
            $config->api_endpoint   = config('shurjopay.api_endpoint');
            $config->callback_url   = config('shurjopay.callback_url');
            $config->order_prefix   = config('shurjopay.order_prefix');
            $config->log_path       = config('shurjopay.log_path');
            $config->ssl_verifypeer = config('shurjopay.ssl_verifypeer');

            return new Shurjopay($config);
        });
    }
}