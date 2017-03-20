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
     *
     */
    public static function create($config = [])
    {
        $configDirectories = array(dirname(__DIR__));
        $this->locator = new FileLocator($configDirectories);

        $this->yamlLoader = new YamlLoader($this->locator);
        $description = $this->yamlLoader->load($this->locator->locate('services.yaml'));

        // Load the service description file.
        $service_description = new Description(
            ['baseUrl' => $config['base_uri']] + $description
        );

        // Creates the client and sets the default request headers.
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        return new static($client, $service_description, NULL, NULL, NULL, $config);
    }
}
