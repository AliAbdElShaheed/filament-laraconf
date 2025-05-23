<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConferenceResource\Pages;
use App\Filament\Resources\ConferenceResource\RelationManagers;
use App\Models\Conference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConferenceResource extends Resource
{
    protected static ?string $model = Conference::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Conference')
                    ->helperText('The name of the conference')
                    ->hint('The name of the conference')
                    ->hintIcon('heroicon-o-home')
                    ->hintColor('info')
                    //->hintAction('https://example.com')
                    ->placeholder('Enter the name of the conference')
                    ->required()
                    ->maxLength(100)
                    ->rules(['required', 'string', 'max:100']),


                /*Forms\Components\TextInput::make('website')
                    ->label('Website')
                    ->url()
                    //->prefix('https://')
                    ->prefixIcon('heroicon-o-globe-alt')
                    ->suffix('. com')
                    ->default('www.example.com')
                    ->maxLength(255)
                    //->columnSpan(4)
                    ->rules(['nullable', 'url', 'max:255']),*/


                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->hint('A brief description of the conference')
                    ->hintIcon('heroicon-o-information-circle')
                    /*->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                        'h1',
                        'h2',
                        'h3',
                        'h4',
                        'h5',
                        'h6',
                    ])*/
                    ->toolbarButtons(['bold', 'italic', 'underline', 'link', 'bulletList', 'numberList'])
                    //->columnSpan(2)
                    ->required(),

                /*Forms\Components\MarkdownEditor::make('description2')
                    ->label('Description')
                    ->hint('A brief description of the conference')
                    ->hintIcon('heroicon-o-information-circle')
                    //->toolbarButtons(['bold', 'italic', 'underline', 'link', 'bulletList', 'numberList'])
                    ->columnSpan(2)
                    ->maxLength(255),*/


                Forms\Components\DatePicker::make('start_date')
                    ->native(false)
                    ->required(),


                Forms\Components\DateTimePicker::make('end_date')
                    ->required(),

                /*Forms\Components\Checkbox::make('status')
                    ->label('Status')
                    ->hint('Is the conference active?')
                    ->hintIcon('heroicon-o-information-circle')
                    ->default(true)
                    ->required(),*/

                /*Forms\Components\Toggle::make('is_virtual')
                                    ->label('Is Virtual')
                                    ->default(false),*/

                Forms\Components\Toggle::make('is_published')
                    ->label('Is Published')
                    ->default(false),

                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('region')
                    ->required()
                    ->maxLength(255),


                Forms\Components\Select::make('venue_id')
                    ->relationship('venue', 'name'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region')
                    ->searchable(),
                Tables\Columns\TextColumn::make('venue.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListConferences::route('/'),
            'create' => Pages\CreateConference::route('/create'),
            'edit' => Pages\EditConference::route('/{record}/edit'),
        ];
    }
}
