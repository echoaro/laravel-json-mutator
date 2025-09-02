<?php

namespace JsonMutator\Tests;

use Illuminate\Support\Collection;
use JsonMutator\Mutators\JsonMutatorDTO;
use PHPUnit\Framework\TestCase;

class JsonMutatorTest extends TestCase
{
    public function test_constructor_with_array()
    {
        $data = ['key' => 'value', 'nested' => ['test' => 'data']];
        $mutator = new JsonMutatorDTO($data);

        $this->assertEquals($data, $mutator->toArray());
    }

    public function test_constructor_with_empty_array()
    {
        $mutator = new JsonMutatorDTO();

        $this->assertEquals([], $mutator->toArray());
        $this->assertTrue($mutator->isEmpty());
    }

    public function test_get_with_dot_notation()
    {
        $data = ['user' => ['name' => 'John', 'email' => 'john@example.com']];
        $mutator = new JsonMutatorDTO($data);

        $this->assertEquals('John', $mutator->get('user.name'));
        $this->assertEquals('john@example.com', $mutator->get('user.email'));
        $this->assertNull($mutator->get('user.age'));
        $this->assertEquals('default', $mutator->get('user.age', 'default'));
    }

    public function test_set_with_dot_notation()
    {
        $mutator = new JsonMutatorDTO();

        $mutator->set('user.name', 'John');
        $mutator->set('user.email', 'john@example.com');

        $this->assertEquals('John', $mutator->get('user.name'));
        $this->assertEquals('john@example.com', $mutator->get('user.email'));
    }

    public function test_has_method()
    {
        $data = ['user' => ['name' => 'John']];
        $mutator = new JsonMutatorDTO($data);

        $this->assertTrue($mutator->has('user.name'));
        $this->assertFalse($mutator->has('user.age'));
    }

    public function test_forget_method()
    {
        $data = ['user' => ['name' => 'John', 'email' => 'john@example.com']];
        $mutator = new JsonMutatorDTO($data);

        $mutator->forget('user.email');

        $this->assertTrue($mutator->has('user.name'));
        $this->assertFalse($mutator->has('user.email'));
    }

    public function test_merge_with_array()
    {
        $mutator = new JsonMutatorDTO(['existing' => 'value']);
        $mutator->merge(['new' => 'data', 'nested' => ['key' => 'value']]);

        $this->assertEquals('value', $mutator->get('existing'));
        $this->assertEquals('data', $mutator->get('new'));
        $this->assertEquals('value', $mutator->get('nested.key'));
    }

    public function test_merge_with_collection()
    {
        $mutator = new JsonMutatorDTO(['existing' => 'value']);
        $collection = new Collection(['new' => 'data']);

        $mutator->merge($collection);

        $this->assertEquals('value', $mutator->get('existing'));
        $this->assertEquals('data', $mutator->get('new'));
    }

    public function test_merge_with_metadata_mutator()
    {
        $mutator1 = new JsonMutatorDTO(['key1' => 'value1']);
        $mutator2 = new JsonMutatorDTO(['key2' => 'value2']);

        $mutator1->merge($mutator2);

        $this->assertEquals('value1', $mutator1->get('key1'));
        $this->assertEquals('value2', $mutator1->get('key2'));
    }

    public function test_replace_method()
    {
        $mutator = new JsonMutatorDTO(['old' => 'value']);
        $mutator->replace(['new' => 'data']);

        $this->assertFalse($mutator->has('old'));
        $this->assertEquals('data', $mutator->get('new'));
    }

    public function test_clear_method()
    {
        $mutator = new JsonMutatorDTO(['key' => 'value']);
        $mutator->clear();

        $this->assertTrue($mutator->isEmpty());
        $this->assertEquals([], $mutator->toArray());
    }

    public function test_to_collection_method()
    {
        $data = ['key' => 'value'];
        $mutator = new JsonMutatorDTO($data);
        $collection = $mutator->toCollection();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals($data, $collection->toArray());
    }

    public function test_count_method()
    {
        $mutator = new JsonMutatorDTO(['key1' => 'value1', 'key2' => 'value2']);

        $this->assertEquals(2, $mutator->count());
    }

    public function test_array_access()
    {
        $mutator = new JsonMutatorDTO();

        $mutator['key'] = 'value';

        $this->assertTrue(isset($mutator['key']));
        $this->assertEquals('value', $mutator['key']);

        unset($mutator['key']);
        $this->assertFalse(isset($mutator['key']));
    }

    public function test_magic_methods()
    {
        $mutator = new JsonMutatorDTO();

        $mutator->test_property = 'value';

        $this->assertTrue(isset($mutator->test_property));
        $this->assertEquals('value', $mutator->test_property);

        unset($mutator->test_property);
        $this->assertFalse(isset($mutator->test_property));
    }

    public function test_to_json_method()
    {
        $data = ['key' => 'value'];
        $mutator = new JsonMutatorDTO($data);

        $json = $mutator->toJson();
        $decoded = json_decode($json, true);

        $this->assertEquals($data, $decoded);
    }

    public function test_from_json_static_method()
    {
        $json = '{"key":"value"}';
        $mutator = JsonMutatorDTO::fromJson($json);

        $this->assertEquals('value', $mutator->get('key'));
    }

    public function test_from_array_static_method()
    {
        $data = ['key' => 'value'];
        $mutator = JsonMutatorDTO::fromArray($data);

        $this->assertEquals($data, $mutator->toArray());
    }

    public function test_from_collection_static_method()
    {
        $collection = new Collection(['key' => 'value']);
        $mutator = JsonMutatorDTO::fromCollection($collection);

        $this->assertEquals(['key' => 'value'], $mutator->toArray());
    }

    public function test_iterator_aggregate()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        $mutator = new JsonMutatorDTO($data);

        $iterator = $mutator->getIterator();
        $this->assertInstanceOf(\ArrayIterator::class, $iterator);

        $result = [];
        foreach ($mutator as $key => $value) {
            $result[$key] = $value;
        }

        $this->assertEquals($data, $result);
    }
}

