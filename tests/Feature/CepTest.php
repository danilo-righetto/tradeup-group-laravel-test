<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Enums\RequestStatusCodeEnum;
use Tests\TestCase;

class CepTest extends TestCase
{
    const URL = '/api/services/cep';

    /*
     * Tests for endpoint /api/services/cep/address
     */
    public function test_get_address_by_cep_returns_a_successful_response(): void
    {
        /* Arrange */
        $cep = '02252-080';
        $expectedData = [
            'version' => env('VERSION'),
            'status' => RequestStatusCodeEnum::OK->value,
            'body' => ['endereco' => 'Rua Mantena, Vila Nivi, São Paulo/SP']
        ];

        /* Act */
        $response = $this->get(self::URL . "/address?cep=$cep");

        /* Assert */
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $response->assertHeader('access-control-allow-origin', '*');
        $response->assertHeader('cache-control', 'no-cache, private');
        $response->assertJson($expectedData, true);
        $response->assertJsonPath('version', env('VERSION'));
        $response->assertJsonPath('status', RequestStatusCodeEnum::OK->value);
        $response->assertJsonPath('body.endereco', 'Rua Mantena, Vila Nivi, São Paulo/SP');
    }

    public function test_get_address_by_cep_with_wrong_zip_code(): void
    {
        /* Arrange */
        $cep = '99999-999';
        $expectedData = [
            'version' => env('VERSION'),
            'status' => RequestStatusCodeEnum::NOT_FOUND->value,
            'error' => ['mensagem' => 'Não foi possível buscar o endereço a partir do cep informado.']
        ];

        /* Act */
        $response = $this->get(self::URL . "/address?cep=$cep");

        /* Assert */
        $response->assertStatus(404);
        $response->assertHeader('content-type', 'application/json');
        $response->assertHeader('access-control-allow-origin', '*');
        $response->assertHeader('cache-control', 'no-cache, private');
        $response->assertJson($expectedData, true);
        $response->assertJsonPath('version', env('VERSION'));
        $response->assertJsonPath('status', RequestStatusCodeEnum::NOT_FOUND->value);
        $response->assertJsonPath('error.mensagem', 'Não foi possível buscar o endereço a partir do cep informado.');
    }

    public function test_get_address_by_cep_with_invalid_zip_code(): void
    {
        /* Arrange */
        $cep = '99-99-999';
        $expectedData = [
            'message' => 'O cep não é um CEP válido.',
            'errors' => ['cep' => ['O cep não é um CEP válido.']]
        ];

        /* Act */
        $response = $this->withHeaders(['Accept' => 'application/json'])->get(self::URL . "/address?cep=$cep");

        /* Assert */
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertHeader('access-control-allow-origin', '*');
        $response->assertHeader('cache-control', 'no-cache, private');
        $response->assertJson($expectedData, true);
        $response->assertJsonPath('message', 'O cep não é um CEP válido.');
        $response->assertJsonPath('errors.cep.0', 'O cep não é um CEP válido.');
    }

    public function test_get_address_by_cep_without_zip_code(): void
    {
        /* Arrange */
        $expectedData = [
            'message' => 'O campo cep é obrigatório.',
            'errors' => ['cep' => ['O campo cep é obrigatório.']]
        ];

        /* Act */
        $response = $this->withHeaders(['Accept' => 'application/json'])->get(self::URL . "/address");

        /* Assert */
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertHeader('access-control-allow-origin', '*');
        $response->assertHeader('cache-control', 'no-cache, private');
        $response->assertJson($expectedData, true);
        $response->assertJsonPath('message', 'O campo cep é obrigatório.');
        $response->assertJsonPath('errors.cep.0', 'O campo cep é obrigatório.');
    }

    /*
     * Tests for endpoint /api/services/cep/all
     */
    public function test_get_all_address_information_by_cep_returns_a_successful_response(): void
    {
        /* Arrange */
        $cep = '02252-080';
        $expectedData = [
            'version' => env('VERSION'),
            'status' => RequestStatusCodeEnum::OK->value,
            'body' => [
                'cep' => '02252-080',
                'logradouro' => 'Rua Mantena',
                'complemento' => '',
                'bairro' => 'Vila Nivi',
                'localidade' => 'São Paulo',
                'uf' => 'SP',
                'estado' => 'São Paulo',
                'regiao' => 'Sudeste',
                'endereco' => 'Rua Mantena, Vila Nivi, São Paulo/SP'
            ]
        ];

        /* Act */
        $response = $this->get(self::URL . "/all?cep=$cep");

        /* Assert */
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $response->assertHeader('access-control-allow-origin', '*');
        $response->assertHeader('cache-control', 'no-cache, private');
        $response->assertJson($expectedData, true);
        $response->assertJsonPath('body.cep', '02252-080');
        $response->assertJsonPath('body.logradouro', 'Rua Mantena');
        $response->assertJsonPath('body.complemento', '');
        $response->assertJsonPath('body.bairro', 'Vila Nivi');
        $response->assertJsonPath('body.localidade', 'São Paulo');
        $response->assertJsonPath('body.uf', 'SP');
        $response->assertJsonPath('body.estado', 'São Paulo');
        $response->assertJsonPath('body.regiao', 'Sudeste');
        $response->assertJsonPath('body.endereco', 'Rua Mantena, Vila Nivi, São Paulo/SP');
    }

    public function test_get_all_address_information_by_cep_with_wrong_zip_code(): void
    {
        /* Arrange */
        $cep = '99999-999';
        $expectedData = [
            'version' => env('VERSION'),
            'status' => RequestStatusCodeEnum::NOT_FOUND->value,
            'error' => ['mensagem' => 'Não foi possível buscar o endereço a partir do cep informado.']
        ];

        /* Act */
        $response = $this->get(self::URL . "/all?cep=$cep");

        /* Assert */
        $response->assertStatus(404);
        $response->assertHeader('content-type', 'application/json');
        $response->assertHeader('access-control-allow-origin', '*');
        $response->assertHeader('cache-control', 'no-cache, private');
        $response->assertJson($expectedData, true);
        $response->assertJsonPath('version', env('VERSION'));
        $response->assertJsonPath('status', RequestStatusCodeEnum::NOT_FOUND->value);
        $response->assertJsonPath('error.mensagem', 'Não foi possível buscar o endereço a partir do cep informado.');
    }

    public function test_get_all_address_information_by_cep_with_invalid_zip_code(): void
    {
        /* Arrange */
        $cep = '99-99-999';
        $expectedData = [
            'message' => 'O cep não é um CEP válido.',
            'errors' => ['cep' => ['O cep não é um CEP válido.']]
        ];

        /* Act */
        $response = $this->withHeaders(['Accept' => 'application/json'])->get(self::URL . "/all?cep=$cep");

        /* Assert */
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertHeader('access-control-allow-origin', '*');
        $response->assertHeader('cache-control', 'no-cache, private');
        $response->assertJson($expectedData, true);
        $response->assertJsonPath('message', 'O cep não é um CEP válido.');
        $response->assertJsonPath('errors.cep.0', 'O cep não é um CEP válido.');
    }

    public function test_get_all_address_information_by_cep_without_zip_code(): void
    {
        /* Arrange */
        $expectedData = [
            'message' => 'O campo cep é obrigatório.',
            'errors' => ['cep' => ['O campo cep é obrigatório.']]
        ];

        /* Act */
        $response = $this->withHeaders(['Accept' => 'application/json'])->get(self::URL . "/all");

        /* Assert */
        $response->assertStatus(422);
        $response->assertHeader('content-type', 'application/json');
        $response->assertHeader('access-control-allow-origin', '*');
        $response->assertHeader('cache-control', 'no-cache, private');
        $response->assertJson($expectedData, true);
        $response->assertJsonPath('message', 'O campo cep é obrigatório.');
        $response->assertJsonPath('errors.cep.0', 'O campo cep é obrigatório.');
    }
}
