## Router

A tool for planning and managing delivery routes.

### Features

* Planning and managing routes
* Adding driver accounts
* Geolocation of addresses using external tools (Google Maps, OpenRouteService)
* Preview the delivery status of a route
* Route preview with map (Google Maps)
* Importing route points from an .xlsx file
* Route optimization with external tools (RouteXL, OpenRouteService)
* Option to force the driver to upload a photo of the delivered package
* Automatic route creation based on predefined zones
* Saving driver location history

### Tech stack

* Laravel 8
* Vue.js 3
* Inertia.js
* PrimeVue
* Laravel Octane (optional)
* RoadRunner (optional)

### Requirements

* Nginx / Apache
* PHP 8.0
* MySQL 5.7+ / MariaDB 5.7+ / PostgreSQL

### Install guide

[Deploy app to GCP using Terraform](https://github.com/dawidholka/router-gcp-tf)

1. Download the app using Git.
2. Copy .env.example as .env and make appropriate changes in the file.
3. Generate APP_KEY in the .env file: ```php artisan key:generate```
4. Create tables: ```php artisan migrate```
5. Create admin user: ```php artisan admin:create```
