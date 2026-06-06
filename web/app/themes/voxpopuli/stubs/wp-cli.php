<?php

/**
 * WP-CLI Stubs for IDE Static Analysis and Type Resolution.
 *
 * This file is not loaded at runtime but helps IDEs/static analyzers
 * resolve the undefined global WP_CLI class.
 */
if (! class_exists('WP_CLI')) {
    class WP_CLI
    {
        /**
         * Log a message to the console.
         *
         * @param  string  $message
         * @return void
         */
        public static function log($message) {}

        /**
         * Log a success message to the console.
         *
         * @param  string  $message
         * @return void
         */
        public static function success($message) {}

        /**
         * Log a warning message to the console.
         *
         * @param  string  $message
         * @return void
         */
        public static function warning($message) {}

        /**
         * Log an error message to the console and optionally exit.
         *
         * @param  string  $message
         * @param  bool  $exit
         * @return void
         */
        public static function error($message, $exit = true) {}

        /**
         * Register a custom WP-CLI command.
         *
         * @param  string  $name
         * @param  callable|string|array  $callable
         * @param  array  $args
         * @return void
         */
        public static function add_command($name, $callable, $args = []) {}
    }
}
