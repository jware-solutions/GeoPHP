# Contibuting

It's nice to have people like you interested in this project. In this document we specify all the things to be considered for developing in this library.

## Preparing the environment

### With Docker

You have available a **Docker Compose** file with a custom image with all installed if It's of your preference. Just run inside the project's root folder:

`docker-compose run geophp`

The image specified in `Dockerfile` file will be compiled and run.

### Without Docker

You need the following tools installed:

- PHP 7.x
- [Composer](https://getcomposer.org/)


## Developing

All the code and test are at `src` and `test` folders respectively.

There is no standard convention about code and comments, but It would really appreciate that you respect the convention used until now, specifying docs for all the methods indicating types and description for their parameters and returned values.

All the public method (except some getters and setters) are covered with unit test. Once you have added some code and want to run the tests you can execute [PHPUnit](https://phpunit.de/) with the following command:

`./vendor/bin/phpunit`

To check that all is OK.
