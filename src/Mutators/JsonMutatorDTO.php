<?php

namespace JsonMutator\Mutators;

use ArrayAccess;
use Countable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;

/**
 * JsonMutatorDTO Mutator for easy JSON JsonMutatorDTO management
 *
 * This class provides a fluent interface for managing JSON JsonMutatorDTO
 * with support for dot notation, collections, and array operations.
 */
class JsonMutatorDTO implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * The JsonMutatorDTO items
     */
    protected array $items = [];

    /**
     * Create a new JsonMutatorDTO mutator instance
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Create from JSON string
     */
    public static function fromJson(string $json): self
    {
        return new self(json_decode($json, true) ?: []);
    }

    /**
     * Create from array
     */
    public static function fromArray(array $array): self
    {
        return new self($array);
    }

    /**
     * Create from collection
     */
    public static function fromCollection(Collection $collection): self
    {
        return new self($collection->toArray());
    }

    /**
     * Get all items as collection
     */
    public function toCollection(): Collection
    {
        return new Collection($this->items);
    }

    /**
     * Merge data into the JsonMutatorDTO
     */
    public function merge($data): self
    {
        if (is_array($data)) {
            $this->items = array_merge_recursive($this->items, $data);
            return $this;
        }

        if ($data instanceof Collection) {
            $this->items = array_merge_recursive($this->items, $data->toArray());
            return $this;
        }

        if ($data instanceof self) {
            $this->items = array_merge_recursive($this->items, $data->toArray());
            return $this;
        }

        $this->items[] = $data;
        return $this;
    }

    /**
     * Get all items as array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Replace all data
     */
    public function replace($data): self
    {
        if (is_array($data)) {
            $this->items = $data;
            return $this;
        }

        if ($data instanceof Collection) {
            $this->items = $data->toArray();
            return $this;
        }

        if ($data instanceof self) {
            $this->items = $data->toArray();
            return $this;
        }

        $this->items = [$data];
        return $this;
    }

    /**
     * Clear all JsonMutatorDTO
     */
    public function clear(): self
    {
        $this->items = [];
        return $this;
    }

    /**
     * Check if JsonMutatorDTO is not empty
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Check if JsonMutatorDTO is empty
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Get the number of items
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Get an iterator for the items
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Check if an offset exists
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Get an offset
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * Set an offset
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Unset an offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * Magic getter for property access
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Magic setter for property access
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Get a JsonMutatorDTO value using dot notation
     */
    public function get(string $key, $default = null)
    {
        return data_get($this->items, $key, $default);
    }

    /**
     * Set a JsonMutatorDTO value using dot notation
     */
    public function set(string $key, $value): self
    {
        data_set($this->items, $key, $value);
        return $this;
    }

    /**
     * Magic isset for property access
     */
    public function __isset($name): bool
    {
        return $this->has($name);
    }

    /**
     * Check if a key exists
     */
    public function has(string $key): bool
    {
        return data_get($this->items, $key) !== null;
    }

    /**
     * Magic unset for property access
     */
    public function __unset($name): void
    {
        $this->forget($name);
    }

    /**
     * Remove a key from JsonMutatorDTO
     */
    public function forget(string $key): self
    {
        data_forget($this->items, $key);
        return $this;
    }

    /**
     * Convert to JSON string
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->items, $options);
    }
}

