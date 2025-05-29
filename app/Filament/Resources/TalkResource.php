<?php

namespace App\Filament\Resources;

use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreBulkAction;
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
use Illuminate\Support\Collection;
use PHPUnit\Util\Filter;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Second Group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
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
                EditAction::make()
                    ->SlideOver(),
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(function ($record) {
                            return $record->status === (TalkStatus::SUBMITTED);
                        })
                        //->requiresConfirmation()
                        ->action(function (Talk $record) {
                            $record->approve();
                        })->after(function () {
                            Notification::make()->success()->title('Talk Approved')
                                ->duration(3000)
                                ->body('The talk has been approved and the speaker has been notified.')
                                ->send();
                        }),
                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->visible(function ($record) {
                            return $record->status === (TalkStatus::SUBMITTED);
                        })
                        ->requiresConfirmation()
                        ->action(function (Talk $record) {
                            $record->reject();
                        })->after(function () {
                            Notification::make()->danger()->title('Talk Rejected')
                                ->duration(3000)
                                ->body('The talk has been rejected and the speaker has been notified.')
                                ->send();
                        }),
                ]),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    BulkAction::make('approve')
                        ->label('Approve Talks')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each->approve();
                        })->after(function () {
                            Notification::make()->success()->title('Talks Approved')
                                ->duration(3000)
                                ->body('The selected talks have been approved and the speakers have been notified.')
                                ->send();
                        }),

                    BulkAction::make('reject')
                        ->label('Reject Talks')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->reject();
                        })->after(function () {
                            Notification::make()->danger()->title('Talks Rejected')
                                ->duration(3000)
                                ->body('The selected talks have been rejected and the speakers have been notified.')
                                ->send();
                        }),
                ]),
            ])
            ->HeaderActions([
                Action::make('export')
                    ->label('Export Talks')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->tooltip('Export all talks to CSV')
                    ->action(function ($livewire) {
                        $livewire->dispatch('exportTalks');
                    }),

                Action::make('export-filtered')
                    ->label('Export Filtered Talks')
                    ->icon('heroicon-o-funnel')
                    ->color('primary')
                    ->tooltip('Export filtered talks to CSV')
                    ->action(function ($livewire) {
                        $livewire->getFilteredTableQuery()
                            ->get()
                            ->each(function ($record) {
                                // Logic to export each record
                                // This could be a custom export function or a direct download
                            });
                    }),

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
            //'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
