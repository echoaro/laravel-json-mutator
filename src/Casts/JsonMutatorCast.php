<?php

namespace JsonMutator\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use JsonMutator\Contracts\BaseJsonMutatorCast;
use JsonMutator\Mutators\JsonMutatorDTO;

/**
 * JsonMutatorDTO Cast for Laravel Eloquent Models
 *
 * This cast allows you to easily manage JSON JsonMutatorDTO columns
 * with a fluent interface and dot notation support.
 */
class JsonMutatorCast extends BaseJsonMutatorCast implements CastsAttributes
{
    /**
     * Get the serialized representation of the value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function serialize($model, string $key, $value, array $attributes)
    {
        if ($value instanceof JsonMutatorDTO) {
            return $value->toArray();
        }

        return $value;
    }


}
