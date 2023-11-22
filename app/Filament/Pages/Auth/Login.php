<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as FilamentLogin;

class Login extends FilamentLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()->schema([
                    Placeholder::make('Click the button below'),
                ]),
            ),
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        $user = User::inRandomOrder()->first();

        Filament::auth()->attempt([
            'email' => $user->email,
            'password' => 'password',
        ]);

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
