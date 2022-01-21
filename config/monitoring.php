<?php

return [
    /*
     * The monitoring data will be collected under this name.
     * If you have running same source code on multiple servers then
     * you need to define a unique name per instance by using
     * environment variables on each server.
     */
    'instance_name' => env('MONITORING_INSTANCE_NAME', env('APP_NAME')),

    /*
     * Route configurations
     */
    'routes' => [
        'prefix' => 'monitoring',
        'middlewares' => ['web']
    ],

    /*
     * Models
     */
    'models' => [
        'monitoring_record' => \SaeedVaziry\Monitoring\Models\MonitoringRecord::class,
        'monitoring_alert' => \SaeedVaziry\Monitoring\Models\MonitoringAlert::class,
    ],

    /*
     * Chart colors
     */
    'chart_colors' => [
        'cpu' => [
            'border_color' => '#4f46e5',
            'background_color' => '#a5b4fc'
        ],
        'memory' => [
            'border_color' => '#e11d48',
            'background_color' => '#fda4af'
        ],
        'disk' => [
            'border_color' => '#9333ea',
            'background_color' => '#d8b4fe'
        ],
    ],

    /*
     * Supported channels are Email and Slack, but you can add
     * your own channels class to the `channels` array.
     * Make sure that you implementing `\SaeedVaziry\Monitoring\Channels\Channel`
     * interface in your custom channels
     */
    'notifications' => [
        'channels' => [
            \SaeedVaziry\Monitoring\Channels\Email::class,
            // \SaeedVaziry\Monitoring\Channels\Slack::class
        ],

        /*
         * Fill it if you want the Email channel
         */
        'email' => env('MONITORING_EMAIL_ADDRESS'),

        /*
         * Fill it if you want the Slack channel
         */
        'slack_webhook_url' => env('MONITORING_SLACK_WEBHOOK_URL')
    ],

    /*
     * You can enable or disable migrations here
     */
    'migrations' => true,

    /*
     * Purge recorded data
     * Supports PHP strtotime options like: '-1 day', '-2 hours', ...
     */
    'purge_before' => '-1 day',

    /*
     * How frequently to record the data
     * Available options are Laravel schedule frequency names
     * https://laravel.com/docs/8.x/scheduling#schedule-frequency-options
     * invalid names will cause your app to crash!
     */
    'frequency' => 'hourly',
];
