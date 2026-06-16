<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Inyectar Google Analytics (GA4) y Meta Pixel (Facebook/Instagram) de forma segura en producción
         */
        add_action('wp_head', function () {
            // 1. Cargar las credenciales desde las variables de entorno de Bedrock
            $ga_id = env('GOOGLE_ANALYTICS_ID');
            $meta_id = env('META_PIXEL_ID');

            // Meta Domain Verification (requerido por Facebook para validar la propiedad del dominio)
            echo "
            <!-- Meta Domain Verification -->
            <meta name=\"facebook-domain-verification\" content=\"pl4dsq30p15llps9quh6k37uq6l8hn\" />
            ";

            // 2. Solo cargar en producción y si el usuario no está logueado para no ensuciar métricas
            if (WP_ENV === 'production' && !is_user_logged_in()) {

                // Google Analytics (GA4)
                if ($ga_id) {
                    echo "
                    <!-- Google tag (gtag.js) -->
                    <script async src=\"https://www.googletagmanager.com/gtag/js?id={$ga_id}\"></script>
                    <script>
                      window.dataLayer = window.dataLayer || [];
                      function gtag(){dataLayer.push(arguments);}
                      gtag('js', new Date());
                      gtag('config', '{$ga_id}', { 'anonymize_ip': true });
                    </script>
                    ";
                }

                // Meta Pixel (Facebook / Instagram)
                if ($meta_id) {
                    echo "
                    <!-- Meta Pixel Code -->
                    <script>
                    !function(f,b,e,v,n,t,s)
                    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                    n.queue=[];t=b.createElement(e);t.async=!0;
                    t.src=v;s=b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t,s)}(window, document,'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                    fbq('init', '{$meta_id}');
                    fbq('track', 'PageView');
                    </script>
                    <noscript><img height=\"1\" width=\"1\" style=\"display:none\"
                    src=\"https://www.facebook.com/tr?id={$meta_id}&ev=PageView&noscript=1\"
                    /></noscript>
                    <!-- End Meta Pixel Code -->
                    ";
                }
            }
        }, 1); // Prioridad 1 para cargar al inicio del <head>
    }
}
