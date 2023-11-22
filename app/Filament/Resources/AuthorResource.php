<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Forms\Components\ListField;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class AuthorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Authors';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Placeholder::make('the-problem')
                        ->content(
                            function () {
                                $file = urlencode(app_path('Forms/Components/ListEditAction.php'));

                                return new HtmlString(<<<HTML
                                    <ol class="space-y-2">
                                        <li>We're listing the author's books and we want to edit them.</li>
                                        <li>Inside the action form, we want to be able to edit the selected book chapters.</li>
                                        <li>The action is using <code>->record(fn (array \$arguments) => Book::find(\$arguments['id']))</code> to bind the book to the action.</li>
                                        <li>The chapters repeater inside the edit action need to point to the record bound to the action (explained above), but even if we pass the book as a \$record, the repeater will still point to the author (that is this form's record, not the action record)</li>
                                        <li>That's why you're seeing the <code>Call to undefined method App\Models\User::chapters()</code> error, because the repeater is trying to access the chapters relationship on the User model, not on the Books model.</li>
                                    </ol>
                                    <br>
                                    <a class="text-primary-600" href="phpstorm://open?file=$file&line=51">Click here to go to the EditAction file. (trust me)</a>
                                HTML);
                            }
                        ),

                    ListField::make('books')
                        ->hint('Click ⬇ to edit a book.')
                        ->editForm([
                            TextInput::make('title'),

                            /** This is the problem
                             * @see \App\Forms\Components\ListEditAction
                             */
                            Repeater::make('chapters')
                                ->relationship('chapters')
                                ->schema([
                                    TextInput::make('title'),
                                ]),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
