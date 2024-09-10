<?php

namespace Ferdiunal\NovaEditableField;

use Laravel\Nova\Fields\Field;

class EditableMacro
{
    public function __construct(protected Field $field, callable $fillCallback)
    {
        $this->field->component = 'nova-editable-field';

        $this->field->fillCallback = $fillCallback;

        return $this->field;
    }
}
