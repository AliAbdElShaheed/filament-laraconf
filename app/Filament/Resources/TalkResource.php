<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PHPUnit\Util\Filter;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(function (Talk $record) {
                        return $record->abstract ? substr($record->abstract, 0, 40) . '...' : 'No abstract provided';
                    }),


                /*TextInputColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->rules([
                        'required',
                        'max:255',
                    ]),*/
                TextColumn::make('abstract')
                    ->wrap()
                    //->limit(50),
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('speaker.name')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('speaker.avatar')
                    ->label('Speaker Avatar')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(function ($record) {
                        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->speaker->name);
                    }),
                /*IconColumn::make('new-talk')
                    ->boolean()
                    ->label('New Talk')
                    ->toggleable(isToggledHiddenByDefault: true),*/

                ToggleColumn::make('new-talk')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->color(function ($state) {
                        return $state->getColor();
                    }),

                IconColumn::make('length')
                    ->label('Length')
                    ->sortable()
                    ->icon(function ($state) {
                        return $state->getIcon();
                    })
                    ->color(function ($state) {
                        return $state->getColor();
                    })
                    ->tooltip(function ($state) {
                        return $state->getLabel();
                    }),


                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('new-talk'),
                SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->placeholder('All Speakers')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('has_avatar')
                    ->label('Only Speakers with Avatars')
                    ->toggle()
                    /*->query(function (Builder $query, $state) {
                    if ($state) {
                        return $query->whereHas('speaker', function (Builder $query) {
                            $query->whereNotNull('avatar');
                        });
                    }
                    return $query->whereDoesntHave('speaker', function (Builder $query) {
                        $query->whereNotNull('avatar');
                    });
                }),*/
                    ->query(function ($query) {
                        return $query->whereHas('speaker', function (Builder $query) {
                            $query->whereNotNull('avatar');
                        });
                    }),

            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
