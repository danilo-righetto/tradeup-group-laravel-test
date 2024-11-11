<?php

namespace App\Services;

use GuzzleHttp\Exception\RequestException;
use App\Contracts\SearchCepInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\Base\Service;
use Exception;

class ViaCepService extends Service implements SearchCepInterface
{
    const URL = 'https://viacep.com.br/ws/';

    public function getAddress(string $cepCode): array
    {
        $cep = $this->formatData($this->sendRequest($cepCode));
        return ['endereco' => $cep->endereco];
    }

    public function getAllData(string $cepCode): object
    {
        return $this->formatData($this->sendRequest($cepCode));
    }

    public function formatData(array $data): object
    {
        $cepObject = new \stdClass;

        $cepObject->cep = $data['cep'] ?? '';
        $cepObject->logradouro = $data['logradouro'] ?? '';
        $cepObject->complemento = $data['complemento'] ?? '';
        $cepObject->bairro = $data['bairro'] ?? '';
        $cepObject->localidade = $data['localidade'] ?? '';
        $cepObject->uf = $data['uf'] ?? '';
        $cepObject->estado = $data['estado'] ?? '';
        $cepObject->regiao = $data['regiao'] ?? '';
        $cepObject->endereco = $this->getFriendlyAddress($cepObject);

        return $cepObject;
    }

    private function getFriendlyAddress(object $cepObject): string
    {
        return $cepObject->logradouro . ", " . $cepObject->bairro . ", $cepObject->localidade/$cepObject->uf";
    }

    private function sendRequest(string $cepCode)
    {
        $cep = preg_replace('/[^0-9]/', '', $cepCode);

        if (Cache::has($cepCode)) {
            return Cache::get($cepCode);
        }

        try {
            $response = Http::get(self::URL . "$cep/json/");

            if(!$response->successful()) {
                throw new Exception("Não foi possível buscar o endereço a partir do cep informado.");
            }

            $cepResponse = json_decode($response->getBody(), true);

            if(isset($cepResponse['erro'])) {
                throw new Exception("Não foi possível buscar o endereço a partir do cep informado.");
            }
            Cache::set($cepCode, $cepResponse);
            return $cepResponse;
        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
