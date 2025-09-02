<?php

namespace JsonMutator\Casts;

use Illuminate\Database\Eloquent\Casts\Attribute;
use JsonMutator\Contracts\BaseJsonMutatorCast;

/**
 * JsonMutatorDTO Attribute Cast for Laravel 11+ & 12+ Eloquent Models
 *
 * This cast uses Laravel's newer Attribute-based approach for better
 * compatibility with Laravel 11+ & 12+ and provides the same functionality
 * as JsonMutatorCast but with improved type safety.
 */
class JsonMutatorAttributeCast extends BaseJsonMutatorCast
{
    /**
     * Create a new attribute instance.
     */
    public static function make(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes, $key) {
                $cast = new self();
                return $cast->get(null, $key, $value, $attributes);
            },
            set: function ($value, array $attributes, $key) {
                $cast = new self();
                return $cast->set(null, $key, $value, $attributes);
            }
        );
    }
}

