<?php

namespace Ferdiunal\NovaEditableField;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Route;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->booted(
            function () {
                $this->routes();
                Field::macro(
                    'editable',

                    /**
                     * Make the field editable on index page
                     * 
                     * @param NovaRequest $request
                     * @param string $popoverWidth
                     */
                    function (NovaRequest $request, string $popoverWidth = '270px') {
                        /** @var Field $this */
                        if ($request->isResourceIndexRequest()) {
                            $this->withMeta([
                                'original_field' => $this->component,
                                'withLabel' => false,
                                'popoverWidth' => $popoverWidth,
                            ]);
                            $this->component = 'nova-editable-field';
                        }

                        return $this;
                    }
                );
            }
        );

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-editable-field', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-editable-field', __DIR__.'/../dist/css/field.css');
        });
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/ferdiunal/nova-editable-field/')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
