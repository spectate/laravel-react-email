# Build Laravel mailables with react-email.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spectate/laravel-react-email.svg?style=flat-square)](https://packagist.org/packages/spectate/laravel-react-email)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spectate/laravel-react-email/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/spectate/laravel-react-email/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/spectate/laravel-react-email/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/spectate/laravel-react-email/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spectate/laravel-react-email.svg?style=flat-square)](https://packagist.org/packages/spectate/laravel-react-email)

Build Laravel mailables with [react.email](https://react.email/).

## Installation

You can install the package via composer:

```bash
composer require spectate/laravel-react-email
```

Next up, install the required NPM packages:

```bash
npm i -D @react-email/components @react-email/render typescript react-email @types/react @types/node
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-react-email-config"
```

### Optional: Build templates on composer install

If you run `composer install` during deployments, this way you will automatically regenerate the React Email templates on each deploy.

To enable this, add the following to your `composer.json` file:

```json
{
    "post-install-cmd": [
        "@php artisan react-email:build"
    ]
}
```

## Usage

Create your first React Email template:

```bash
php artisan make:react-email MyFirstEmail
```

### Optional: run the React Email dev server

You can run the React Email dev server to preview your templates:

```bash
php artisan react-email:dev
```

This will start the dev server and watch for changes in your templates.

Next up, edit the files in `resources/views/react-emails` and see the changes in your browser.

### Hot reloading

By default, on local development environments, the React Email templates are rendered on-the-fly for every send. This can be disabled by setting the `REACT_EMAIL_HOT_RELOAD` environment variable to `false`.

### Building templates

You can build the React Email templates to HTML using the `react-email:build` command:

```bash
php artisan react-email:build
```

This will build all the templates and output them to `resources/views/vendor/react-email`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bjarn Bronsveld](https://github.com/14638441+bjarn)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
