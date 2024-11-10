<?php

namespace App\Services;

use App\Services\Base\Service;
use App\Contracts\SearchCepInterface;

class ViaCepService extends Service implements SearchCepInterface
{
    public function getAddress(string $cepCode): array
    {
        return [];
    }

    public function getAllData(string $cepCode): array
    {
        return [];
    }
}
