<?php

use function Env\env;

if (!is_admin()) {

    /**
     * Enqueue JavaScript files and (S)CSS styles in... dev mode, production, always
     */
    function enqueue_assets()
    {
        $assets = new Assets(get_theme_file_path('dist/assets.json'), 'dist');

        $site_in_js = [
            'themeUrl'  => get_template_directory_uri(),
            'assetsDir' => env('ASSETS_DIR')
        ];

        if (env('MODE') === 'development') {
            $assets->enqueueScript('vendors.js');
            $assets->enqueueScript('dev.js', false, ['site' => $site_in_js]);
        } else {
            $assets->enqueueScript('critical.js');
            $assets->enqueueStyle('critical.css');

            $assets->enqueueStyle('vendors.css', true);
            $assets->enqueueScript('vendors.js', true);

            $assets->enqueueScript('app.js', true, ['site' => $site_in_js]);
            $assets->enqueueStyle('app.css', true);
        }
    }

    add_action('wp_enqueue_scripts', 'enqueue_assets', 999);
}
