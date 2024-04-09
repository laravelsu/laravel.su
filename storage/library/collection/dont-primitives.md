---
title: "Не возвращайте примитив"
description: "Не используйте примитивы там где может потребоваться продолжение цепочки"
---

Помимо явной цепочки вызовов, мы можем передавать промежуточные значения для дальнейшей обработки. Это может привести к
избыточности кода и потере читаемости. Вместо этого, использование коллекций позволяет нам проводить цепочку операций
непосредственно с объектами данных, что делает код более компактным, читаемым и эффективным.

Снова проиллюстрируем на примере:

```php
// Плохо ❌

function activeUsers(): array
{
    // ...
    
    $activeUsers = array_filter($activeUsers, function (User $user) {
        return $user->isActive();
    });
     
    $activeUsers = array_filter($activeUsers, function (User $user) {
        return !$user->isAdmin();
    });
    
    usort($activeUsers, function (User $a, User $b) {
        return $a->created_at <=> $b->created_at;
    });
    
    retrun $activeUsers;
}
```

Этот метод возвращает массив, что приводит к провокации при дальнейшей обработке.


```php
$activeUsers = activeUsers();

// Вычисление среднего возраста активных пользователей
$totalAge = 0;
foreach ($activeUsers as $user) {
    $totalAge += $user->age;
}
$averageAge = $totalAge / count($activeUsers);

// Формирование списка электронных адресов активных пользователей
$emailList = [];
foreach ($activeUsers as $user) {
    $emailList[] = $user->email;
}
```

Но если метод возвращал коллекцию, то он не подстрекал на обработку массива: 

```php
// Хорошо ✅
function activeUsers(): Collection
{
    // ...

    return $users
        ->filter(function (User $user) {
            return $user->isActive();
        })
        ->filter(function (User $user) {
            return !$user->isAdmin();
        })
        ->sortBy('created_at');
}
```

И продолжение цепочки было бы более простым и читаемым:

```php
// Хорошо ✅

$activeUsers = activeUsers();

// Вычисление среднего возраста активных пользователей
$averageAge = $activeUsers->avg('age');

// Формирование списка электронных адресов активных пользователей
$emailList = $activeUsers->pluck('email');
```
