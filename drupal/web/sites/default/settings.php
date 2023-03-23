<?php

// phpcs:ignoreFile

/**
 * Load default configuration.
 */
include $app_root . '/' . $site_path . '/default.settings.php';

/**
 * Get database settings from env variables.
 */
$databases['default']['default'] = [
  'database' => $_ENV['DB_NAME'],
  'username' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASS'],
  'host' => $_ENV['DB_HOST'],
  'port' => $_ENV['DB_PORT'],
  'driver' => 'mysql',
  'collation' => 'utf8mb4_general_ci',
];

/**
 * Location of the site configuration files.
 */
$settings['config_sync_directory'] = '../config/sync';

/**
 * Salt for one-time login links, cancel links, form tokens, etc.
 */
$settings['hash_salt'] = 'G5b6S8Qa1jgoCHh6bpL8nvGPQ5SwLTUOyiM3ZLRUf_rQnYod78fEembj4kikz5IbgIlylWY2Qg';


/**
 * Private file path.
 */
$settings['file_private_path'] = '../private_files';

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/default.services.yml';
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

/**
 * Override smtp settings with env vars if available.
 */
if (isset($_ENV['SMTP_HOST'])) {
  $config['smtp.settings']['smtp_host'] = $_ENV['SMTP_HOST'];
}
if (isset($_ENV['SMTP_PORT'])) {
  $config['smtp.settings']['smtp_port'] = $_ENV['SMTP_PORT'];
}
if (isset($_ENV['SMTP_TLS'])) {
  $config['smtp.settings']['smtp_autotls'] = $_ENV['SMTP_TLS'];
}

/**
 * Trusted host configuration.
 */
if (isset($_ENV['DOMAIN'])) {
  $baseDomain = str_replace('.', '\.', $_ENV['DOMAIN']);
  $settings['trusted_host_patterns'] = [
    "^$baseDomain$",
    "^.+\.$baseDomain$",
  ];
}

/**
 * Config splits.
 */
$config['config_split.config_split.development']['status'] = FALSE;

/**
 * Load development settings overrides.
 */
if (
  file_exists($app_root . '/' . $site_path . '/settings.development.php')
  && isset($_ENV['ENVIRONMENT'])
  && $_ENV['ENVIRONMENT'] === 'development'
) {
  include $app_root . '/' . $site_path . '/settings.development.php';
}

/**
 * Load local settings overrides.
 */
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}
