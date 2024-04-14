---
title: "Принцип разделения интерфейса"
description: "Интерфейсов должно быть много."
---

Принцип разделения интерфейса (Interface Segregation Principle, ISP) предписывает, что клиенты не должны зависеть от
методов, которые они не используют. Вместо этого интерфейсы должны быть разделены на более мелкие, специализированные
интерфейсы, чтобы клиенты могли реализовывать только те методы, которые им нужны.

Давай разберем, как это работает на примере:

```php
// Нарушение принципа разделения интерфейса
interface Workable
{
    public function canCode();
    public function code();
    public function test();
}

class Programmer implements Workable
{
    public function canCode()
    {
        return true;
    }

    public function code()
    {
        return 'coding';
    }

    public function test()
    {
        return 'testing in localhost';
    }
}

class Tester implements Workable
{
    public function canCode()
    {
        return false;
    }

    public function code()
    {
         throw new Exception('Opps! I can not code');
    }

    public function test()
    {
        return 'testing in test server';
    }
}

class ProjectManagement
{
    public function processCode(Workable $member)
    {
        if ($member->canCode()) {
            $member->code();
        }
    }
}
```

В этом примере интерфейс Workable содержит методы canCode(), code() и test(). Проблема в том, что не все классы,
реализующие этот интерфейс, могут выполнять все эти действия. Например, класс Tester не может кодировать, но он должен
реализовать метод code(), потому что интерфейс Workable требует это.

Чтобы исправить это, мы можем разделить интерфейс на более мелкие и специализированные интерфейсы:

```php
// Улучшенный вариант
interface Codeable
{
    public function code();
}

interface Testable
{
    public function test();
}

class Programmer implements Codeable, Testable
{
    public function code()
    {
        return 'coding';
    }

    public function test()
    {
        return 'testing in localhost';
    }
}

class Tester implements Testable
{
    public function test()
    {
        return 'testing in test server';
    }
}

class ProjectManagement
{
    public function processCode(Codeable $member)
    {
        $member->code();
    }
}
```

Теперь интерфейсы Codeable и Testable более специализированы. Класс Programmer реализует оба интерфейса, потому что он
может кодировать и тестировать. Класс Tester реализует только интерфейс Testable, потому что он может только
тестировать. Таким образом, классы могут реализовывать только те методы, которые им нужны, что соответствует принципу
разделения интерфейса.
