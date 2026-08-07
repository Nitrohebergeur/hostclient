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

trait SupportRelateItemTrait
{
    public function relatedName(): string
    {
        return 'Related Name';
    }

    public function relatedId(): int
    {
        return $this->id;
    }

    public function relatedLink(): string
    {
        return route('admin.'.$this->relatedType().'s.show', $this->relatedId());
    }

    public function relatedIcon(): string
    {
        switch ($this->relatedType()) {
            case 'invoice':
                return 'bi bi-file-earmark-text';
            case 'service':
                return 'bi bi-cube';
            default:
                return 'bi bi-question-circle';
        }
    }

    public function relatedType(): string
    {
        return strtolower(class_basename($this));
    }
}
