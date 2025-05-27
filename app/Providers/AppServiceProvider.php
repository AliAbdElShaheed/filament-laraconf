<?php

namespace App\Providers;


use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();


        EditAction::configureUsing(function ($action) {
            return $action->slideOver()/*->form(fn (Model $record) => $record::getFormSchema())
                ->mutateFormDataUsing(fn (array $data, Model $record) => $record->fill($data))
                ->successNotificationTitle('Record updated successfully')*/ ;
        });
    }
}
