<?php

namespace App\Contracts;

interface SearchCepInterface
{
    /**
     * Get only address data
     */
    public function getAddress(string $cepCode): array;

    /**
     * Get all service data
     */
    public function getAllData(string $cepCode): object;

    /**
     * Formats the information obtained by the class
     */
    public function formatData(array $data): object;
}
