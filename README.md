# vorozhzov
CRUD for Objects

Install
Clone
in root directory:

Run "composer update"
Rename .env.example to .env
Run "php artisan key:generate"
Set Database name as you like (in .env)

Run "php artisan migrate" ("yes" for "create db")
Run "npm install"

Run "php artisan serve"
Run "npm run dev"

To create user:
Run "php artisan tinker"
in shell: User::create(['name'=>'your name', 'email'=>'your@mail.com','password'=>bcrypt('yourpassword')]);

Run "php artisan test --filter DatabaseTest" for to seed users

To get user token (for existing user!!):

Run "php artisan gettoken your@mail.com yourpassword"

expiring time for token = 5 minutes

Route: /create 
with valid token create entry in table jsons in DB

Route /list: listing records from table jsons

Use links "read", "delete", "update" (here: "/list") to manipulate records

in updateForm paths for object should look like:
$data->list->sublist[0] = 0;
$data->list->sublist[1] = 2;
etc.

one path per line!!!

For path that doesn't exists in object you get error!


For deployment:

Example NGINX config:

server {
    listen 80;
    listen [::]:80;
    server_name example.com;
    root /srv/example.com/public;
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}

info: https://laravel.com/docs/9.x/deployment
