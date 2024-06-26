Для докеризации используется отдельный пакет https://github.com/HMRDevil/lite-docker-symfony

```
Добавить в системный HOSTS-файл строку:
127.0.0.1       localhost
git clone https://github.com/HMRDevil/lite-docker-symfony docker_project_name
cd docker_project_name
git clone https://github.com/HMRDevil/TestExample.git app
cd app
composer install
cd ..
docker-compose up --build -d
docker-compose exec php php bin/console doctrine:migrations:migrate
При необходимости - загрузить фикстуры
docker-compose exec php php bin/console doctrine:fixtures:load
```
Приложение доступно по адресу https://localhost
