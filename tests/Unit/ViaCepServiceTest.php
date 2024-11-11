<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Http;
use App\Services\ViaCepService;
use Tests\TestCase;
use Exception;

class ViaCepServiceTest extends TestCase
{
    private function setHttpFakeSuccessfulResponse(): void
    {
        Http::fake([
            'https://viacep.com.br/ws/02252080/json/*' => Http::response([
                'cep' => '02252-080',
                'logradouro' => 'Rua Mantena',
                'complemento' => '',
                'unidade' => '',
                'bairro' => 'Vila Nivi',
                'localidade' => 'São Paulo',
                'uf' => 'SP',
                'estado' => 'São Paulo',
                'regiao' => 'Sudeste',
                'ibge' => '3550308',
                'gia' => '1004',
                'ddd' => '11',
                'siafi' => '7107'
            ], 200, ['Headers']),
        ]);
    }

    private function setHttpFakeErrorResponse(): void
    {
        Http::fake([
            'https://viacep.com.br/ws/99999999/json/*' => Http::response([
                'erro' => 'true'
            ], 400, ['Headers']),
        ]);
    }

    public function test_get_address_successful_response(): void
    {
        /* Arrange */
        $this->setHttpFakeSuccessfulResponse();
        $service = new ViaCepService();

        /* Act */
        $address = $service->getAddress('02252-080');

        /* Assert */
        $this->assertEquals($address['endereco'], 'Rua Mantena, Vila Nivi, São Paulo/SP');
        $this->assertArrayHasKey('endereco', $address);
        $this->assertInstanceOf(ViaCepService::class, $service);
        $this->assertIsArray($address);
        $this->assertIsString($address['endereco']);
        $this->assertStringStartsWith('Rua', $address['endereco']);
        $this->assertStringEndsWith('Paulo/SP', $address['endereco']);
        $this->assertStringContainsString('Vila Nivi', $address['endereco']);
    }

    public function test_get_address_with_wrong_zip_code(): void
    {
        /* Arrange */
        $this->setHttpFakeErrorResponse();
        $service = new ViaCepService();

        /* Assert */
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Não foi possível buscar o endereço a partir do cep informado.');

        /* Act */
        $service->getAddress('99999-999');
    }

    public function test_format_data_method(): void
    {
        /* Arrange */
        $data = [
            'cep' => '06465-134',
            'logradouro' => 'Rua Bonnard',
            'complemento' => '(Green Valley I)',
            'unidade' => '',
            'bairro' => 'Alphaville Empresarial',
            'localidade' => 'Barueri',
            'uf' => 'SP',
            'estado' => 'São Paulo',
            'regiao' => 'Sudeste',
            'ibge' => '3505708',
            'gia' => '2069',
            'ddd' => '11',
            'siafi' => '6213'
        ];

        $service = new ViaCepService();

        /* Act */
        $cepObjectTest = $service->formatData($data);

        /* Assert */
        $this->assertEquals($cepObjectTest->endereco, 'Rua Bonnard, Alphaville Empresarial, Barueri/SP');
        $this->assertEquals($cepObjectTest->cep, $data['cep']);
        $this->assertEquals($cepObjectTest->logradouro, $data['logradouro']);
        $this->assertEquals($cepObjectTest->complemento, $data['complemento']);
        $this->assertEquals($cepObjectTest->bairro, $data['bairro']);
        $this->assertEquals($cepObjectTest->localidade, $data['localidade']);
        $this->assertEquals($cepObjectTest->uf, $data['uf']);
        $this->assertEquals($cepObjectTest->estado, $data['estado']);
        $this->assertEquals($cepObjectTest->regiao, $data['regiao']);
        $this->assertObjectHasProperty('endereco', $cepObjectTest);
        $this->assertObjectHasProperty('cep', $cepObjectTest);
        $this->assertObjectHasProperty('logradouro', $cepObjectTest);
        $this->assertObjectHasProperty('complemento', $cepObjectTest);
        $this->assertObjectHasProperty('bairro', $cepObjectTest);
        $this->assertObjectHasProperty('localidade', $cepObjectTest);
        $this->assertObjectHasProperty('uf', $cepObjectTest);
        $this->assertObjectHasProperty('estado', $cepObjectTest);
        $this->assertObjectHasProperty('regiao', $cepObjectTest);
        $this->assertIsObject($cepObjectTest);
        $this->assertInstanceOf(ViaCepService::class, $service);
    }
}
