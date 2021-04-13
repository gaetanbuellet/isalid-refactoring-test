help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

vendor: ## Install PHP dependencies using composer
	docker run --rm --interactive --tty --volume ${PWD}:/app composer install --ignore-platform-reqs

.PHONY: run-example
run-example: ## Run the sample file
	 docker run -it --rm --name isalid-php -v "${PWD}":/usr/src/app -w /usr/src/app php:7.4-cli php example/example.php

.PHONY: run-test
run-test: ## Run the unit test
	 docker run -it --rm --name  isalid-php -v "${PWD}":/usr/src/app -w /usr/src/app php:7.4-cli php vendor/bin/phpunit
