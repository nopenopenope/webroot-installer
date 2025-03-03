![CI Status](https://github.com/nopenopenope/webroot-installer/actions/workflows/docker-image.yml/badge.svg)

# A Webroot [Composer](http://getcomposer.org) Library Installer

This is for PHP packages that support composer to configure in their `composer.json`.  It will
allow a root package to define a webroot directory and webroot package and magically install it
in the correct location.

## Requirements

In order to use this package your project must use both components in the given version, otherwise you cannot use this plugin:

1. Composer v2
2. PHP 8.x

## Installation

This repository is a fork of [Fancyguy/Webroot-Installer](https://github.com/nopenopenope/webroot-installer), so its not commited to packagist and you cannot install it via an unmodified composer require.

If you want to add this updated fork to your `composer.json`, you have to add the VCS declaration to your `repositories` node manually:

``` json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:nopenopenope/webroot-installer.git",
        "only": ["nopenopenope/webroot-installer"]
    }
],
```

Also, make sure to mark your project as `"type": "webroot"`, otherwise this plugin won't know that you support it.

Afterwards, you can proceed with the regular require command:
``` bash
composer require nopenopenope/webroot-installer:8.1.11
```


## Example `composer.json` File

``` json
{
    "name": "nopenopenope/www-mysite-com",
    "description": "Webroot Installer made for Composer v2",
    "authors": [
        {
            "name": "Steve Buzonas",
            "email": "steve@fancyguy.com"
        },
        {
            "name": "Maximilian Graf Schimmelmann",
            "email": "webroot@schimmelmann.org"
        }
    ],
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "wordpress/wordpress",
                "type": "webroot",
                "version": "5.9.2",
                "dist": {
                    "type": "zip",
                    "url": "https://wordpress.org/wordpress-5.9.2-no-content.zip"
                },
                "require": {
                    "nopenopenope/webroot-installer": "^1.0"
                }
            }
        },
        {
            "type": "vcs",
            "url": "git@github.com:nopenopenope/webroot-installer.git",
            "only": ["nopenopenope/webroot-installer"]
        }
    ],
    "require": {
        "wordpress/wordpress": "^5.9"
    },
    "extra": {
        "webroot-dir": "content",
        "webroot-package": "wordpress/wordpress"
    }
}
```

This would install the defined `wordpress/wordpress` package in the `content` directory of the project.

## Warning

Setting the `webroot-dir` to a non-empty directory will delete the contents in most cases.  It is recommended to use a clean target within your project directory.
