<?php

namespace App\Casts;

use App\Services\ColorText;

enum PackageTypeEnum: string
{
    case FileManagement = 'file-management';
    case AuthAndPermission = 'auth-and-permission';
    case DatabaseAndEloquent = 'database-and-Eloquent';
    case DebuggingAndDevTools = 'debugging-and-dev-tools';
    case DevOps = 'dev-ops';
    //case Localization = 'localization';
    case API = 'api';
    //case SEO = 'seo';
    case Testing = 'testing';
    //case Payment = 'payment';
    case Security = 'security';
    //case Mail = 'mail';
    case ECommerce = 'e-commerce';
    case CMSAndAdminPanels = 'cms-and-admin-panels';
    case CodeArchitecture = 'code-architecture';
    case Notifications = 'notifications';
    case UIAndBladeComponents = 'ui-and-blade-components';
    case UtilitiesAndHelpers = 'utilities-and-helpers';

    public function text(): string
    {
        return match ($this) {
            self::FileManagement => 'Управление файлами',
            self::AuthAndPermission => 'Аутентификация и разрешения',
            self::DatabaseAndEloquent => 'Базы данных и Eloquent',
            self::DebuggingAndDevTools => 'Инструменты разработчика',
            self::DevOps => 'DevOps',
            //self::Localization         => 'Локализация',
            self::API => 'API',
            //self::SEO                  => 'Оптимизация для поисковых систем (SEO)',
            self::Testing => 'Тестирование',
            //self::Payment              => 'Платежи',
            self::Security => 'Безопасность',
            //self::Mail                 => 'Почта',
            self::ECommerce => 'Интернет-торговля',
            self::CMSAndAdminPanels => 'CMS и панели администратора',
            self::CodeArchitecture => 'Архитектура кода',
            self::Notifications => 'Уведомления',
            self::UIAndBladeComponents => 'UI и компоненты Blade',
            self::UtilitiesAndHelpers => 'Утилиты',

        };
    }

    public function colorBg(): string
    {
        return ColorText::Hex($this->text(), '21');//подобрать подходящее значение
    }

    public function colorText(): string
    {
        return ColorText::Hex($this->text());
    }

    public function icon(): string
    {
        return match ($this) {
            self::FileManagement => 'bs.file-earmark-fill',
            self::AuthAndPermission => 'bs.people-fill',
            self::DatabaseAndEloquent => 'bs.database-fill',//'bs.server'
            self::DebuggingAndDevTools => 'bs.bug-fill',
            self::DevOps => 'bs.display',
            //self::Localization         => 'bs.globe',
            self::API => 'bs.cloud-fill',
            //self::SEO                  => 'bs.search',
            self::Testing => 'bs.gear-wide',
            //self::Payment              => 'bs.credit-card-fill',
            self::Security => 'bs.shield-shaded',
            //self::Mail                 => 'bs.envelope-fill',
            self::ECommerce => 'bs.bag-fill',
            self::CMSAndAdminPanels => 'bs.window-stack',
            self::CodeArchitecture => 'bs.file-earmark-code-fill',
            self::Notifications => 'bs.bell-fill',
            self::UIAndBladeComponents => 'bs.file-earmark-richtext-fill',
            self::UtilitiesAndHelpers => 'bs.wrench-adjustable',

        };
    }
}