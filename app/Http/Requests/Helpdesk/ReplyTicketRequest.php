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

namespace App\Http\Requests\Helpdesk;

use App\Rules\NoScriptOrPhpTags;
use Illuminate\Foundation\Http\FormRequest;

class ReplyTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxFileSize = setting('helpdesk_attachments_max_size', 10);
        $maxFileSize = $maxFileSize * 1024;
        $allowedMimes = setting('helpdesk_attachments_allowed_types', 'jpg,jpeg,png,gif,pdf,doc,docx,txt,zip');

        return [
            'content' => 'required|min:5|string|max:10000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => [
                'file',
                'mimes:'.$allowedMimes,
                'max:'.$maxFileSize,
                new NoScriptOrPhpTags,
            ],
        ];
    }
}
