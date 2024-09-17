#!/usr/bin/env bash
echo migration test
php artisan migrate:fresh --seed

echo artisan inspire
php artisan inspire
