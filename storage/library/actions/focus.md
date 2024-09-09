---
title: "Фокус на работе приложения"
description: "Что такое принцип 'Один класс — одна задача'?"
---

Использование `Actions` позволяет сосредоточиться на бизнес-логике приложения, а не на технических деталях.
Классы `Action` выполняют конкретные задачи и изолируют их от других частей приложения, что упрощает понимание кода и
его поддержку. Логика, связанная с выполнением одной задачи, собирается в одном месте, что облегчает её изменение и тестирование.


```php
class GenerateReservationCode
{
    const UNAMBIGUOUS_ALPHABET = 'BCDFGHJLMNPRSTVWXYZ2456789';

    public function __invoke(int $characters = 7): string
    {
        do {
            $code = $this->generateCode($characters);
        } while (Reservation::where('code', $code)->exists());

        return $code;
    }

    protected function generateCode(int $characters): string
    {
        return substr(str_shuffle(str_repeat(static::UNAMBIGUOUS_ALPHABET, $characters)), 0, $characters);
    }
}
```

Это позволит вам вызывать объект класса, как если бы он был функцией. Например:
```php
$generator = new GenerateReservationCode();
$reservationCode = $generator(8); // Генерация кода длиной 8 символов
```


В это системе Laravel есть прекрасный пакет `Laravel Actions` — это пакет, который предлагает новый способ организации
логики вашего Laravel-приложения, сосредоточив внимание на действиях, которые выполняет ваше приложение. Вместо создания
контроллеров, джобов, слушателей и других элементов, этот пакет позволяет создавать PHP-классы, каждый из которых
выполняет одну конкретную задачу. Эти классы можно запускать как угодно: из контроллеров, консольных команд, событий и
так далее.

```php
class GenerateReservationCode
{
    use AsAction;

    const UNAMBIGUOUS_ALPHABET = 'BCDFGHJLMNPRSTVWXYZ2456789';

    public function handle(int $characters = 7): string
    {
        do {
            $code = $this->generateCode($characters);
        } while(Reservation::where('code', $code)->exists());

        return $code;
    }

    protected function generateCode(int $characters): string
    {
        return substr(str_shuffle(str_repeat(static::UNAMBIGUOUS_ALPHABET, $characters)), 0, $characters);
    }
}
```

и вызывать его так:
```php
GenerateReservationCode::run() // Генерация кода длиной 7 символов
```

> **Примечание** Вы можете узнать больше об удобстве использование действий с пакетом `Laravel Actions` на его [официальном сайте](https://laravelactions.com/).

Но вам не обязательно использовать пакет, чтобы следовать принципу «Один класс — одна задача». Вы можете создавать свои
собственные классы действий, используя стандартные средства Laravel/PHP. Важно помнить, что главная цель — разделить логику
вашего приложения на небольшие, легко понимаемые и поддерживаемые части.
