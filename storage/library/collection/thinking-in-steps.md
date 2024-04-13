---
title: "Цепочка методов для обработки данных"
description: "Думай об изменениях. Как это будет работать завтра?"
---

В программировании часто возникают задачи, требующие доработки или внесения изменения в уже существующий код.
Давайте рассмотрим, как мы можем использовать методы коллекций и сравним их с использованием методов обработки массивов.

Простой пример: у нас есть набор пользователей и мы хотим отфильтровать только активных пользователей:

```php
// Плохо ❌
$activeUsers = array_filter($activeUsers, function (User $user) {
    return $user->isActive();
});
```

Теперь нам нужно добавить ещё один шаг: убрать администраторов из списка активных пользователей:

```php
// Плохо ❌
$activeUsers = array_filter($activeUsers, function (User $user) {
    return $user->isActive();
});
 
$activeRegularUsers = array_filter($activeUsers, function (User $user) {
    return !$user->isAdmin();
});
```

При использовании коллекции каждый вызов метода, это отдельный шаг цепочки, который можно легко прочитать и понять:

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
// Плохо ❌
$activeUsers = array_filter($activeUsers, function (User $user) {
    return $user->isActive();
});
 
$activeRegularUsers = array_filter($activeUsers, function (User $user) {
    return !$user->isAdmin();
});

usort($activeRegularUsers, function (User $a, User $b) {
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

