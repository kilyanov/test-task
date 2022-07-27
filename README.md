Структура папок
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains web controller classes
      mail/               contains view files for e-mails
      migrations/         contains migrations for database
      models/             contains model classes
      modules/            contains modules files
      runtime/            contains files generated during runtime and JWT RS256 key
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources


Добавляем соединение для работы с базой PostgresSql
---
```
Host: localhost
Port: 5432
Database: test_yii
User: app
Password: secret
```

How to generate JWT RS256 key
-
```
cd app/runtime
ssh-keygen -t rsa -b 4096 -m PEM -f jwtRS256.key
```
# Don't add passphrase (Генерировать ключи без задания пароля)
```
openssl rsa -in jwtRS256.key -pubout -outform PEM -out jwtRS256.key.pub
cat jwtRS256.key
cat jwtRS256.key.pub
```