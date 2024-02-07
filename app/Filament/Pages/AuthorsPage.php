<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;

class AuthorsPage extends Page implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $view = 'filament.pages.authors-page';

    #[Computed]
    public function users(): Collection
    {
        return User::all();
    }

    public function editAction(): EditAction
    {
        return EditAction::make('edit')
            ->record(fn (array $arguments) => User::findOrFail($arguments['id']))
            ->form([
                TextInput::make('title'),
                Repeater::make('books')
                    ->relationship('books') // Repeater must know $record in order to work
                    ->schema([
                        TextInput::make('title'),
                    ]),
            ])
            ->action(function (array $data, User $record) {
                $record->update($data);
            });
    }
}
