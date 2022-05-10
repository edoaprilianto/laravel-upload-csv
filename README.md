## Laravel Import CSV

this application provide for the large amount file upload csv with laravel. handle real-time progress and chunks upload data to database & using redis queue for managing process. 

**Noted : After upload file in application , it will delay 30 second process**

## Features

- Web Socket (Pusher)
- Job & Queue (Redis)
- Docker for Redis Service
- Real-time Progress
- Upsert Process
- Mysql Database

## Installation

use *composer install* to install all package
```bash
composer install
```
**setting your .env (alignment with your database & pusher)**

install laravel-echo & pusher

```bash
npm install --save laravel-echo pusher-js
```

run this command
```bash
php artisan migrate
```

```bash
php artisan key:generate
```

using this command to up redis
```bash
docker-compose up -d
```

run a server
```bash
php artisan serve
```

run a queue
```bash
php artisan queue:listen --timeout=0
```

run a web socket
```bash
php artisan websockets:serve
```
after this command , you can access http://127.0.0.1:8000/laravel-websockets to check web socker running or no


