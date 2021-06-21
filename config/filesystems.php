<?php

return [

    /*
            |--------------------------------------------------------------------------
            | Default Filesystem Disk
            |--------------------------------------------------------------------------
            |
            | Here you may specify the default filesystem disk that should be used
            | by the framework. The "local" disk, as well as a variety of cloud
            | based disks are available to your application. Just store away!
            |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
            |--------------------------------------------------------------------------
            | Default Cloud Filesystem Disk
            |--------------------------------------------------------------------------
            |
            | Many applications store files both locally and in the cloud. For this
            | reason, you may specify a default "cloud" driver here. This driver
            | will be bound as the Cloud disk implementation in the container.
            |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
            |--------------------------------------------------------------------------
            | Filesystem Disks
            |--------------------------------------------------------------------------
            |
            | Here you may configure as many filesystem "disks" as you wish, and you
            | may even configure multiple disks of the same driver. Defaults have
            | been setup for each driver as an example of the required options.
            |
            | Supported Drivers: "local", "ftp", "s3", "rackspace"
            |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        'logs' => [
            'driver' => 'local',
            'root' => storage_path('logs'),
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],
        'sftp' => [
//          'driver' => 'sftp',
//          'host' => env('SFTP_IMAGE_STORAGE_HOST', '10.11.31.14'),
//          'username' => env('SFTP_IMAGE_STORAGE_USERNAME', 'cs_ftp'),
//          'password' => env('SFTP_IMAGE_STORAGE_PASSWORD', '&p5oWtypD8Y6MmD4'),

            'driver' => 'sftp',
            'host' => env('SFTP_IMAGE_STORAGE_HOST', '10.11.31.14'),
            'username' => env('SFTP_IMAGE_STORAGE_USERNAME', 'cs_ftp'),
            'privateKey' => '../resources/keys/cs_ftp.key',
            'password' => 'cs_@sseT123',

            // Settings for SSH key based authentication...
            // 'privateKey' => '/path/to/privateKey',
            // 'password' => 'encryption-password',

            // Optional SFTP Settings...
            // 'port' => 22,
            // 'root' => '',
            // 'timeout' => 30,
        ],

    ],

];
