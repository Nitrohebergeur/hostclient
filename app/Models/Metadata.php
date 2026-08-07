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

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $model_id
 * @property string $model_type
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Metadata whereValue($value)
 *
 * @mixin \Eloquent
 */
class Metadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'key',
        'value',
    ];
}
