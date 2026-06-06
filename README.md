File Download System
A system where a cloud server can pull a file from any on-premise client (e.g. a restaurant PC) on demand.
The challenge is that the client sits behind a private network, so the server can't reach it directly. Instead, the client polls the server every few seconds asking "any commands for me?" — and when the server wants a file, the client uploads it.

How it works
1. Client starts up and registers itself with the server
2. Client polls GET /api/check-command every 5 seconds
3. Someone triggers POST /api/request-download on the server
4. Next time the client polls, it gets back "upload_file"
5. Client uploads the file to the server
6. Server saves it to storage/app/downloads/

Stack
Server: Laravel (PHP) + MySQL
Client: PHP CLI script

Server Setup

1. Install dependencies
composer install
2. Set up your .env
cp .env.example .env
php artisan key:generate

Update these in .env:
DB_DATABASE=file_download
DB_USERNAME=root
DB_PASSWORD=
3. Create the Database
In MySQL:
CREATE DATABASE file_download;
4. Run migrations
php artisan migrate
5. Start The Server
php artisan serve

Client Setup
The client script lives in client.php (outside the Laravel project, e.g. C:\client\client.php).
1. Create the test file (100MB)
Run this in PowerShell:
$out = [System.IO.File]::OpenWrite("$HOME\file_to_download.txt")
$out.SetLength(100MB)
$out.Close()

2. Run the client
php C:\client\client.php

You should see:
Registered: {"message":"registered",...}
Polling... command: none
Polling... command: none

Via Postman:
POST http://127.0.0.1:8000/api/request-download
Body: { "client_id": "restaurant_001" }

Via curl:
curl -X POST http://127.0.0.1:8000/api/request-download \
     -H "Content-Type: application/json" \
     -d "{\"client_id\": \"restaurant_001\"}"

The client terminal will then show:
Polling... command: upload_file
Uploading file...
Upload result: {"message":"File received","filename":"..."}

API endpoints:
POST /api/registerClient          registers with the server
GET  /api/check-commandClient     polls for commands
POST /api/request-download        Trigger a file download from a client
POST /api/upload-fileClient       uploads the file

Where files are saved
Uploaded files go to:
storage/app/downloads/{client_id}_{timestamp}_{filename}
