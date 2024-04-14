---
title: "Принцип единственной ответственности"
description: "Класс должен решать только одну задачу (иметь одну ответственность)."
---

Принцип единой ответственности (SRP) гласит, что каждый класс должен заниматься только одним делом. 
Вот пример, чтобы лучше понять это:

Представь, что у тебя есть класс "Отчет" (Report), который должен предоставлять информацию о некоторых данных. Однако, в
текущем виде он не соблюдает принцип SRP, потому что помимо предоставления данных он также занимается их форматированием
в JSON.

```php
class Report
{
    public function title(): string
    {
        return 'Report Title';
    }

    public function date(): string
    {
        return '2016-04-21';
    }

    public function contents(): array
    {
        return [
            'title' => $this->title(),
            'date' => $this->date(),
        ];
    }

    public function formatJson(): string
    {
        return json_encode($this->contents());
    }
}
```

Проблема здесь в том, что класс Report занимается слишком многим - он не только предоставляет данные, но и форматирует
их в JSON. Допустим, тебе потребуется отформатировать данные в HTML. В таком случае, класс Report придется изменять,
нарушая принцип SRP.

Чтобы исправить это, мы можем разделить ответственности. Давай создадим новый класс JsonReportFormatter, который будет
отвечать только за форматирование данных в JSON:

```php
class Report
{
    public function title(): string
    {
        return 'Report Title';
    }

    public function date(): string
    {
        return '2016-04-21';
    }

    public function contents(): array
    {
        return [
            'title' => $this->title(),
            'date' => $this->date(),
        ];
    }
}

interface ReportFormattable
{
    public function format(Report $report);
}

class JsonReportFormatter implements ReportFormattable
{
    public function format(Report $report)
    {
        return json_encode($report->contents());
    }
}
```

Теперь класс Report отвечает только за предоставление данных, а класс JsonReportFormatter занимается только их
форматированием в JSON. Таким образом, каждый класс имеет только одну причину для изменения, что соответствует принципу
SRP.
