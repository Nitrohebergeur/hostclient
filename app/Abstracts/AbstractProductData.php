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

namespace App\Abstracts;

use App\Contracts\Store\ProductDataInterface;
use App\DTO\Store\ProductDataDTO;

abstract class AbstractProductData implements ProductDataInterface
{
    protected array $parameters = [];

    public function primary(ProductDataDTO $productDataDTO): string
    {
        return $this->parameters[0] ?? '';
    }

    public function validate(): array
    {
        return [];
    }

    public function parameters(ProductDataDTO $productDataDTO): array
    {
        return collect($productDataDTO->parameters)->filter(function ($value, $key) {
            return in_array($key, $this->parameters);
        })->toArray();
    }

    public function render(ProductDataDTO $productDataDTO)
    {
        return 'Please override render method in your product data class';
    }

    public function renderAdmin(ProductDataDTO $productDataDTO)
    {
        return $this->render($productDataDTO);
    }
}
