COMPOSER ?= composer

.PHONY: install test rector check clean

install:
	$(COMPOSER) install --no-interaction --ansi

test:
	./vendor/bin/phpunit

rector:
	./vendor/bin/rector process

check: install test

clean:
	rm -rf .phpunit.cache
