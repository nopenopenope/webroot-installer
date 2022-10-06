# A Webroot [Composer](http://getcomposer.org) Library Installer

This is for PHP packages that support composer to configure in their `composer.json`.  It will
allow a root package to define a webroot directory and webroot package and magically install it
in the correct location.

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
