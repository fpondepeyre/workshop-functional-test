WORKSHOP FUNCTIONAL TEST
========================

```
#install project
composer install
vendor/bin/bdi detect drivers
yarn && yarn encore dev-server
docker-compose up -d
make install
```

```
#run web server
symfony serve -d
```

```
#run test
make tests
```

## Cases to tests:

used libs:
- https://github.com/zenstruck/foundry
- https://github.com/zenstruck/messenger-test
- https://github.com/zenstruck/browser
- https://github.com/symfony/panther
- https://github.com/dbrekelmans/bdi
- https://github.com/briannesbitt/carbon
- https://github.com/paratestphp/paratest

```
Calcultar.php # unit test, TestCase
./bin/console app app:csv:product # generate csv with a command, KernelTestCase

src/Repository/ProductRepository # test search method, KernelTestCase

https://127.0.0.1:8000/ #dummmy page, WebTestCase
https://127.0.0.1:8000/call_api #call external api with ramdom result, WebTestCase
https://127.0.0.1:8000/product/1/edit #simple form, WebTestCase

curl --request POST \
--url https://127.0.0.1:8000/api/products \
--header 'Content-Type: application/json' \
--data '{
"name": "demo2",
"imageFilename": "pen.png"
}' # api which send a message to rabbitmq in order to send an email, ApiTestCase

https://127.0.0.1:8000/search #form search with ajax, PantherTestCase
```

## troubleshooting

Find docker ip for .env
```
docker inspect workshop_functional_test__database_1 --format='{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'
docker inspect workshop_functional_test__rabbitmq_1 --format='{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'
docker inspect workshop_functional_test_mailer_1 --format='{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'

DATABASE_URL="postgresql://symfony:ChangeMe@172.23.0.4:5432/app?serverVersion=13&charset=utf8"
RABBITMQ_URL=amqp://guest:guest@172.27.0.3:5672/%2f/messages
MAILER_DSN=smtp://172.27.0.2:1025
```