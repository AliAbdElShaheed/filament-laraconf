<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Forms;

class Venue extends Model
{
    use HasFactory;

    //casts
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'region' => Region::class,
        ];
    }

    // relationships
    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }


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

        ];
    } // end getFormSchema

} // end Venue Model
