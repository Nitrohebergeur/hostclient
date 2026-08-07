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

@extends('install.layout')
@section('title', __('install.settings.title'))
@section('content')
    <form method="POST" action="{{ route('install.settings') }}">
        @csrf
        @include('shared.alerts')
        <div class="mt-2">
            @include('shared.input', ['name' => 'app_name', 'value' => env('APP_NAME'), 'label' => __('install.settings.hosting_name')])
        </div>
        <div class="mt-2">
            @include('shared.input', ['name' => 'client_id', 'value' =>  old('client_id'), 'label' => __('install.settings.client_id')])
        </div>
        <div class="mt-2">
            @include('shared.input', ['name' => 'client_secret', 'value' => old('client_id'), 'label' => __('install.settings.client_secret')])
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">{!! __('install.settings.infolicense', ['link' => 'https://Hostclient.com/']) !!}</p>

        <div class="mt-2">
            @include('shared.search-select-multiple', ['name' => 'locales[]', 'value' => ['en_GB'], 'label' => __('install.settings.locales'), 'options' => $locales])
        </div>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ __('install.settings.eula') }}
        </p>
        <button type="submit" class="mt-4 btn btn-primary w-full" {{ !$isMigrated ? 'disabled' : '' }}>
            {{ __('install.settings.connect') }}
        </button>
    </form>
    @endsection
