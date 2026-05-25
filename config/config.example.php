<?php

/**
 * Application configuration.
 * This file replaces .env — keep out of version control.
 * Add to .gitignore: /config/config.php
 */

return [

    // App
    'APP_NAME'                  => 'FlyoverBD',
    'APP_ENV'                   => 'local',
    'APP_KEY'                   => '',  // generate: php artisan key:generate --show
    'APP_DEBUG'                 => 'true',
    'APP_URL'                   => 'http://localhost:8000',

    // Locale
    'APP_LOCALE'                => 'en',
    'APP_FALLBACK_LOCALE'       => 'en',
    'APP_FAKER_LOCALE'          => 'en_US',

    // Maintenance
    'APP_MAINTENANCE_DRIVER'    => 'file',

    // Security
    'BCRYPT_ROUNDS'             => '12',

    // Logging
    'LOG_CHANNEL'               => 'stack',
    'LOG_STACK'                 => 'single',
    'LOG_DEPRECATIONS_CHANNEL'  => 'null',
    'LOG_LEVEL'                 => 'debug',

    // Database
    'DB_CONNECTION'             => 'mysql',
    'DB_HOST'                   => '127.0.0.1',
    'DB_PORT'                   => '3306',
    'DB_DATABASE'               => 'flyoverbd',
    'DB_USERNAME'               => 'root',
    'DB_PASSWORD'               => '',

    // Session
    'SESSION_DRIVER'            => 'database',
    'SESSION_LIFETIME'          => '120',
    'SESSION_ENCRYPT'           => 'false',
    'SESSION_PATH'              => '/',
    'SESSION_DOMAIN'            => 'null',

    // Broadcasting / Filesystem / Queue / Cache
    'BROADCAST_CONNECTION'      => 'log',
    'FILESYSTEM_DISK'           => 'public',
    'QUEUE_CONNECTION'          => 'database',
    'CACHE_STORE'               => 'database',

    // Memcached
    'MEMCACHED_HOST'            => '127.0.0.1',

    // Redis
    'REDIS_CLIENT'              => 'phpredis',
    'REDIS_HOST'                => '127.0.0.1',
    'REDIS_PASSWORD'            => 'null',
    'REDIS_PORT'                => '6379',

    // Mail
    'MAIL_MAILER'               => 'log',
    'MAIL_SCHEME'               => 'null',
    'MAIL_HOST'                 => '127.0.0.1',
    'MAIL_PORT'                 => '2525',
    'MAIL_USERNAME'             => 'null',
    'MAIL_PASSWORD'             => 'null',
    'MAIL_FROM_ADDRESS'         => 'hello@example.com',
    'MAIL_FROM_NAME'            => 'FlyoverBD',

    // AWS
    'AWS_ACCESS_KEY_ID'                 => '',
    'AWS_SECRET_ACCESS_KEY'             => '',
    'AWS_DEFAULT_REGION'                => 'us-east-1',
    'AWS_BUCKET'                        => '',
    'AWS_USE_PATH_STYLE_ENDPOINT'       => 'false',

    // Vite
    'VITE_APP_NAME'             => 'FlyoverBD',

    // Web Push (VAPID)
    'VAPID_PUBLIC_KEY'          => '',
    'VAPID_PRIVATE_KEY'         => '',

    // reCAPTCHA
    'RECAPTCHA_SITE_KEY'        => '',
    'RECAPTCHA_SECRET_KEY'      => '',

];
