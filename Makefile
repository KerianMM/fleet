##
## Tests
## -----
##
behat:
	php vendor/bin/behat --strict --config=./behat.yml

unit:
	php vendor/bin/phpunit

infection:
	@$(eval flags ?= '')
	php ./vendor/bin/infection $(flags) --threads=max
ifdef open
	open ./.infection/infection.html
endif

##
## Quality assurance
## -----------------
##

tools:
	mkdir $@

tools/phpmd/composer.lock: tools/phpmd/composer.json
	composer --working-dir=tools/phpmd update

tools/phpmd/vendor: composer.lock
	composer --working-dir=tools/phpmd install

phpmd: tools/phpmd/vendor
	php tools/phpmd/vendor/bin/phpmd $(arguments)

apply-phpmd:
	$(MAKE) phpmd arguments="src,tests text .phpmd.xml"

update-phpmd:
	composer --working-dir=tools/phpmd update

tools/phpstan/composer.lock: tools/phpstan/composer.json
	composer --working-dir=tools/phpstan update

tools/phpstan/vendor: composer.lock
	composer --working-dir=tools/phpstan install

phpstan: tools/phpstan/vendor
	php tools/phpstan/vendor/bin/phpstan $(arguments)

apply-phpstan:
	$(MAKE) phpstan arguments="analyse --memory-limit=-1 -c .phpstan.neon"

update-phpstan:
	composer --working-dir=tools/phpstan update

tools/php-cs-fixer/composer.lock: tools/php-cs-fixer/composer.json
	composer --working-dir=tools/php-cs-fixer update

tools/php-cs-fixer/vendor: composer.lock
	composer --working-dir=tools/php-cs-fixer install

php-cs-fixer: tools/php-cs-fixer/vendor
	php tools/php-cs-fixer/vendor/bin/php-cs-fixer $(arguments)

check-php-cs:
	$(MAKE) php-cs-fixer arguments="fix --dry-run --using-cache=no --verbose --diff"

apply-php-cs:
	$(MAKE) php-cs-fixer arguments="fix --using-cache=no --verbose --diff"

update-php-cs-fixer:
	composer --working-dir=tools/php-cs-fixer update

pre-commit: apply-phpmd apply-php-cs apply-phpstan
