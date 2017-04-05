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

    /**
     * Generate a token for authenticating requests with the Veelo API.
     * @param string $identification
     * @param string $password
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function generateToken($identification, $password)
    {
        $command = $this->getCommand('generateToken', ['identification' => $identification, 'password' => $password]);
        return $this->execute($command);
    }

    /**
     * Refresh an authentication token.
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function refreshToken()
    {
        $command = $this->getCommand('refreshToken');
        return $this->execute($command);
    }

    /**
     * Returns a nested list of AdminGroups.
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function listAdminGroups()
    {
        $command = $this->getCommand('listAdminGroups');
        return $this->execute($command);
    }

    /**
     * Return a list of circles within an admin group.
     * @param string $admingroup_id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function listCircles($admingroup_id)
    {
        $command = $this->getCommand('listCircles', ['admingroup_id' => $admingroup_id]);
        return $this->execute($command);
    }

    /**
     * Upload a file.
     * @param string $admingroup_id
     * @param resource $file
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function uploadFile($admingroup_id, $file)
    {
        $command = $this->getCommand('uploadFile', ['admingroup_id' => $admingroup_id, 'file' => $file]);
        return $this->execute($command);
    }

    /**
     * Get information about a file.
     * @param string $file_id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function getFile($file_id)
    {
        $command = $this->getCommand('getFile', ['file_id' => $file_id]);
        return $this->execute($command);
    }

    /**
     * Delete a file.
     * @param string $file_id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function deleteFile($file_id)
    {
        $command = $this->getCommand('deleteFile', ['file_id' => $file_id]);
        return $this->execute($command);
    }

    /**
     * Upload a new version of a file.
     * @param string $file_id
     * @param resource $file
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function replaceFile($file_id, $file)
    {
        $command = $this->getCommand('replaceFile', ['file_id' => $file_id, 'file' => $file]);
        return $this->execute($command);
    }

    /**
     * Update file properties.
     * @param string $file_id
     * @param array $attributes
     *   - confidential (boolean)
     *   - name (string)
     *   - filename (string)
     *   - state (string) either 'Active' or 'Inactive'
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function updateFile($file_id, array $attributes)
    {
        $attributes['file_id'] = $file_id;
        $command = $this->getCommand('updateFile', $attributes);
        return $this->execute($command);
    }

    /**
     * Add files to an admin group.
     * @param string $admingroup_id
     * @param array $file_ids
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function addFilesToGroup($admingroup_id, array $file_ids)
    {
        $args = ['admingroup_id' => $admingroup_id];

        foreach ($file_ids as $file_id) {
            $args['objects'][] = ['id' => $file_id];
        }

        $command = $this->getCommand('addFilesToGroup', $args);
        return $this->execute($command);
    }

    /**
     * Remove files from an admin group.
     * @param string $admingroup_id
     * @param array $file_ids
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function removeFilesFromGroup($admingroup_id, array $file_ids)
    {
        $args = ['admingroup_id' => $admingroup_id];

        foreach ($file_ids as $file_id) {
            $args['objects'][] = ['id' => $file_id];
        }

        $command = $this->getCommand('removeFilesFromGroup', $args);
        return $this->execute($command);
    }

    /**
     * Add files to a circle.
     * @param string $circle_id
     * @param array $file_ids
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function addFilesToCircle($circle_id, array $file_ids)
    {
        $args = ['circle_id' => $circle_id];

        foreach ($file_ids as $file_id) {
            $args['objects'][] = ['id' => $file_id];
        }

        $command = $this->getCommand('addFilesToCircle', $args);
        return $this->execute($command);
    }

    /**
     * Remove files from a circle.
     * @param string $circle_id
     * @param array $file_ids
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function removeFilesFromCircle($circle_id, array $file_ids)
    {
        $args = ['circle_id' => $circle_id];

        foreach ($file_ids as $file_id) {
            $args['objects'][] = ['id' => $file_id];
        }

        $command = $this->getCommand('removeFilesFromCircle', $args);
        return $this->execute($command);
    }

    /**
     * List tag categories.
     * @param boolean $is_relevance
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function listTagCategories($is_relevance = NULL)
    {
        if (!is_null($is_relevance)) {
            $args = ['is_relevance' => $is_relevance ? 'true' : 'false'];
        }
        else {
            $args = [];
        }

        $commmand = $this->getCommand('listTagCategories', $args);
        return $this->execute($commmand);
    }

    /**
     * Create a new tag category.
     * @param string $name
     * @param boolean $is_relevance
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function createTagCategory($name, $is_relevance = FALSE)
    {
        $command = $this->getCommand('createTagCategory', ['name' => $name, 'is_relevance' => $is_relevance]);
        return $this->execute($command);
    }

    /**
     * Get a tag category.
     * @param string $category_id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function getTagCategory($category_id)
    {
        $command = $this->getCommand('getTagCategory', ['category_id' => $category_id]);
        return $this->execute($command);
    }

    /**
     * Update a tag category.
     * @param string $category_id
     * @param array $attributes
     *   - name (string)
     *   - is_relevance (boolean)
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function updateTagCategory($category_id, array $attributes)
    {
        $attributes['category_id'] = $category_id;
        $command = $this->getCommand('updateTagCategory', $attributes);
        return $this->execute($command);
    }

    /**
     * Delete a tag category.
     * @param string $category_id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function deleteTagCategory($category_id)
    {
        $command = $this->getCommand('deleteTagCategory', ['category_id' => $category_id]);
        return $this->execute($command);
    }

    /**
     * Get all tags in a tag category.
     * @param string $category_id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function listTags($category_id)
    {
        $command = $this->getCommand('listTags', ['category_id' => $category_id]);
        return $this->execute($command);
    }

    /**
     * Create a new tag in a tag category.
     * @param string $category_id
     * @param string $name
     * @param string $customer_identifier
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function createTag($category_id, $name, $customer_identifier = '')
    {
        $command = $this->getCommand('createTag', ['category_id' => $category_id, 'name' => $name, 'customer_identifier' => $customer_identifier]);
        return $this->execute($command);
    }

    /**
     * Get a tag
     * @param string $category_id
     * @param string $tag_id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function getTag($category_id, $tag_id)
    {
        $command = $this->getCommand('getTag', ['category_id' => $category_id, 'tag_id' => $tag_id]);
        return $this->execute($command);
    }


    /**
     * Update a tag.
     * @param string $category_id
     * @param string $tag_id
     * @param array $attributes
     *   - name (string)
     *   - customer_identifier (string)
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function updateTag($category_id, $tag_id, array $attributes)
    {
        $attributes['category_id'] = $category_id;
        $attributes['tag_id'] = $tag_id;
        $command = $this->getCommand('updateTag', $attributes);
        return $this->execute($command);
    }

    /**
     * Delete a tag.
     * @param string $category_id
     * @param string $tag_id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function deleteTag($category_id, $tag_id)
    {
        $command = $this->getCommand('deleteTag', ['category_id' => $category_id, 'tag_id' => $tag_id]);
        return $this->execute($command);
    }

    /**
     * Add a tag to a piece of content.
     * @param string $category_id
     * @param string $object_id
     * @param string $object_type
     * @param string $tag_text
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function addTagToContent($category_id, $object_id, $object_type, $tag_text)
    {
        $args = [
            'category_id' => $category_id,
            'obj' => [
                'id' => $object_id,
                'type' => $object_type,
            ],
            'target' => [
                'category' => [
                    'id' => $category_id,
                ],
                'name' => $tag_text
            ],
        ];
        $command = $this->getCommand('addTagToContent', $args);
        return $this->execute($command);
    }

    /**
     * Delete a tag from a piece of content.
     * @param string $category_id
     * @param string $object_id
     * @param string $object_type
     * @param string $id
     * @return \GuzzleHttp\Command\ResultInterface|mixed
     */
    public function removeTagFromContent($category_id, $object_id, $object_type, $id)
    {
        $args = [
            'category_id' => $category_id,
            'obj' => [
                'id' => $object_id,
                'type' => $object_type,
            ],
            'target' => [
                'category' => [
                    'id' => $category_id,
                ],
                'id' => $id
            ],
        ];
        $command = $this->getCommand('removeTagFromContent', $args);
        return $this->execute($command);
    }
}

