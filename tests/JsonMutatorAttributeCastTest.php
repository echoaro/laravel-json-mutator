<?php

namespace JsonMutator\Tests;

use Illuminate\Database\Eloquent\Model;
use JsonMutator\Casts\JsonMutatorAttributeCast;
use JsonMutator\Mutators\JsonMutatorDTO;
use PHPUnit\Framework\TestCase;

class JsonMutatorAttributeCastTest extends TestCase
{
    private JsonMutatorAttributeCast $cast;

    public function test_get_with_null_value()
    {
        $result = $this->cast->get(null, 'metadata', null, []);

        $this->assertInstanceOf(JsonMutatorDTO::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_get_with_valid_json_string()
    {
        $json = '{"user":{"name":"John","email":"john@example.com"}}';
        $result = $this->cast->get(null, 'metadata', $json, []);

        $this->assertInstanceOf(JsonMutatorDTO::class, $result);
        $this->assertEquals('John', $result->get('user.name'));
        $this->assertEquals('john@example.com', $result->get('user.email'));
    }

    public function test_get_with_invalid_json_string()
    {
        $invalidJson = '{"user":{"name":"John",}'; // Invalid JSON
        $result = $this->cast->get(null, 'metadata', $invalidJson, []);

        $this->assertInstanceOf(JsonMutatorDTO::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    public function test_get_with_array_value()
    {
        $array = ['user' => ['name' => 'John']];
        $result = $this->cast->get(null, 'metadata', $array, []);

        $this->assertInstanceOf(JsonMutatorDTO::class, $result);
        $this->assertEquals('John', $result->get('user.name'));
    }

    public function test_set_with_metadata_mutator()
    {
        $mutator = new JsonMutatorDTO(['user' => ['name' => 'John']]);
        $result = $this->cast->set(null, 'metadata', $mutator, []);

        $this->assertEquals('{"user":{"name":"John"}}', $result);
    }

    public function test_set_with_null_value()
    {
        $result = $this->cast->set(null, 'metadata', null, []);

        $this->assertEquals('{}', $result);
    }

    public function test_set_with_array_value()
    {
        $array = ['user' => ['name' => 'John']];
        $result = $this->cast->set(null, 'metadata', $array, []);

        $this->assertEquals('{"user":{"name":"John"}}', $result);
    }

    public function test_set_with_valid_json_string()
    {
        $json = '{"user":{"name":"John"}}';
        $result = $this->cast->set(null, 'metadata', $json, []);

        $this->assertEquals($json, $result);
    }

    public function test_set_with_invalid_json_string()
    {
        $invalidJson = 'invalid json';
        $result = $this->cast->set(null, 'metadata', $invalidJson, []);

        $this->assertEquals('["invalid json"]', $result);
    }

    public function test_set_with_other_value_types()
    {
        $result = $this->cast->set(null, 'metadata', 'simple string', []);
        $this->assertEquals('["simple string"]', $result);

        $result = $this->cast->set(null, 'metadata', 123, []);
        $this->assertEquals('[123]', $result);

        $result = $this->cast->set(null, 'metadata', true, []);
        $this->assertEquals('[true]', $result);
    }

    public function test_round_trip_conversion()
    {
        $originalData = ['user' => ['name' => 'John', 'email' => 'john@example.com']];

        // Set the data
        $jsonResult = $this->cast->set(null, 'metadata', $originalData, []);

        // Get the data back
        $mutator = $this->cast->get(null, 'metadata', $jsonResult, []);

        $this->assertEquals($originalData, $mutator->toArray());
    }

    public function test_round_trip_with_metadata_mutator()
    {
        $originalMutator = new JsonMutatorDTO(['user' => ['name' => 'John']]);

        // Set the mutator
        $jsonResult = $this->cast->set(null, 'metadata', $originalMutator, []);

        // Get the data back
        $resultMutator = $this->cast->get(null, 'metadata', $jsonResult, []);

        $this->assertEquals($originalMutator->toArray(), $resultMutator->toArray());
    }

    public function test_make_static_method()
    {
        $attribute = JsonMutatorAttributeCast::make();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Casts\Attribute::class, $attribute);
    }

    public function test_make_static_method_with_custom_data()
    {
        $attribute = JsonMutatorAttributeCast::make();

        // Test the get callback
        $getCallback = $attribute->get;
        $result = $getCallback('{"test":"value"}', [], 'metadata');

        $this->assertInstanceOf(JsonMutatorDTO::class, $result);
        $this->assertEquals('value', $result->get('test'));

        // Test the set callback
        $setCallback = $attribute->set;
        $jsonResult = $setCallback(['test' => 'value'], [], 'metadata');

        $this->assertEquals('{"test":"value"}', $jsonResult);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->cast = new JsonMutatorAttributeCast();
    }
}

