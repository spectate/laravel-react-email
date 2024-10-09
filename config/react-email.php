<?php

// config for Spectate/ReactEmail
return [

    /**
     * The path to the React Email templates.
     *
     * Defaults to `resources/views/react-emails`.
     */
    'email_templates_path' => resource_path('views/react-email'),

    /**
     * The path to the HTML output.
     *
     * Defaults to `resources/views/vendor/react-email`.
     */
    'blade_output_path' => resource_path('views/vendor/react-email'),

    /**
     * Enable hot reloading.
     *
     * Defaults to `APP_DEBUG` env var.
     */
    'enable_hot_reload' => env('REACT_EMAIL_HOT_RELOAD', env('APP_DEBUG', false)),

];
