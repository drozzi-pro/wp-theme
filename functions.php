<?php

use function Env\env;

/*
|--------------------------------------------------------------------------
| Объявление переменных темы
|--------------------------------------------------------------------------
*/
define('THEME_ASSETS', get_template_directory_uri() . '/dist');
/*
|--------------------------------------------------------------------------
| Регистрация авто загрузчика composer
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/
if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    wp_die(__('Ошибка загрузки. Пожалуйста выполните <code>composer install</code> в папке активной темы', 'raskroy'));
}
require $composer;

/*
|--------------------------------------------------------------------------
| Настройка шаблонизатора
|--------------------------------------------------------------------------
*/
CE::init(
    [
        'debug' => WP_DEBUG,
        'template_parts' => 'template/',
        'aliases' => [
            'layout' => 'template/layouts',
            'component' => 'template/components',
        ]
    ]
);

require_once 'settings/_index.php';
require_once 'post-types/_index.php';
require_once 'includes/assets.php';
