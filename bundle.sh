#!/bin/sh
# This script can be used to make it easier to upgrade the spid-php-lib dependency of this plugin
# Run it from the root of the project

set -e
rm -rf spid-php-lib
composer install --no-dev
rsync -rvx vendor/italia/spid-php-lib .
cd spid-php-lib
composer install --no-dev
