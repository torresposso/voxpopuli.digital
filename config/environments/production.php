<?php
/**
 * Production environment configuration
 *
 * @package Bedrock
 */

use Roots\WPConfig\Config;

// Enforce SSL for logins and administrative screens
Config::define('FORCE_SSL_ADMIN', true);
