---
title: "Пакет Laravel Actions"
description: "Действие можно удобно запустить как объект, контроллер, фоновую задачу и консольную команду."
---

В экосистеме Laravel есть прекрасный пакет `Laravel Actions` который способствует организации кода вокруг действий.
Данный пакет позволяет создавать классы действий, которые могут быть вызваны в различных контекстах, таких как контроллеры, события и консольные команды. 
Это обеспечивает более универсальный и гибкий код.


Пример использования:

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

Класс можно вызвать следующим образом:

```php
GenerateReservationCode::run()
```

Если вам нужно выполнить действие в очереди, то вы так же можете это сделать, например:

```php
GenerateReservationCode::dispatch();
```

> **Примечание** Вы можете узнать больше об удобстве использование действий с пакетом `Laravel Actions` на его [официальном сайте](https://laravelactions.com/).

