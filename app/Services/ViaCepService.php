<?php

namespace App\Services;

use Exception;
use App\Services\Base\Service;
use App\Contracts\SearchCepInterface;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;

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

        try {
            $response = Http::get(self::URL . "$cep/json/");

            if(!$response->successful()) {
                throw new Exception("Não foi possível buscar o cep informado.");
            }

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
