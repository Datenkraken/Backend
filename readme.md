Backend
=======

# Installation

## Manual

You will need php >= 7.2 (With the php-mongodb extension installed), mongodb, composer and npm.

Make sure to start the mongodb instance before running the server (eg. `sudo systemctl start mongodb`).

### Updating / Installing the project
You will then need to run the following commands to setup the project. 

Once on a fresh installation, you will need to create a .env file:  
`cp .env.example .env`
And generate an application key:  
`php artisan key:generate`

**The following will also be needed, if the project has been updated or changed.**

```
composer install
npm install

php artisan migrate:fresh
php artisan db:seed
php artisan passport:install

-- use this to regenerate css/js/resources
npm run dev
```

### Start the server

You can start the php server with:
`php artisan serve`

## Docker

You will need to install docker and docker-compose in order to run this project.
Then execute the following statements to start the project containers.
You might need to start the docker service before `sudo systemctl start docker`.

### Updating / Rebuilding the container

If you updated the project you will need to rebuild the container with the following command:

`sudo docker-compose build`

### Start the containers

Start the containers with the following command:
`sudo docker-compose up -d`

**NOTE: After the first start you will need to execute the following commands once:**  
Generate application key:
`sudo docker-compose exec web sudo php artisan key:generate`
Generate OAuth Clients / Secrets:
`sudo docker-compose exec web sudo php artisan passport:install`

### Stop / Remove containers

To stop the containers, you can use:
`sudo docker-compose stop`

To remove all containers, volumes / persistent storages:
`sudo docker-compose down -v --remove-orphans`

# Connect / Use the dashboard

The project should now be available on localhost (127.0.0.1) with port 8000 (when using the manual method) or port 5454.

A default admin account is available:
```
admin@admin.com
password
```
