---
title: "Рекомендуемые соглашения"
description: "Помогут вам оставаться последовательными при организации вашего приложения"
---

**Начните с глагола**

Назовите свои классы действий как небольшие явные предложения, начинающиеся с глагола. Например, действие, которое
«отправляет электронное письмо пользователю для сброса пароля», можно назвать `SendResetPasswordEmail`. Такой подход
делает названия классов самодокументированными и легко понятными, что улучшает читабельность кода.

**Используйте папку `Actions`**

Создайте папку `app/Actions` и сгруппируйте свои действия внутри неё по темам. Это помогает поддерживать структуру
вашего кода организованной и логичной. Например:

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

В качестве альтернативы, если ваше приложение уже разделено на темы — или модули — вы можете создать папку `Actions` под
каждым из этих модулей. Например:

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

Такая организация помогает вам поддерживать порядок в коде и упрощает его навигацию.
