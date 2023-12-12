# Requirements
To run this project you will need a computer with PHP and composer installed.

# Install
To install the project, you just have to run `composer install` to get all the dependencies

# Running the tests
## Integration
After installing the dependencies you can run integration tests with this command `make behat`.

## Unit
After installing the dependencies you can run unit tests with this command `make unit`.

To check tests quality, run `make infection` or `make infection open=1` if you want to show the report.
(This requires xdebug extension.)

# Running the cli
Run `php bin/main.php` to manage the fleet interactively.
