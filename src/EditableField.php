<?php

namespace Ferdiunal\NovaEditableField;

use Laravel\Nova\Fields\Field;

class EditableField extends Field
{
    /**
     * The callback that should be used to resolve the pivot fields.
     *
     * @var callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Model):array<int, \Laravel\Nova\Fields\Field>
     */
    public $fieldsCallback;

    /**
     * @var callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Model):bool
     */
    public $fillCallback;

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->fieldsCallback = fn () => [];

        $this->fillCallback = fn () => false;
    }

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-editable-field';

    /**
     * Specify the callback to be executed to retrieve the pivot fields.
     *
     * @param  callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Model):array<int, \Laravel\Nova\Fields\Field>  $callback
     * @return $this
     */
    public function fields($callback)
    {
        $this->fieldsCallback = $callback;

        return $this;
    }

    /**
     * @param  callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Model):bool  $callback
     * @return $this
     */
    public function fillCallback($callback)
    {
        $this->fillCallback = $callback;

        return $this;
    }
}
