<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms;



class Conference extends Model
{
    use HasFactory;


    // Scopes


    // scopes

    // cast attributes to specific types
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'region' => Region::class,
            'venue_id' => 'integer',
        ];
    }


    // relationships
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    } // end venue relationship

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    } // end speakers relationship

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    } // end talks relationship


    // functions
    public static function getFormSchema(): array
    {
        return [
            Section::make('Conference Details')
                ->description('Fill in the details of the conference')
                ->icon('heroicon-o-information-circle')
                //->aside()
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                    'lg' => 3,
                ])
                ->collapsible()
                ->schema([
                    TextInput::make('name')
                        ->label('Conference')
                        ->columnSpanFull()
                        //->helperText('The name of the conference')
                        //->hint('The name of the conference')
                        //->hintIcon('heroicon-o-home', tooltip: 'This is the name of the conference')
                        //->hintColor('info')
                        //->hintAction('https://example.com')
                        ->placeholder('Enter the name of the conference')
                        ->required()
                        ->maxLength(100)
                        ->rules(['required', 'string', 'max:100']),


                    /*TextInput::make('website')
                        ->label('Website')
                        ->url()
                        //->prefix('https://')
                        ->prefixIcon('heroicon-o-globe-alt')
                        ->suffix('. com')
                        ->default('www.example.com')
                        ->maxLength(255)
                        //->columnSpan(4)
                        ->rules(['nullable', 'url', 'max:255']),*/


                    RichEditor::make('description')
                        //->columnSpan(2)
                        ->columnSpanFull()
                        ->label('Description')
                        //->hint('A brief description of the conference')
                        //->hintIcon('heroicon-o-information-circle')
                        //->autofocus()
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
                        ->required(),

                    /*MarkdownEditor::make('description2')
                        ->label('Description')
                        ->hint('A brief description of the conference')
                        ->hintIcon('heroicon-o-information-circle')
                        //->toolbarButtons(['bold', 'italic', 'underline', 'link', 'bulletList', 'numberList'])
                        ->columnSpan(2)
                        ->maxLength(255),*/


                    DateTimePicker::make('start_date')
                        ->native(false)
                        ->required(),


                    DateTimePicker::make('end_date')
                        ->native(false)
                        ->required(),

                ]),

            Section::make('Conference Settings')
                ->description('Configure the settings for the conference')
                ->icon('heroicon-o-cog')
                ->collapsed()
                ->schema([

                    /*Checkbox::make('status')
                ->label('Status')
                ->hint('Is the conference active?')
                ->hintIcon('heroicon-o-information-circle')
                ->default(true)
                ->required(),*/

                    /*Toggle::make('is_virtual')
                                        ->label('Is Virtual')
                                        ->default(false),*/


                    TextInput::make('status')
                        ->required()
                        ->maxLength(255),

                    Toggle::make('is_published')
                        ->label('Is Published')
                        ->default(false),


                    Fieldset::make('Conference Speakers')
                        ->columns(2)
                        ->schema([
                            CheckboxList::make('speakers')
                                ->relationship('speakers', 'name')
                                ->options(
                                    Speaker::all()->pluck('name', 'id')
                                )->columns(4)
                                ->searchable()
                                ->columnSpanFull()
                        ]),
                ]),

            Section::make('Location')
                ->description('Set the location of the conference')
                ->icon('heroicon-o-map-pin')
                ->columns(2)
                ->collapsed()
                ->schema([
                    Select::make('region')
                        ->live()
                        ->enum(Region::class)
                        ->options(Region::class)
                        ->required(),


                    Select::make('venue_id')
                        ->searchable()
                        ->preload()
                        ->editOptionForm(Venue::getFormSchema())
                        ->createOptionForm(Venue::getFormSchema())
                        ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                            return $query->where('region', $get('region'));
                        }),
                ]),


        ];

    } // end getFormSchema


} // end Conference model
