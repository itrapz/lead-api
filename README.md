##  Starting Project
git clone the project, and go to its directory (/lead-api), and start:
```
docker-compose up -d
```

##  Create DB tables
Run composer and migrations with user creation
```
docker exec lead-api-php bash -c "composer install && php bin/console doctrine:migrations:migrate -n && php bin/console app:create-user"
```

Project api will available here:
http://localhost:8077/api

## Add leads handler
Lead handling was implemented asynchronously to support over 1000 requests per minute. Make sure to run LeadMessageHandler to process each POST request to /leads asynchronously%
```
docker exec -d lead-api-php bash -c "php bin/console messenger:consume lead_queue"
```
Each POST request to the /leads endpoint now stores data in the api_requests and api_responses database tables, and is processed asynchronously.

## Endpoint Postman collection
This collection is provided in (lays in root directory):
```
lead-api.postman_collection.json
```

You can also try sending your own request using cURL. For example:
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
I decided to create a separate test environment to ensure clean and isolated testing. In this setup, we need to run the API and the tests separately. Let’s create a dedicated test database to store all testing data independently.
```
docker exec -it lead-api-php php bin/console doctrine:migrations:migrate --env=test -n
```

Run test api instances
```
docker-compose -f docker-compose.yml -f docker-compose.test.yml up -d api_test nginx_test
```
Create test user
```
docker exec -it lead-api-api_test php bin/console app:create-user
```
And launch test lead handler worker
```
docker exec -d lead-api-api_test bash -c "php bin/console messenger:consume lead_queue"
```

Then, run the testing script and review the results:
```
docker run -i grafana/k6 run - < test_script.js
```
To test the load level of the endpoint, you can customize the duration and number of virtual users in test_script.js.
If you encounter issues running the k6 package, you can install it using:
```
brew install k6   
```

All testing data will be stored separately in the lead_db_test database.