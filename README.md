

# Screenshots

<p align="center">
![Screenshot 2025-02-25 at 14-40-27 Dashboard - Cashier App](https://github.com/user-attachments/assets/ba3e3312-b858-44ed-a7a9-54bff3b8545f)
![Screenshot 2025-02-25 at 14-40-53 Income - Cashier App](https://github.com/user-attachments/assets/a23add7f-1eac-46ff-9472-355567be4342)
![Screenshot 2025-02-25 at 14-41-03 Orders - Cashier App](https://github.com/user-attachments/assets/06979c9d-d050-4215-9e59-b0787c3f97ab)
![Screenshot 2025-02-25 at 14-41-10 Product - Cashier App](https://github.com/user-attachments/assets/0d455ce8-e8b0-4f98-96e7-cc076da32aa3)

</p>

## About

A simple cashier app build with Laravel 11 & Vue 3

# Requirements

-   Php 8
-   Composer
-   Mysql
-   Apache/Nginx

## Installation and Usage

Clone the git repository

```bash
git clone https://github.com/ekohunterz/Kasir-Laravel.git
```

Go to the folder

```bash
cd Kasir-Laravel
```

Install and Update composer dependencies

```bash
composer update
```

Install npm dependencies

```bash
npm install
```

Copy .env.example and raname it to .env

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Link storage

```bash
php artisan storage:link
```

SETTING UP DB CONNECTION IN .env

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Cashier
DB_USERNAME=root
DB_PASSWORD=
```

Migrate Database with fresh seed

```bash
php artisan migrate:fresh --seed
```

Start the NPM server

```bash
npm run dev
```

Start the Laravel Developement Server

```bash
php artisan serve
```

## Login With

### Superadmin

```bash
email : superadmin@superadmin.com
password : superadmin
```

### Kasir

```bash
email : kasir@admin.com
password : password
```

# Packages

-   [Vue](https://vuejs.org/)
-   [Inertia](https://inertiajs.com/)
-   [Tailwind](https://tailwindcss.com/)
-   [Spatie](https://spatie.be/docs/laravel-permission/v5/introduction)
-   [Floating Vue](https://floating-vue.starpad.dev/)
-   [VueUse](https://vueuse.org/)
-   [Hero Icons](https://heroicons.com/)
-   [HeadlessUI](https://headlessui.com/)


# Starter Kit

[Laravel Jarvis By Erik Wibowo](https://github.com/erikwibowo/Laravel-Jarvis).

# Built With

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
