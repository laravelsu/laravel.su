---
title: "Принцип подстановки Барбары Лисков"
description: "Дочерние классы должны работать так, что бы ими можно было заменить родительские."
---

Принцип подстановки Барбары Лисков (Liskov Substitution Principle, LSP) утверждает, что поведение подклассов должно быть
совместимо с поведением их суперклассов. Другими словами, объекты подтипов должны быть заменяемыми экземплярами своих
супертипов без изменения ожидаемого поведения программы. Давай разберемся с примером, чтобы лучше понять этот принцип:

```php
<?php

// Нарушение принципа подстановки Барбары Лисков
// Проблема квадрата и прямоугольника
class Rectangle
{
    protected $width;
    protected $height;

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function area()
    {
         return $this->height * $this->width;
    }
}

class Square extends Rectangle
{
    public function setHeight($value)
    {
        $this->width = $value;
        $this->height = $value;
    }

    public function setWidth($value)
    {
        $this->width = $value;
        $this->height = $value;
    }
}

class RectangleTest
{
    private $rectangle;

    public function __construct(Rectangle $rectangle)
    {
        $this->rectangle = $rectangle;
    }

    public function testArea()
    {
        $this->rectangle->setHeight(2);
        $this->rectangle->setWidth(3);
        // Ожидаем, что площадь прямоугольника будет 6
    }
}
```

В данном примере класс Square наследуется от Rectangle, что кажется логичным, так как квадрат является частным случаем
прямоугольника. Однако, нарушается принцип подстановки Барбары Лисков из-за того, что Square переопределяет методы
setHeight() и setWidth() так, чтобы они всегда делали высоту равной ширине.

Что делает этот пример нарушением LSP? Дело в том, что ожидается, что при вызове setHeight() и setWidth() объекта
Rectangle сначала будет изменяться одно измерение, а потом другое. Однако в случае Square эти методы нарушают это
ожидание, что может привести к непредсказуемому поведению в программах, которые используют Rectangle или его подтипы.

Как исправить это? Один из способов - пересмотреть архитектуру классов так, чтобы Square не наследовался от Rectangle,
так как это нарушает LSP. Вместо этого, можно использовать композицию или выделить общий интерфейс для обоих классов и
разработать их независимо.
