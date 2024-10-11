---
title: "Рекомендуемые соглашения"
description: "Помогут вам оставаться последовательными при организации вашего приложения"
---


Для упрощения поддержки и организации кода целесообразно придерживаться ряда рекомендаций при создании классов действий.

##### Начните с глагола

Названия классов действий должны представлять собой глаголы, отражающие выполняемую задачу. 

Например, если класс предназначен для отправки письма для сброса пароля, его следует назвать `SendResetPasswordEmail`.


##### Используйте директорию `Actions`

Создайте папку `app/Actions` и сгруппируйте свои действия внутри неё по модулям.
Это поможет поддерживать структуру вашего кода организованной и логичной. Например:

```php
app/
├── Actions/
│   ├── Authentication/
│   │   ├── LoginUser.php
│   │   ├── RegisterUser.php
│   │   ├── ResetUserPassword.php
│   │   └── SendResetPasswordEmail.php
│   ├── Leads/
│   │   ├── BulkRemoveLead.php
│   │   ├── CreateNewLead.php
│   │   ├── GetLeadDetails.php
│   │   ├── MarkLeadAsCustomer.php
│   │   ├── MarkLeadAsLost.php
│   │   ├── RemoveLead.php
│   │   ├── SearchLeadsForUser.php
│   │   └── UpdateLeadDetails.php
│   └── Settings/
│       ├── GetUserSettings.php
│       ├── UpdateUserAvatar.php
│       ├── UpdateUserDetails.php
│       ├── UpdateUserPassword.php
│       └── DeleteUserAccount.php
├── Models/
└── ...
```

Если ваше приложение уже разделено на модули - создайте директорию `Actions` в
каждом из них:

```php
app/
├── Authentication/
│   ├── Actions/
│   ├── Models/
│   └── ...
├── Leads/
│   ├── Actions/
│   ├── Models/
│   └── ...
└── Settings/
    ├── Actions/
    └── ...
```

Такая организация поможет вам поддерживать порядок в коде и упростит навигацию.
