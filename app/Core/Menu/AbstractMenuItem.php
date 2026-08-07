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

namespace App\Core\Menu;

abstract class AbstractMenuItem
{
    public string $uuid;

    public string $route;

    public string $icon;

    public string $translation;

    public ?string $permission = null;

    public array $children;

    public int $position;

    public function __construct(string $uuid, string $route, string $icon, string $translation, int $position, ?string $permission = null, array $children = [])
    {
        $this->uuid = $uuid;
        $this->route = $route;
        $this->icon = $icon;
        $this->permission = $permission;
        $this->children = $children;
        $this->translation = $translation;
        $this->position = $position;
    }
}
