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
$response = $client->generateToken('user@example.com', 'password');
echo $response['token'];


// Initialize the client with a token.
$client = VeeloApiClient::create(['token' => $token]);

// List admin groups.
$response = $client->listAdminGroups();

// List circles in an admin group.
$response = $client->listCircles($admingroup_id);

// Upload a file.
$response = $client->uploadFile($admingroup_id, fopen('path/to/file.pdf', 'r'));

```

To do
-----

1. Finish operations for Users and Admingroups
2. Tests
