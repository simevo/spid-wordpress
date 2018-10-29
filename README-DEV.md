# Contributing

Issues and patches are welcome !

To contribute code to this project please follow the [git-flow workflow](https://danielkummer.github.io/git-flow-cheatsheet/).

# Coding standards

This project follows the [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards), more precisely `WordPress` and `PHPCompatibilityWP`.

Prior to committing, lint your code from the CLI with:
```sh
./vendor/bin/phpcs xxx.php
```
or [configure PHPCS and WPCS in your preferred IDE](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards#using-phpcs-and-wpcs-from-within-your-ide).

# Testing

TODO

# Development workflow

To develop bugfixes and new features for this plugin you can use either the docker-compose setup from simevo/spid-wordpress-example-form, or a self-standing WP install.

## Docker-compose

The benefit here is that this setup gives you both WP and the test Identity Provider [spid-testenv2](https://github.com/italia/spid-testenv2) preconfigured to talk to each other.

[Follow the guide](https://github.com/simevo/spid-wordpress-example-form#installazione-con-docker-compose) and you'll end up with WordPress here: http://localhost:8099/wp-login.php; the spid-wordpress repo will be cloned from https://github.com/simevo/spid-wordpress inside `spid-wordpress-example-form/spid-wordpress`.

Any change to the content of that directory will be picked up by the WP docker container immediately because the `spid-wordpress` dir from the host is mapped to `/var/www/html/wp-content/plugins/spid-wordpress`inside the container, by the magic of docker-compose: https://github.com/simevo/spid-wordpress-example-form/blob/master/docker-compose.yml#L31-L33

## Self-standing WP install

Use your preferred local / private / public wordpress test machine.
You'll have to set up and configure the test Identity Provider [spid-testenv2](https://github.com/italia/spid-testenv2) yourself. RTFM !

It is advised to clone https://github.com/simevo/spid-wordpress inside `/var/www/html/wp-content/plugins/spid-wordpress/`.

# Bundling spid-php-lib

This WordPress plugin has a single dependency: the [PHP package for SPID authentication (spid-php-lib)](https://github.com/italia/spid-php-lib).

The plugin bundles a copy of this library together with its own dependencies in [spid-php-lib](/spid-php-lib) so that the plugin users don't need to run composer on their WordPress instances.

To update the version of spid-php-lib bundled with the plugin proceed like this:
1. update the version of spid-php-lib in [composer.json](/composer.json)
2. run the [bundle.php](/bundle.php) script
3. commit any added / modified / deleted files together with the `composer.{json,lock}` changes.

In this way:
- the version we're bundling is self-documented in [composer.json](/composer.json)
- we're 100% sure we ship a 1-to-1 copy of the original
- the bundle process is reproducible.
