<?php

namespace App\Forms\Components;

use App\Models\Book;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Actions\Contracts\HasRecord;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ListEditAction extends Action implements HasRecord
{
    use InteractsWithRecord;

    public static function getDefaultName(): ?string
    {
        return 'edit';
    }

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * This is the relevant part. This resolves to a Book model.
         *
         * - We're listing the author's books and we want to edit them.
         * - Inside the edit form, we want to be able to edit the book's chapters.
         * - We need to know which book we're editing. ($record resolved using $arguments['id'])
         * - The chapters repeater inside this action need to point to the record below,
         *   but even if we pass the book as a $record, the repeater will still point to the author (that is the form record, not the action record).
         *   That's why you're seeing the `Call to undefined method App\Models\User::chapters()` error, because the repeater is trying to access the chapters relationship on the author model.
         */
        $this->record(
            fn (ListField $component, array $arguments): Model => $component->getCachedExistingRecords()->firstWhere('id', $arguments['id'])
        );

        /** This is not relevant */
        $this->label('Edit');

        $this->form(
            fn (ListField $component): array => $component->getEditFormSchema()
        );

        $this->fillForm(
            function (ListField $component, array $arguments): array {

                $repeater = $component->getEditFormSchema()[1];
                $repeater->model($this->getRecord());
                $repeater->relationship('chapters');

                return $component->getCachedExistingRecords()->firstWhere('id', $arguments['id'])->attributesToArray();
            }
        );

        $this->action(function (array $data) {

            $this->getRecord()->update([
                'title' => $data['title'],
            ]);

            $this->getRecord()->chapters()->delete();

            foreach ($data['chapters'] as $chapter) {
                $this->getRecord()->chapters()->create([
                    'title' => $chapter['title'],
                ]);
            }

        });
    }
}
