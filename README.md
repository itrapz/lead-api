##  Starting Project
```
docker-compose up -d
```

##  Create DB tables

First of all, go into php container
```
docker exec -it lead-api-php bash
```
run composer
```
composer install
```
and run migration:
```
php bin/console doctrine:migrations:migrate
```

##  Create User with token
run command
```
php bin/console app:create-user
```

## Add leads handler
Lead handling was implemented asynchronously to support over 1000 requests per minute. Make sure to run LeadMessageHandler to process each POST request to /leads asynchronously%
```
php bin/console messenger:consume lead_queue -vv
```

Now every POST request to the /leads endpoint stores data in the api_requests and api_responses database tables, and each request is also processed asynchronously.