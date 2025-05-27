<?php

namespace App\Models;

use App\Enums\TalkStatus;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALIFICATIONS = [
        'business-leader' => 'Business Leader',
        'developer' => 'Developer',
        'designer' => 'Designer',
        'entrepreneur' => 'Entrepreneur',
        'investor' => 'Investor',
        'marketer' => 'Marketer',
        'product-manager' => 'Product Manager',
        'researcher' => 'Researcher',
    ];


    // Scopes


    // casts
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'qualifications' => 'array',
        ];
    } // end casts


    // relationships
    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    } // end conferences relationship

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    } // end talks relationship




    // functions
    // Form schema for the Speaker resource
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            FileUpload::make('avatar')
                //->image()
                ->avatar()
                ->imageEditor()
                ->directory('speakers/avatars')
                ->maxSize(1024), // 1MB
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            RichEditor::make('bio')
                ->columnSpanFull(),
            TextInput::make('twitter_handle')
                ->maxLength(255),
            CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->options([
                    'business-leader' => 'Business Leader',
                    'developer' => 'Developer',
                    'designer' => 'Designer',
                    'entrepreneur' => 'Entrepreneur',
                    'investor' => 'Investor',
                    'marketer' => 'Marketer',
                    'product-manager' => 'Product Manager',
                    'researcher' => 'Researcher',
                ])->descriptions(
                    [
                        'business-leader' => 'A person who leads a business or organization.',
                        'developer' => 'A person who develops software or applications.',
                        'designer' => 'A person who designs products or services.',
                        'entrepreneur' => 'A person who starts and runs a business.',
                        'investor' => 'A person who invests money in businesses or projects.',
                        'marketer' => 'A person who promotes products or services.',
                        'product-manager' => 'A person who manages the development of a product.',
                        'researcher' => 'A person who conducts research in a specific field.',
                    ]
                )
                ->columns(4)
        ];
    } // end getFormSchema


    // Infolist schema for the Speaker resource
    public static function getInfolistSchema(): array
    {
        return [
            Section::make('Personal Information')
                ->collapsible()
                ->columns(3)
                ->schema([
                    ImageEntry::make('avatar')
                        ->circular(),
                    Group::make()
                        ->columns(2)
                        ->columnSpan(2)
                        ->schema([
                            TextEntry::make('name')
                                ->label('Name')
                                ->weight('bold')
                                ->size('lg'),
                            TextEntry::make('email')
                                ->label('Email')
                                ->weight('medium'),
                            TextEntry::make('twitter_handle')
                                ->label('Twitter')
                                ->getStateUsing(fn($record) => $record->twitter_handle ? '@' . $record->twitter_handle : null)
                                ->weight('medium')
                                ->url(fn($record) => 'https://twitter.com/' . $record->twitter_handle),
                            TextEntry::make('has_spoken')
                                ->label('Has Spoken')
                                ->getStateUsing(fn($record) => $record->talks()->where('status', TalkStatus::APPROVED)->count() > 0 ? 'Previously Spoken' : 'Has Not Spoken')
                                ->badge()
                                ->color(fn($record) => $record->talks()->where('status', TalkStatus::APPROVED)->count() > 0 ? 'success' : 'danger'),

                        ]),

                ]),
            Section::make('Other Information')
                ->collapsed()
                ->columns(3)
                ->schema([
                    TextEntry::make('bio')
                        ->label('Bio')
                        ->extraAttributes(['class' => 'prose dark:prose-invert'])
                        ->HTML()
                        ->columnSpanFull(),
                    TextEntry::make('qualifications')
                        ->label('Qualifications')
                        /*->getStateUsing(fn($record) => implode(', ', $record->qualifications))
                        ->weight('medium')*/
                        ->listWithLineBreaks()
                        ->bulleted(),

                ]),
        ];
    } // end getInfolistSchema


} // end Speaker Model
