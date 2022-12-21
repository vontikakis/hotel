<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Hotel project</h1>
    <br>
</p>

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.4.


INSTALLATION
------------

### Install via Composer

we assume you already have install composer just press

composer install

and packages will install in vendor folder


CONFIGURATION
-------------

### Database

enter user and password in your db

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=hotel',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
];
```

Sample database


calendars for rooms is from 2023-01-01 to 2023-03-31

sample get request and response is in json

http://localhost/index.php?r=site/index&startDate=2023-01-15&endDate=2023-01-25