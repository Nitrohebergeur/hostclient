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

@extends('layouts/client')
@section('title', __('client.invoices.index'))
@section('scripts')
    <script src="{{ Vite::asset('resources/themes/default/js/filter.js') }}"></script>
@endsection
@section('content')
    <div class="{{ theme_metadata('layout_classes', 'max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto') }}">
        @include('shared/alerts')
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
            @include('front/billing/invoices/card', ['invoices' => $invoices, 'filter' => $filter, 'filters' => $filters])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
