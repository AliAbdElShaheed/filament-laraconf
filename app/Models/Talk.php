<?php

namespace App\Models;

use App\Enums\Status;
use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talk extends Model
{
    use HasFactory;

    // Scopes


    // Casts
    /*protected $casts = [
        'id' => 'integer',
        'speaker_id' => 'integer',
        'status' => Status::class,
        'length' => TalkLength::class,
    ]; // end casts*/
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'speaker_id' => 'integer',
            'status' => TalkStatus::class,
            'length' => TalkLength::class,
            'new-talk' => 'boolean',
        ];
    } // end casts


    // Relationships
    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    } // end speaker relationship

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    } // end conferences relationship


    // Functions
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->required()
                ->maxLength(255),
            Textarea::make('abstract')
                ->required()
                ->columnSpanFull(),
            Select::make('speaker_id')
                ->relationship('speaker', 'name')
                ->required(),
        ];
    } // end getFormSchema


    public function approve()
    {
        $this->status = TalkStatus::APPROVED;

        // email the speaker
        $this->save();
    } // end approve


    public function reject()
    {
        $this->status = TalkStatus::REJECTED;

        // email the speaker
        $this->save();
    } // end reject

} // end Talk Model
