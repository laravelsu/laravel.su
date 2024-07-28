---
title: "Принцип открытости/закрытости"
description: "Классы должны предоставлять интерфейсы для их использования, все остальное должно быть закрыто."
---

Принцип открытости/закрытости гласит, что классы должны быть открыты для расширения (путем добавления нового кода) и
закрыты для модификации (существующий код не должен изменяться).

Принцип открытости/закрытости обеспечивает гибкость и стабильность программного кода. Суть его заключается в том, что
после того как класс написан и протестирован, его код не должен изменяться при добавлении новой функциональности. Вместо
этого, новая функциональность должна добавляться через расширение класса или использование интерфейсов.

Давай посмотрим на пример, чтобы понять это.

```php
class Programmer
{
    public function code()
    {
        return 'coding';
    }
}

class Tester
{
    public function test()
    {
        return 'testing';
    }
}

class ProjectManagement
{
    public function process($member)
    {
        if ($member instanceof Programmer) {
            $member->code();
        } elseif ($member instanceof Tester) {
            $member->test();
        };

        throw new Exception('Invalid input member');
    }
}
```

Этот код нарушает принцип открытости/закрытости, потому что для добавления новых видов сотрудников (например,
дизайнеров) нам придется изменять класс `ProjectManagement`.

Давай исправим это, используя интерфейсы:

```php
interface Workable
{
    public function work();
}

class Programmer implements Workable
{
    public function work()
    {
        return 'coding';
    }
}

class Tester implements Workable
{
    public function work()
    {
        return 'testing';
    }
}

class ProjectManagement
{
    public function process(Workable $member)
    {
        return $member->work();
    }
}
```

Теперь мы используем интерфейс `Workable`, который гарантирует, что все классы, реализующие этот интерфейс, будут иметь
метод `work()`. Таким образом, мы можем добавлять новые типы сотрудников, реализующих интерфейс `Workable`, без изменения
кода класса `ProjectManagement`. Это соответствует принципу открытости/закрытости.
