# Coding Challenge
Our product is a marketplace platform where customers can find human advisors. These
advisors can be contacted through our online-platform. In order to manage the advisors’
we have a service with multiple API endpoints, that supports the
following user stories. In our case the user refers to another service developer, which is
using your API in his own backend client.

## Required
- [git](https://git-scm.com/)
- [docker](https://docs.docker.com/get-docker/)
- [make](https://www.gnu.org/software/make/)

## How to run

```sh
git clone https://github.com/olga-pashkevych/coding-challenge-rest.git
cd coding-challenge-rest
make start
```
Wait a few minutes till all containers will start up ☕

Then you need Database, for this case you should make:
```sh
make migrate
```
If you need preset data, use fixtures:
```sh
make fixture
```
## How to use

Go to [http://localhost:8000/api/doc](http://localhost:8080/api/doc)

You can see documentation about our Advisors API. There you can make real request calls to a Database and see what the response is.

After all you can stop application by:
```sh
make stop
```
Or:
```sh
docker-compose down
```
## How to test
```sh
cd coding-challenge-rest
make tests
```
Or:

```sh
docker run -v `pwd`:/app -w /app --rm keinos/php8-jit vendor/bin/phpunit -c phpunit.xml.dist
```
You should see:

```sh
Testing
.......                                                             7 / 7 (100%)

Time: 00:00.179, Memory: 22.00 MB

OK (7 tests, 14 assertions)

```
That's mean, all tests are green - system works as expected!
