# Boyarin

Автономный Telegram-антиспам для групп Laravel Russia. Сервис принимает webhook
через ReactPHP, проверяет сообщения через CAS и локальный классификатор,
удаляет спам и блокирует отправителя на сутки. Доверенные Telegram-пользователи
задаются вручную в `resources/trusted-users.json`.

Из [mattstauffer/torch](https://github.com/mattstauffer/torch) используются
самостоятельные Artisan Console и Illuminate Support с Collections/helpers.
Базы данных, очереди и Laravel-приложения здесь нет; ReactPHP отвечает за
HTTP-сервер и асинхронные запросы к Telegram/CAS.

## Запуск

```bash
composer setup
# заполнить TELEGRAM_BOT_TOKEN, WEBHOOK_URL и WEBHOOK_SECRET
docker compose up -d --build
```

Docker Compose автоматически использует `compose.yaml`. Образ собирается из
`Dockerfile` на официальных образах `php:8.5-cli-alpine` и `composer:2`.

Как и в Laravel, `.env.example` хранится в Git, а локальный `.env` игнорируется.
`composer setup` создаёт отсутствующие `.env` и `trusted-users.json`, но никогда
не перезаписывает существующие настройки.

Контейнер запускает `composer start`: команда проверяет конфигурацию и локальные
JSON-ресурсы, регистрирует Telegram webhook и запускает сервер.
Регистрация webhook идемпотентна и безопасно повторяется после перезапуска.
Некорректная конфигурация останавливает запуск; при временной недоступности
Telegram сервер запускается и повторяет регистрацию после следующего рестарта.

`WEBHOOK_URL` должен быть публичным HTTPS URL и оканчиваться тем же путём, что
`WEBHOOK_PATH`. Reverse proxy должен направлять его на порт сервиса `8080`.

Для сброса непрочитанных Telegram updates при регистрации:

```bash
docker compose exec boyarin php artisan webhook:register --drop-pending-updates
```

Проверка состояния доступна на `GET /health`.

Monolog пишет логи процесса в `stderr`. Docker автоматически собирает этот
поток, поэтому сервис не создаёт log-файлы и не управляет их ротацией.

## Как принимается решение

Для каждого текстового сообщения сервис последовательно:

1. пропускает ответы и сообщения с блоком кода `pre`;
2. пропускает пользователей из `resources/trusted-users.json`;
3. проверяет Telegram ID пользователя через CAS;
4. если CAS не нашёл спамера, применяет стоп-слова, эвристику специальных
   символов и локальный классификатор;
5. удаляет спам-сообщение и временно запрещает автору отправлять сообщения.

URL не является причиной пропустить сообщение: текст со ссылкой проходит те же
проверки. Если CAS временно недоступен, решение принимает локальный
классификатор.

## Структура

- `src/Console` — тонкие Artisan-команды запуска и регистрации webhook;
- `src/Telegram` — HTTP-сервер, обработка updates, Telegram/CAS и регистрация
  webhook;
- `src/Spam` — доверенные пользователи, стоп-слова и локальный классификатор;
- `resources` — редактируемые JSON-настройки и обучающие выборки.

## Доверенные пользователи

Пользователи, которых не нужно проверять на спам, перечисляются по Telegram ID
из поля `update.message.from.id`:

```json
[
    123456789,
    987654321
]
```

Рабочий файл `resources/trusted-users.json` автоматически создаётся при первом
запуске. Он не хранится в Git и не попадает в Docker-образ, поэтому локальные ID
не конфликтуют с `git pull`. В репозитории находится только безопасный пример
[resources/trusted-users.example.json](resources/trusted-users.example.json).

Сервис перечитывает рабочий файл при каждом сообщении, поэтому после
редактирования не требуется пересборка или перезапуск контейнера. Пока редактор
записывает некорректный JSON, продолжает использоваться последняя валидная
версия.

## Локальная разработка

```bash
composer install
composer setup
composer start
composer test
```

Если при локальном запуске регистрировать webhook не нужно:

```bash
php artisan start --skip-webhook
```

Отдельные служебные команды:

```bash
php artisan serve
php artisan webhook:register
```

Классификатор обучается при старте из `resources/classifiers/ham.json` и
`resources/classifiers/spam.json`, а стоп-слова читаются из
`resources/stop-words.json`.

Новые примеры добавляются отдельными Artisan-командами:

```bash
php artisan add:spam "Реклама! Выплаты"
php artisan add:ham "Обсуждаем Laravel и PHP"
```

Команды не добавляют дубликаты. Изменения `spam.json` и `ham.json` можно
проверить и зафиксировать в Git обычным способом.

## Развернуть на laravel.su

Production использует отдельный контейнер Boyarin в существующей сети Kamal.
Публичный обработчик находится на `https://148.253.210.79:88/webhook/telegram`.
Порт контейнера наружу не публикуется. Сайт продолжает работать через свой маршрут.

На сервере сохраните настройки в `/opt/laravel-su/shared/boyarin/.env` с правами
`600`. Укажите действующие `TELEGRAM_BOT_TOKEN`, случайный `WEBHOOK_SECRET`,
`WEBHOOK_PATH=/webhook/telegram` и указанный выше `WEBHOOK_URL`.
Доверенные ID хранятся в `/opt/laravel-su/shared/boyarin/resources/trusted-users.json`.
Секреты и этот список не публикуются в Git.

Из корня загруженного релиза на сервере выполните:

```sh
bin/deploy-boyarin-server "$PWD/boyarin"
bin/register-boyarin-webhook
```

Вторая команда загружает публичный TLS-сертификат в Telegram. Это необходимо
для текущего самоподписанного сертификата. Команда сохраняет ожидающие updates.
Регистрация выполняется после проверки контейнера и маршрута. Production
запускает `start --skip-webhook`, чтобы перезапуск не менял сертификат webhook.
После замены TLS-сертификата повторите `bin/register-boyarin-webhook`.

Боту нужны права администратора группы на удаление сообщений и ограничение
участников. Настройка webhook сама по себе этих прав не выдаёт.
