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

namespace App\Http\Requests\Admin\Metadata;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMetadataRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'metadata_key.*' => 'required|string|max:100',
            'metadata_value.*' => 'nullable|string|max:65535',
            'model' => 'required|string|max:100',
            'model_id' => 'required|integer',
        ];
    }

    public function getRedirectUrl()
    {
        return $this->redirector->getUrlGenerator()->previous();
    }
}
