Veelo API client
================

This is a PHP library for accessing the [Veelo API](https://home.veeloapp.com/docs/).

Install
-------

Add this requirement to your `composer.json` file:

```
"require": {
    "mikeyp/veelo-api-client": "@stable"
}
```

Usage
-----

Example:

```php
use \VeeloApi\VeeloApiClient;

// Initialize the client.
$client = VeeloApiClient::create();

// Get an authorization token.
$response = $client->generateToken(['identification' => 'user@example.com', 'password' => 'secret']);
echo $response['token'];


// Initialize the client with a token.
$client = VeeloApiClient::create(['token' => $token]);

// List admin groups.
$response = $client->listAdminGroups();

// List circles in an admin group.
$response = $client->listCircles(['admingroup_id' => $admingroup_id]);

// Upload a file.
$response = $client->uploadFile(['admingroup_id' => $admingroup_id, 'file' => fopen('path/to/file.pdf', 'r')]);

```

To do
-----

1. Finish operations for Users and Admingroups
2. Add wrapper methods to the client with type hints and documentation for each operations (this is especially helpful with some of the more complicated request bodies, such as tagging content where admingroup_id is required twice)
3. Tests
4. Change how token is handled in constructor?