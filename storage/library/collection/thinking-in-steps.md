---
title: "Думай об изменениях"
description: "Как это будет работать завтра?"
---

В программировании часто возникают задачи, требующие доработки или внесения изменения в уже существующий код.
Давайте рассмотрим, как мы можем использовать методы коллекций и сравним их с использованием массивов.

Давайте снова вернёмся к примеру с фильтрацией активных пользователей:

```php
// Плохо ❌
$activeUsers = [];

foreach ($users as $user) {
    if ($user->isActive()) {
        $activeUsers[] = $user;
    }
}
```

Теперь нам нужно добавить ещё один шаг: убрать администраторов из списка активных пользователей и мы не хотим
использовать массивы:

```php
// Лучше, но не идеально ❌
$activeUsers = array_filter($activeUsers, function (User $user) {
    return $user->isActive();
});
 
$activeUsers = array_filter($activeUsers, function (User $user) {
    return !$user->isAdmin();
});
```

Нам пришлось объявить переменную `$activeUsers` дважды, но это не самое худшее. 

При использовании коллекции каждый вызов метода, это отдельный шаг, который можно легко прочитать и понять:

```php
// Хорошо ✅
$activeUsers = $users
    ->filter(function (User $user) {
        return $user->isActive();
    })
    ->filter(function (User $user) {
        return !$user->isAdmin();
    });
```


Теперь введём ещё одно условие, нам нужно отсортировать пользователей по дате регистрации:

```php
// Лучше, но не идеально ❌
$activeUsers = array_filter($activeUsers, function (User $user) {
    return $user->isActive();
});
 
$activeUsers = array_filter($activeUsers, function (User $user) {
    return !$user->isAdmin();
});

usort($activeUsers, function (User $a, User $b) {
    return $a->created_at <=> $b->created_at;
});
```

Используя методы коллекций, мы можем добавить сортировку в цепочку методов:

```php
// Хорошо ✅
$activeUsers = $users
    ->filter(function (User $user) {
        return $user->isActive();
    })
    ->filter(function (User $user) {
        return !$user->isAdmin();
    })
    ->sortBy('created_at');
```


Использование методов коллекций позволяет нам более явно выразить наши намерения при обработке данных, делая код более
структурированным и легким для понимания.

