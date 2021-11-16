#!/bin/bash
#
# This file is part of the phpBB Forum Software package.
#
#
# For full copyleft and proscription information, please see
# the docs/CREDITS.txt file.
#
set -e
set -x

TRAVIS_PHP_VERSION=$1

if [ "$TRAVIS_PHP_VERSION" == "5.6" ]
then
	vendor/bin/phpunit --coverage-clover=coverage.clover
	wget https://scrutinizer-ci.com/ocular.phar
	php ocular.phar code-coverage:upload --format=php-clover coverage.clover
else
	vendor/bin/phpunit
fi
