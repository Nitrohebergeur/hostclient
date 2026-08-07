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

<div class="grid md:grid-cols-2 gap-4 grid-cols-1">
    <div>
        @include('admin/shared/password', [
            'name' => 'client_id',
            'label' => __('admin.settings.store.gateways.fields.client_id'),
            'value' => env('REVOLUT_CLIENT_ID')
        ])
    </div>
    <div>
        @include('admin/shared/password', [
            'name' => 'client_secret',
            'label' => __('admin.settings.store.gateways.fields.client_secret'),
            'value' => env('REVOLUT_CLIENT_SECRET')
        ])
    </div>
    <div>
        @include('admin/shared/text', [
            'name' => 'counterparty_id',
            'label' => 'ID du bénéficiaire (counterparty_id)',
            'value' => env('REVOLUT_COUNTERPARTY_ID')
        ])
    </div>
    <div>
        @include('admin/shared/text', [
            'name' => 'counterparty_account_id',
            'label' => 'ID du compte bénéficiaire (counterparty_account_id)',
            'value' => env('REVOLUT_COUNTERPARTY_ACCOUNT_ID')
        ])
    </div>
    <dov>
        @include('admin/shared/select', ['name' => 'sandbox', 'label' => __('admin.settings.store.gateways.fields.sandbox'), 'value' => env('REVOLUT_SANDBOX', 'true') == 'sandbox' ? 'sandbox' : 'live', 'options' => ['sandbox' => __('global.enabled'), 'live' => __('global.disabled')]])
    </dov>
</div>
