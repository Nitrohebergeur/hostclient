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

namespace App\DTO\Core\Extensions;

class ExtensionInstallDTO
{
    public bool $isInstalled;

    public string $name;

    public string $version;

    public bool $isUpdated;

    public bool $isActivated;

    public function __construct(bool $isInstalled, string $name, string $version, bool $isUpdated, bool $isActivated)
    {
        $this->isInstalled = $isInstalled;
        $this->name = $name;
        $this->version = $version;
        $this->isUpdated = $isUpdated;
        $this->isActivated = $isActivated;
    }
}
