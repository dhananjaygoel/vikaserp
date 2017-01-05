<?php

namespace App\Repositories;

use League\Flysystem\Dropbox\DropboxAdapter;
use League\Flysystem\Filesystem;
use Dropbox\Client;

class DropboxStorageRepository {

    protected $client;
    protected $adapter;

    public function __construct() {
        $this->client = new Client('MU7AdbbalnAAAAAAAAAAF0PyVT1KnpH6dehU6kjgnEntKUAdQDRauQJ2zHcVZbUN', 'Vikas Web', null);
        $this->adapter = new DropboxAdapter($this->client);
    }

    public function getConnection() {
        return new Filesystem($this->adapter);
    }

}
