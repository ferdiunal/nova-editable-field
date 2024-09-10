<?php

namespace Ferdiunal\NovaEditableField\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class ConfirmResourceController extends Controller
{
    /**
     * List the fields for a resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(string $resourceName, string $resourceId, string $attribute, NovaRequest $request)
    {
        $resource = $request->findResourceOrFail($resourceId);

        $resource->authorizeToUpdate($request);

        $model = $resource->model();

        [$model, $callbacks] = $resource::fillForUpdate($request, $model);

        $model->save();

        $model->refresh();

        return response()->noContent();
    }

    protected function makeFakeResource(string $fieldName, $fieldValue)
    {
        $fakeResource = new \Laravel\Nova\Support\Fluent;
        $fakeResource->{$fieldName} = $fieldValue;

        return $fakeResource;
    }
}
