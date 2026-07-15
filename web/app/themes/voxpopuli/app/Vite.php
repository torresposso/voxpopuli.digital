<?php

namespace App;

use Roots\Acorn\Assets\Vite as SageVite;

class Vite extends SageVite
{
    /**
     * Get the path to a given asset when running in HMR mode.
     * Overridden to dynamically replace 0.0.0.0 with the active HTTP request host
     * to allow HMR from mobile devices on the LAN.
     */
    protected function hotAsset($asset)
    {
        $url = parent::hotAsset($asset);

        if (isset($_SERVER['HTTP_HOST'])) {
            $requestHost = $_SERVER['HTTP_HOST'];

            // Extract just the hostname/IP, stripping port
            $requestHostName = parse_url('http://' . $requestHost, PHP_URL_HOST);

            if ($requestHostName) {
                $urlParts = parse_url($url);
                if (isset($urlParts['host']) && $urlParts['host'] === '0.0.0.0') {
                    $scheme = $urlParts['scheme'] ?? 'http';
                    $port = $urlParts['port'] ?? 5174;
                    $path = $urlParts['path'] ?? '';
                    $query = isset($urlParts['query']) ? '?' . $urlParts['query'] : '';

                    return "{$scheme}://{$requestHostName}:{$port}{$path}{$query}";
                }
            }
        }

        return $url;
    }
}
