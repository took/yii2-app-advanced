# Yii 2 Advanced Project Template V2

Yii 2 Advanced Project Template is a skeleton [Yii 2](https://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The V2 template includes four default tiers: frontpage, backoffice, api, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Full documentation is at [docs/guide/README.md](docs/guide/README.md).


DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backoffice and frontpage
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime    
api
    config/              contains api configurations
    controllers/         contains api controllers (commands)
    models/              contains api-specific model classes
    runtime/             contains files generated during runtime
backoffice
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backoffice configurations
    controllers/         contains Web controller classes
    models/              contains backoffice-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backoffice application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontpage
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontpage configurations
    controllers/         contains Web controller classes
    models/              contains frontpage-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontpage application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontpage widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
    dev/                 contains local configurations templates for dev environments
    prod/                contains local configurations templates for prod environment
    stage/               contains fixed configurations for stage and test environment
```
