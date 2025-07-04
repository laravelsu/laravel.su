---
title: "Высокие нагрузки"
description: "Это не язык программирования или фреймворк"
---

> “Laravel не для highload. Настоящие проекты на Go, Elixir или хотя бы Java.”

Highload — это не язык программирования или фреймворк. Это определенный подход и навыки. Если разработчик не знает, как оптимизировать запросы, кэшировать данные и масштабировать приложение, то никакой фреймворк не поможет. 

То есть если ты делаешь по одному запросу к базе данных в каждой итерации foreach, никакой Go не спасёт.

Laravel умеет отлично масштабироваться и при этом имеет множество инструментов для этого. Например:

- Сложные вычисления, запросы к внешним сервисам или сбор данных можно сохранить в быстрый кеш — Redis или Memcached.

- Очереди задач и асинхронность. Отправка писем, обработка файлов или подсчет статистики — всё это можно вынести из основного запроса в фон. Очереди могут работать на нескольких серверах, а инструмент Laravel Horizon помогает контролировать состояние задач, автоматически перезапускает сбои и масштабирует воркеров.

- Максимум из одного сервера с Laravel Octane. В отличие от обычного подхода, где фреймворк загружается на каждый запрос, Octane держит приложение «живым» на сервере, что заметно снижает задержки и увеличивает скорость обработки.

- Облачные решения — Laravel Vapor и Cloud. Если хочется забыть про сервера и рутинные задачи DevOps, эти платформы возьмут на себя масштабирование и управление инфраструктурой, позволяя сосредоточиться только на написании кода.
