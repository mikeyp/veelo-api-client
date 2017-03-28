<?php

namespace VeeloApi;

use Guzzle\Service\Loader\YamlLoader;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Symfony\Component\Config\FileLocator;

class VeeloApiClient extends GuzzleClient
{

    /**
     * Create a new Veelo API Client.
     */
    public static function create($config = [])
    {
        $configDirectories = array(dirname(__DIR__));
        $locator = new FileLocator($configDirectories);

        $yamlLoader = new YamlLoader($locator);
        $description = $yamlLoader->load($locator->locate('services.yaml'));

        if (isset($config['base_uri'])) {
            $description = ['baseUrl' => $config['base_uri']] + $description;
        }

        // Load the service description file.
        $service_description = new Description($description);


        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if (isset($config['token'])) {
            $headers['Authorization'] = "JWT {$config['token']}";
        }

        // Creates the client and sets the default request headers.
        $client = new Client([
            'headers' => $headers,
        ]);

        return new static($client, $service_description, NULL, NULL, NULL, $config);
    }
}
