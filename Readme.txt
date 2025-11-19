Для развертывания выполнить команды в папке проекта:

# 1. Запускает nginx, PHP-FPM, PgSQL, redis, Rabbit итд.
docker-compose up -d

# 2. Заходим в PHP-контейнер
docker-compose exec app bash

# 3. Устанавливаем зависимости
composer install

# 4. Накатываем миграции БД
php bin/console doctrine:migrations:migrate

# 5. Чистим кеш
php bin/console cache:clear

# 6. Выходим, возвращаемся в хост-систему
exit
