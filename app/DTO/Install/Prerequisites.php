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

namespace App\DTO\Install;

class Prerequisites
{
    public array $errors = [];

    private string $phpVersion;

    private array $requiredExtensions = [
        'openssl',
        'pdo',
        'mbstring',
        'tokenizer',
        'xml',
        'ctype',
        'json',
        'bcmath',
        'curl',
        'fileinfo',
        'gd',
        'zip',
    ];

    public const REQUIRED_PHP_VERSION = '8.1.0';

    public function __construct()
    {
        $this->phpVersion = phpversion();

        $this->checkExtensions();
        $this->checkPhpVersion();
    }

    private function checkPhpVersion(): void
    {
        if (version_compare($this->phpVersion, self::REQUIRED_PHP_VERSION, '<')) {
            $this->errors[] = "The PHP version {$this->phpVersion} is not supported. Please use PHP version ".self::REQUIRED_PHP_VERSION.' or higher.';
        }
    }

    private function checkExtensions(): void
    {
        foreach ($this->requiredExtensions as $extension) {
            if (! extension_loaded($extension)) {
                $this->errors[] = "The extension {$extension} is not loaded";
            }
        }
    }
}
