<?php

namespace App\Models;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
            Textarea::make('bio')
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


} // end Speaker Model
