---
title: "Собираем Archivable"
description: "Помещаем команды, проверку состояния и выборку архива в одно место."
---

Создадим `app/Models/Concerns/Archivable.php`. Concern будет отвечать на три вопроса: как изменить состояние записи, как
его проверить и как найти архивные записи.

```php
<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait Archivable
{
    public function archive(): void
    {
        // Повторный вызов не должен менять время первой архивации.
        if ($this->isArchived()) {
            return;
        }

        $this->archived_at = now();
        $this->save();
    }

    public function unarchive(): void
    {
        if (! $this->isArchived()) {
            return;
        }

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

Здесь важен не сам трейт, а собранное в нём правило. `archive()` сохраняет дату только один раз, `unarchive()` возвращает
запись в обычное состояние, `isArchived()` скрывает способ проверки, а `archived()` не даёт запросам по всему приложению
повторять `whereNotNull('archived_at')`.

Атрибут `#[Scope]` отмечает защищённый метод как локальное условие запроса Eloquent. Благодаря этому снаружи доступен
короткий вызов `Post::archived()`, хотя сам метод не становится частью открытого интерфейса объекта.

Теперь модели только заявляют, что поддерживают архивацию:

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

Помимо возможностей Eloquent, Concern требует от модели столбец `archived_at`. Не нужно прятать это условие за сложной
автоматикой. Название столбца видно в коде, а его назначение одинаково для каждой модели с `use Archivable`.

Обратите внимание и на события Eloquent. Вызов `save()` оставлен обычным намеренно: при архивации сработают стандартные
события сохранения модели. Concern не должен неожиданно обходить наблюдателей или менять привычное поведение Eloquent.
