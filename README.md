# TinyWow Compress Image

## Introduction

This is a simple image compression application based on [Laravel](https://laravel.com/) framework. The application allows users to upload an image, preview original and compressed versions, and receive a compressed image.

## Getting started

#### Create .env file and set env variables

```
cp .env.example .env
```

#### Laravel setup and migrations

```
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
```

#### Insert ASSET_URL to .env file

```
ASSET_URL=http://localhost:8000/storage
```

### Run application

```
npm install && npm run build
composer run dev
```

The application should be available at `http://localhost:8000`

## Architecture

Every time a user opens the application, it generates a session ID that is stored until the user closes the browser tab. The session ID is used to create a directory for the current session, where compressed images are stored - this prevents image name collisions. Users can set the compression quality (default: 50%). For each quality level, the application creates a separate file:

```
IMAGE_UPLOAD_DIR/session_id/quality_filename.ext
```

If a user tries to compress the same image with the same quality, the application returns the previously compressed image.

The application stores basic image compression information in the database, which can be used for analytics purposes.

## Improvements

- Allow users to upload multiple images at once
- Allow users to resize image
- Supports more image formats (GIF, WebP)
- Add support cloud store services (AWS S3, Cloudflare)
- Account creation to save image compression history
- Remove uploaded images after 1 hour
