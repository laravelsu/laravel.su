---
title: "Фокус на работе приложения"
description: "Использование <code>Actions</code> позволяет сосредоточиться на бизнес-логике приложения, а не на технических деталях."
---

Классы `Action` выполняют конкретные задачи и изолируют их от других частей приложения, что упрощает понимание кода и
его поддержку. Логика, связанная с выполнением одной задачи, собирается в одном месте, что облегчает её изменение и тестирование.

Пример класса действия:

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

Данный класс можно вызвать как функцию:

```php
$generator = new GenerateReservationCode();
$reservationCode = $generator(8); // Генерация кода длиной 8 символов
```
