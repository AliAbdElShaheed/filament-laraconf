<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Forms;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Venue extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;


    // scopes



    //casts
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'region' => Region::class,
        ];
    } // end casts


    // relationships
    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    } // end conferences relationship




    //functions
    // Form schema for the Venue resource
    public static function getFormSchema(): array
    {
        return [

            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('city')
                ->required()
                ->maxLength(255),
            TextInput::make('country')
                ->required()
                ->maxLength(255),
            TextInput::make('postal_code')
                ->required()
                ->maxLength(255),

            Select::make('region')
                ->enum(Region::class)
                ->options(Region::class),

            SpatieMediaLibraryFileUpload::make('images')
                ->collection('venue-images')
                ->label('Venue Image')
                ->disk('media') // Use the 'media' disk defined in config/filesystems.php)
                ->image()
                ->multiple()
                ->maxSize(1024 * 2) // 2MB
                ->acceptedFileTypes(['image/*'])
                ->directory('venues/images'),
        ];
    } // end getFormSchema

} // end Venue Model
