#!/bin/bash

set -e

# bail out if not in local/dev environment
#grep -q APP_ENV=local .env || exit 1

# drop & recreate all the db tables
php artisan migrate:fresh

# seed the DB with test data
php artisan db:seed --class=TestDataSeeder
