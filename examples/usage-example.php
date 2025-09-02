<?php

/**
 * Comprehensive usage example for Laravel Json Mutator
 *
 * This file demonstrates various ways to use the Json Mutator
 * and JsonMutatorDTO classes in your Laravel application.
 */

require_once 'vendor/autoload.php';

use Examples\User;
use JsonMutator\Mutators\JsonMutatorDTO;

// Example 1: Basic usage with a User model
echo "=== Example 1: Basic User Model Usage ===\n";

$user = new User();
$user->name = 'John Doe';
$user->email = 'john@example.com';

// Set metadata using array access
$user->metadata['profile']['age'] = 30;
$user->metadata['profile']['location'] = 'New York';
$user->metadata['preferences']['theme'] = 'dark';
$user->metadata['preferences']['language'] = 'en';

// Access metadata using array access
echo "User age: " . $user->metadata['profile']['age'] . "\n";
echo "User location: " . $user->metadata['profile']['location'] . "\n";
echo "Theme preference: " . $user->metadata['preferences']['theme'] . "\n";

// Check if a key exists
if (isset($user->metadata['preferences']['theme'])) {
    echo "User has theme preference set\n";
}

// Remove a key
unset($user->metadata['preferences']['language']);
echo "Language preference removed: " . (isset($user->metadata['preferences']['language']) ? 'No' : 'Yes') . "\n";

echo "\n";

// Example 2: Array access syntax
echo "=== Example 2: Array Access Syntax ===\n";

$user->metadata['settings']['notifications'] = true;
$user->metadata['settings']['email_alerts'] = false;

echo "Notifications enabled: " . ($user->metadata['settings']['notifications'] ? 'Yes' : 'No') . "\n";
echo "Email alerts enabled: " . ($user->metadata['settings']['email_alerts'] ? 'Yes' : 'No') . "\n";

echo "\n";

// Example 3: Magic properties
echo "=== Example 3: Magic Properties ===\n";

$user->metadata->user_role = 'admin';
$user->metadata->last_login = '2024-01-15 10:30:00';

echo "User role: " . $user->metadata->user_role . "\n";
echo "Last login: " . $user->metadata->last_login . "\n";

echo "\n";

// Example 4: Merging data
echo "=== Example 4: Merging Data ===\n";

// Merge with array
$user->metadata->merge([
    'tags' => ['admin', 'moderator'],
    'permissions' => ['read', 'write', 'delete']
]);

// Merge with collection
$collection = collect(['achievements' => ['first_login', 'profile_complete']]);
$user->metadata->merge($collection);

// Merge with another metadata mutator
$otherMetadata = new JsonMutatorDTO(['social' => ['twitter' => '@johndoe']]);
$user->metadata->merge($otherMetadata);

echo "Tags: " . implode(', ', $user->metadata->get('tags', [])) . "\n";
echo "Permissions: " . implode(', ', $user->metadata->get('permissions', [])) . "\n";
echo "Achievements: " . implode(', ', $user->metadata->get('achievements', [])) . "\n";
echo "Twitter: " . $user->metadata->get('social.twitter') . "\n";

echo "\n";

// Example 5: Working with collections
echo "=== Example 5: Working with Collections ===\n";

$collection = $user->metadata->toCollection();
echo "Total metadata items: " . $collection->count() . "\n";

$collection->each(function ($value, $key) {
    echo "Key: $key, Value: " . (is_array($value) ? json_encode($value) : $value) . "\n";
});

echo "\n";

// Example 6: Static methods
echo "=== Example 6: Static Methods ===\n";

// Create from JSON
$jsonData = '{"temp_key":"temp_value","nested":{"data":"example"}}';
$tempMetadata = JsonMutatorDTO::fromJson($jsonData);
echo "From JSON - temp_key: " . $tempMetadata->get('temp_key') . "\n";
echo "From JSON - nested.data: " . $tempMetadata->get('nested.data') . "\n";

// Create from array
$arrayData = ['array_key' => 'array_value'];
$arrayMetadata = JsonMutatorDTO::fromArray($arrayData);
echo "From Array - array_key: " . $arrayMetadata->get('array_key') . "\n";

// Create from collection
$collectionData = collect(['collection_key' => 'collection_value']);
$collectionMetadata = JsonMutatorDTO::fromCollection($collectionData);
echo "From Collection - collection_key: " . $collectionMetadata->get('collection_key') . "\n";

echo "\n";

// Example 7: Fluent interface
echo "=== Example 7: Fluent Interface ===\n";

$user->metadata
    ->set('fluent.test1', 'value1')
    ->set('fluent.test2', 'value2')
    ->set('fluent.test3', 'value3')
    ->forget('fluent.test2')
    ->merge(['fluent' => ['test4' => 'value4']]);

echo "Fluent test1: " . $user->metadata->get('fluent.test1') . "\n";
echo "Fluent test2 exists: " . ($user->metadata->has('fluent.test2') ? 'Yes' : 'No') . "\n";
echo "Fluent test3: " . $user->metadata->get('fluent.test3') . "\n";
echo "Fluent test4: " . $user->metadata->get('fluent.test4') . "\n";

echo "\n";

// Example 8: Utility methods
echo "=== Example 8: Utility Methods ===\n";

echo "Is metadata empty: " . ($user->metadata->isEmpty() ? 'Yes' : 'No') . "\n";
echo "Is metadata not empty: " . ($user->metadata->isNotEmpty() ? 'Yes' : 'No') . "\n";
echo "Total metadata items: " . $user->metadata->count() . "\n";

// Get as JSON
$jsonOutput = $user->metadata->toJson(JSON_PRETTY_PRINT);
echo "Metadata as JSON:\n$jsonOutput\n";

// Example 9: Working with nested data
echo "=== Example 9: Working with Nested Data ===\n";

// Set user preferences using array access
$user->metadata['preferences'] = [
    'theme' => 'light',
    'notifications' => true,
    'timezone' => 'UTC'
];

// Get user preference
echo "User theme: " . $user->metadata['preferences']['theme'] . "\n";
echo "Notifications enabled: " . ($user->metadata['preferences']['notifications'] ? 'Yes' : 'No') . "\n";

// Set profile data
$user->metadata['profile'] = [
    'bio' => 'Software developer',
    'website' => 'https://example.com',
    'company' => 'Tech Corp'
];

// Get profile data
echo "User bio: " . $user->metadata['profile']['bio'] . "\n";
echo "User website: " . $user->metadata['profile']['website'] . "\n";

// Add tags
$user->metadata['tags'] = ['developer', 'php', 'laravel'];
echo "User has 'developer' tag: " . (in_array('developer', $user->metadata['tags']) ? 'Yes' : 'No') . "\n";

// Set settings
$user->metadata['settings']['debug_mode'] = true;
$user->metadata['settings']['log_level'] = 'info';
echo "Debug mode: " . ($user->metadata['settings']['debug_mode'] ? 'Enabled' : 'Disabled') . "\n";
echo "Log level: " . $user->metadata['settings']['log_level'] . "\n";

echo "\n";

// Example 10: Iterator usage
echo "=== Example 10: Iterator Usage ===\n";

echo "Iterating through metadata:\n";
foreach ($user->metadata as $key => $value) {
    echo "  $key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
}

echo "\n=== Example Complete ===\n";

