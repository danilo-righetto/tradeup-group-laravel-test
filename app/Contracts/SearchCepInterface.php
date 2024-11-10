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
    public function getAllData(string $cepCode): array;
}
