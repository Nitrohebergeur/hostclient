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

namespace App\Http\Requests\Provisioning;

use App\DTO\Store\UpgradeDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpgradeServiceRequest extends FormRequest
{
    public function authorize()
    {
        return $this->service->canUpgrade();
    }

    public function rules()
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'type' => ['required', Rule::in([UpgradeDTO::MODE_INVOICE, UpgradeDTO::MODE_NO_INVOICE])],
        ];
    }
}
