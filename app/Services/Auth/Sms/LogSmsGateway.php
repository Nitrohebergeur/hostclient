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

namespace App\Services\Auth\Sms;

use App\Contracts\Auth\SmsGatewayContract;

class LogSmsGateway implements SmsGatewayContract
{
    public function send(string $to, string $message): void
    {
        logger()->info('mfa.sms.log_driver', [
            'to' => $this->mask($to),
            'body_chars' => strlen($message),
        ]);
    }

    public function name(): string
    {
        return 'log';
    }

    public function rules(array $data): array
    {
        return [];
    }

    private function mask(string $to): string
    {
        $len = strlen($to);
        if ($len <= 4) {
            return str_repeat('*', $len);
        }

        return substr($to, 0, 2).str_repeat('*', max(0, $len - 4)).substr($to, -2);
    }
}
