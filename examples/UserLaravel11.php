<?php

namespace Examples;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use JsonMutator\Casts\JsonMutatorAttributeCast;

/**
 * Example User model for Laravel 11+ & 12+ demonstrating the use of JsonMutatorAttributeCast
 */
class UserLaravel11 extends Model
{
    protected $fillable = [
        'name',
        'email',
        'metadata',
    ];

    /**
     * Metadata attribute using Laravel 11+ & 12+ Attribute-based cast
     */
    protected function metadata(): Attribute
    {
        return JsonMutatorAttributeCast::make();
    }


}

