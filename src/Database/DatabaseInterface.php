<?php

namespace Freddymu\Phpnotif\Database;

/**
 * Interface DatabaseInterface
 * @package Freddymu\Phpnotif\Database
 */
interface DatabaseInterface
{
    /**
     * @return mixed
     */
    public function openConnection();

    /**
     * @return mixed
     */
    public function getConnection();

    /**
     * @return mixed
     */
    public function closeConnection();

    /**
     * @param string $referenceName
     * @param array $payload
     * @return mixed
     */
    public function create(string $referenceName, array $payload);

    /**
     * @param string $referenceName
     * @param array $payload
     * @return mixed
     */
    public function read(string $referenceName, array $payload);

    /**
     * @param string $referenceName
     * @param array $payload
     * @return mixed
     */
    public function update(string $referenceName, array $payload);

    /**
     * @param string $referenceName
     * @param array $payload
     * @return mixed
     */
    public function delete(string $referenceName, array $payload);
}
