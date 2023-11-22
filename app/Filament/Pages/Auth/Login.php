<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Login as FilamentLogin;

class Login extends FilamentLogin
{
    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->default('saade@laravel.local');
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->default('123123123');
    }
}
