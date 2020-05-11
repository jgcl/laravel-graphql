<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    /**
     * Model table
     *
     * @var string
     */
    protected $table = 'movements';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     *
     */
    public const CREATED_AT = 'created_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'amount', 'balance', 'created_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'account' => 'integer',
        'value' => 'double',
        'balance' => 'double',
        'created_at' => 'datetime',
    ];
}
