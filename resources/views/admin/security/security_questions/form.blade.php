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
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        @include('admin/shared/input', [
            'name' => 'question',
            'label' => __($translatePrefix . '.fields.question'),
            'value' => old('question', $item?->question),
            'required' => true,
            'translatable' => true,
        ])
    </div>

    <div>
        @include('admin/shared/input', [
            'name' => 'sort_order',
            'label' => __($translatePrefix . '.fields.sort_order'),
            'value' => old('sort_order', $item?->sort_order ?? 0),
            'type' => 'number',
            'min' => 0,
        ])
    </div>

    <div>
        @include('admin/shared/checkbox', [
            'name' => 'is_active',
            'label' => __($translatePrefix . '.fields.is_active'),
            'checked' => old('is_active', $item?->is_active ?? true),
        ])
    </div>
</div>
