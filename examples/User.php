<?php

namespace Examples;

use Illuminate\Database\Eloquent\Model;
use JsonMutator\Casts\JsonMutatorCast;

/**
 * Example User model demonstrating the use of JsonMutatorCast
 */
class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'metadata',
    ];

    protected $casts = [
        'metadata' => JsonMutatorCast::class,
    ];


}

