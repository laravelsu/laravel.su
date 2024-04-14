---
title: "Принцип инверсии зависимостей"
description: "Любые более высокие (дочерние) классы всегда должны зависеть от абстракций, а не от деталей."
---

Принцип инверсии зависимостей (Dependency Inversion Principle, DIP) гласит, что модули высокого уровня не должны
зависеть от модулей низкого уровня, а оба типа модулей должны зависеть от абстракций. Также он утверждает, что
абстракции не должны зависеть от деталей, а детали должны зависеть от абстракций.

Давай разберемся, что это означает на примере:

```php
<?php
// Нарушение принципа инверсии зависимостей
class Mailer
{

}

class SendWelcomeMessage
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
}
```

Здесь класс SendWelcomeMessage зависит от конкретной реализации Mailer, что нарушает принцип инверсии зависимостей. Если
мы захотим изменить способ отправки сообщений, нам придется изменять и класс SendWelcomeMessage.

Для исправления этой проблемы мы можем использовать абстракцию в виде интерфейса Mailer:

```php
interface Mailer
{
    public function send();
}

class SmtpMailer implements Mailer
{
    public function send()
    {
        // Реализация отправки через SMTP
    }
}

class SendGridMailer implements Mailer
{
    public function send()
    {
        // Реализация отправки через SendGrid
    }
}

class SendWelcomeMessage
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
}
```

Теперь класс SendWelcomeMessage зависит от абстракции Mailer, а не от конкретной реализации. Мы можем легко изменить
способ отправки сообщений, просто передавая нужную реализацию интерфейса Mailer в конструктор SendWelcomeMessage. Это
соответствует принципу инверсии зависимостей.
