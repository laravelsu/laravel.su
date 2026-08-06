---
title: "Создаём Archivable"
description: "Собираем методы архивации в одном трейте."
---

Создадим `app/Models/Concerns/Archivable.php` и перенесём туда методы архивации:

```php
<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait Archivable
{
    public function archive(): void
    {
        $this->archived_at = now();
        $this->save();
    }

    public function unarchive(): void
    {
        $this->archived_at = null;
        $this->save();
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    /** @param Builder<static> $query */
    #[Scope]
    protected function archived(Builder $query): void
    {
        $query->whereNotNull('archived_at');
    }
}
```

Теперь `Archivable` позволяет отправить запись в архив, вернуть её, проверить состояние и выбрать архивные записи. Эти
методы больше не нужно повторять в каждой модели.

Подключим `Archivable` к двум моделям:

```php
use App\Models\Concerns\Archivable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Archivable;
}

class Comment extends Model
{
    use Archivable;
}
```

Атрибут `#[Scope]` позволяет использовать защищённый метод `archived()` как `Post::archived()`. Вызывающий код получает
понятное имя и не повторяет условие запроса.
