<?php

namespace PhpMailer;

use PDO;

abstract class Controller
{
    protected PDO $connection;

    public function __construct()
    {
        $this->setConnection();
    }

    protected function setConnection(): void
    {

        $yamlInput = yaml_parse_file(__DIR__ . '/../config.yml');

        $dsn = "{$yamlInput['driver']}:host={$yamlInput['host']};dbname={$yamlInput['dbname']}";
        $username = $yamlInput['username'];
        $password = $yamlInput['password'];

        $this->connection = new PDO($dsn, $username, $password);
    }

    abstract public function indexAction();

}