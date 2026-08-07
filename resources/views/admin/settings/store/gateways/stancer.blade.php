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
        @include('admin/shared/password', ['name' => 'public_key', 'label' => __('admin.settings.store.gateways.fields.client_id'), 'value' => env('STANCER_PUBLIC_KEY')])
    </div>
    <div>
        @include('admin/shared/password', ['name' => 'private_key', 'label' => __('admin.settings.store.gateways.fields.client_secret'), 'value' => env('STANCER_SECRET_KEY')])
    </div>
</div>
