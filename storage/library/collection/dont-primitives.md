---
title: "Не используйте примитивы"
description: "Что-то написать"
---

Помимо явной цепочки вызовов, мы можем передавать промежуточные значения для дальнейшей обработки.
Например, в одном методе мы можем сформировать коллекцию активных пользователей, а в другом продолжить высокоуровневую обработку:

```php
function getActiveUsers(): array
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


$activeUsers = getActiveUsers();

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

```php
// Хорошо ✅
function getActiveUsers(): Collection
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



$activeUsers = getActiveUsers();

// Вычисление среднего возраста активных пользователей
$averageAge = $activeUsers->avg('age');

// Формирование списка электронных адресов активных пользователей
$emailList = $activeUsers->pluck('email');
```
