<?php

namespace App\Forms\Components;

use App\Models\Book;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Illuminate\Database\Eloquent\Collection;

class ListField extends Field
{
    protected string $view = 'forms.components.list-field';

    protected array $editFormSchema = [];

    /** Everything here is not relevant (i guess) */
    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (Field $component, ?array $state) {
            if (! $state) {
                $component->state([]);
            }
        });

        $this->default([]);

        $this->registerActions([
            fn (Field $component): Action => $component->getEditAction(),
        ]);
    }

    public function getEditAction(): Action
    {
        return ListEditAction::make();
    }

    public function getCachedExistingRecords(): Collection
    {
        return Book::query()
            ->whereBelongsTo($this->getRecord(), 'author')
            ->get();
    }

    public function editForm(array $schema): static
    {
        $this->editFormSchema = $schema;

        return $this;
    }

    public function getEditFormSchema(): array
    {
        return $this->editFormSchema;
    }
}
