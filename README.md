##  Starting Project
git clone the project, and go to its directory (/lead-api), and start:
```
docker-compose up -d
```

##  Create DB tables
run composer
```
docker exec -it lead-api-php composer install
```
and run migration and type yes:
```
docker exec -it lead-api-php php bin/console doctrine:migrations:migrate
```

project api will available here:
http://localhost:8077/api


##  Create User with token
run command
```
docker exec -it lead-api-php php bin/console app:create-user
```

## Add leads handler
Lead handling was implemented asynchronously to support over 1000 requests per minute. Make sure to run LeadMessageHandler to process each POST request to /leads asynchronously%
```
docker exec -it lead-api-php php bin/console messenger:consume lead_queue -vv
```

Now every POST request to the /leads endpoint stores data in the api_requests and api_responses database tables, and each request is also processed asynchronously.

## Endpoint Postman collection
This collection is provided in (lays in root directory):
```
lead-api.postman_collection.json
```

but you also can try to send your own request with curl, for example:
```
curl --location 'http://localhost:8077/api/leads' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer testtoken123' \
--data-raw '{
    "firstName": "check1",
    "lastName": "check2",
    "email": "check@email.com",
    "phone": "9295032303",
    "dateOfBirth": "2002-01-02",
    "dynamicData": {
        "middleName": 1,
        "hobbies": ["tennis", "video games"]
    }
}'
```

##  Load Testing
I decided to create a separate test environment to ensure clean and isolated testing processes. In this setup, we need to run the API and tests separately. Let's create a test database to store all testing data independently.
```
docker exec -it lead-api-php php bin/console doctrine:migrations:migrate --env=test
```

run test api instances
```
docker-compose -f docker-compose.yml -f docker-compose.test.yml up -d api_test nginx_test
```
and launch test lead handler worker
```
docker exec -it lead-api-api_test php bin/console messenger:consume lead_queue
```

Then run the testing script:
```
docker run -i grafana/k6 run - < test_script.js
```
to test endpoint's load level
btw, you can customize time and users in  test_script.js

if you have a problem with k6 package to run testing, install it with:
```
brew install k6   
```

