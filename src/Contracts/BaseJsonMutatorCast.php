<?php

namespace JsonMutator\Contracts;

use JsonMutator\Mutators\JsonMutatorDTO;

abstract class BaseJsonMutatorCast
{
    /**
     * Cast the given value from the database.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return \JsonMutatorCast\Mutators\JsonMutator
     */
    public function get($model, string $key, $value, array $attributes): JsonMutatorDTO
    {
        if (is_null($value)) {
            return new JsonMutatorDTO();
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // If JSON is invalid, return empty mutator
                return new JsonMutatorDTO();
            }

            return new JsonMutatorDTO($decoded ?: []);
        }

        if (is_array($value)) {
            return new JsonMutatorDTO($value);
        }

        return new JsonMutatorDTO();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        // If value is already a JsonMutatorDTO, use it directly
        if ($value instanceof JsonMutatorDTO) {
            return $value->toJson();
        }

        // If value is null, return empty JSON object
        if (is_null($value)) {
            return '{}';
        }

        // If value is an array, create new mutator
        if (is_array($value)) {
            return (new JsonMutatorDTO($value))->toJson();
        }

        // If value is a string and looks like JSON, validate and return
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $value;
            }
        }

        // For any other type, wrap in array and encode
        return json_encode([$value]);
    }
}