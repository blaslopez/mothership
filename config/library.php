<?php

/*
 * Library directive configuration.
 *
 * Each key is a library name used in @library(name).
 * Assets are resolved from public/vendor/{name}/css/ and public/vendor/{name}/js/
 *
 * Keys per library:
 *   css     : string|array  stylesheet filename(s)
 *   js      : string|array  script filename(s); env key overrides generic
 *   script  : array         inline script blocks (supports {locale}, {url})
 *   plugins : array         sub-libraries living inside the parent lib directory
 *
 * Environment override: if a key matches APP_ENV inside js/css arrays, it is
 * used instead of the plain filenames. Useful for CDN URLs in dev mode.
 */

return [

    'bootstrap' => [
        'css' => 'bootstrap.min.css',
        'js'  => 'bootstrap.bundle.min.js',
    ],

    'vue' => [
        'js' => [
            'vue.global.prod.js',
            'dev' => 'https://unpkg.com/vue@3/dist/vue.global.js',
        ],
    ],

    'bootstrap-icons' => [
        'css' => 'bootstrap-icons.min.css',
    ],

    'datatables' => [
        'css'    => ['datatables.min.css'],
        'js'     => ['datatables.min.js'],
        'script' => [
            'const DATATABLES_LOCALE_URL = `{url}/vendor/datatables/js/locale.{locale}.json`',
        ],
        'plugins' => [
            'fixedColumns' => [
                'css' => 'dataTables.fixedColumns.min.css',
                'js'  => 'dataTables.fixedColumns.min.js',
            ],
        ],
    ],

];
