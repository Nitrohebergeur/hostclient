<?php

/*
 * This file is part of the Hostclient project.
 * It is the property of the Hostclient association.
 *
 * Personal and non-commercial use of this source code is permitted.
 * However, any use in a project that generates profit (directly or indirectly),
 * or any reuse for commercial purposes, requires prior authorization from Hostclient.
 *
 * To request permission or for more information, please contact our support:
 * https://Hostclient.com/client/support
 *
 * Learn more about Hostclient License at:
 * https://Hostclient.com/eula
 *
 * Year: 2025
 */

namespace App\Http\Controllers\Admin\Core;

use App\Http\Requests\Admin\Metadata\UpdateMetadataRequest;

class MetadataController
{
    public function update(UpdateMetadataRequest $request)
    {
        staff_aborts_permission('admin.manage_metadata');
        $model = $request->model;
        $modelId = $request->model_id;
        $metadata = $request->validated();
        if (class_exists($model)) {
            $model = $model::find($modelId);
            if ($model && method_exists($model, 'syncMetadata')) {
                $model->syncMetadata(array_combine($metadata['metadata_key'] ?? [], $metadata['metadata_value'] ?? []));

                return back()->with('success', __('admin.metadata.updated'));
            }

            return back()->with('error', 'Model not found');
        }
    }
}
