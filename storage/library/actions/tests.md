---
title: "Тестирование"
description: "Что такое принцип 'Один класс — одна задача'?"
---

Поскольку каждый `Action` отвечает за одну задачу, его тестирование становится более простым и эффективным.
Вы можете изолировать и протестировать каждое действие отдельно, что упрощает написание и выполнение тестов.

```php
class GenerateReservationCodeTest extends TestCase
{
    public function testGeneratedCodeContainsOnlyAllowedCharacters(): void
    {
        $code = GenerateReservationCode::run(8);

        $this->assertMatchesRegularExpression(
            '/^[BCDFGHJLMNPRSTVWXYZ2456789]+$/',
             $code
        );
    }

    public function testGeneratedCodeIsUrlSafe(): void
    {
        $code = GenerateReservationCode::run();

        $this->assertTrue(
            filter_var($code, FILTER_VALIDATE_URL) === false
        );
    }
}
```

А наличие четко определенных входных и выходных данных позволят легко
обнаруживать и исправлять ошибки.
