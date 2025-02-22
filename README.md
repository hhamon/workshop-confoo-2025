# README

## Installation

```bash
$ symfony composer install
$ symfony console doctrine:database:create -e dev
$ symfony console doctrine:migration:migrate -e dev
$ symfony console doctrine:fixtures:load -e dev
$ symfony console build:frontend-dev
```