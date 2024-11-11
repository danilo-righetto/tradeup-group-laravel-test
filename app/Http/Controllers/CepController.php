<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ViaCepService;
use App\Traits\ResponseUtils;
use App\Enums\RequestStatusCodeEnum;
use App\Http\Requests\SearchCepRequest;
use Illuminate\Support\Facades\Cache;
use Exception;

class CepController extends Controller
{
    use ResponseUtils;

    public function __construct(
        private ViaCepService $service
    ) {}

    /**
     * @OA\Get(
     *     path="/api/services/cep/address",
     *     summary="Busca um endereço a partir de um cep.",
     *     tags={"Cep"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="string",
     *             example="{""version"":""1.0.0"",""status"":200,""body"":{""endereco"":""Rua Mantena, Vila Nivi, São Paulo\/SP""}}"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="cep",
     *         in="query",
     *         description="Dados do Cep",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="string",
     *             example={"version":"1.0.0","status":500,"error":{"mensagem":"Não foi possível buscar o endereço a partir do cep informado."}}
     *         )
     *     )
     * )
     */
    public function getAddressByCep(SearchCepRequest $request)
    {
        try {
            $addressReturned = $this->service->getAddress($request->validated('cep'));
            return $this->responseSuccess(RequestStatusCodeEnum::OK->value, $addressReturned);
        } catch (Exception $ex) {
            Cache::flush();
            return $this->responseError(RequestStatusCodeEnum::NOT_FOUND->value, $ex->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/services/cep/all",
     *     summary="Busca todos os dados de endereço a partir de um cep.",
     *     tags={"Cep"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="string",
     *             example="{""version"":""1.0.0"",""status"":200,""body"":{""cep"":""02252-080"",""logradouro"":""Rua Mantena"",""complemento"":"""",""bairro"":""Vila Nivi"",""localidade"":""São Paulo"",""uf"":""SP"",""estado"":""São Paulo"",""regiao"":""Sudeste"",""endereco"":""Rua Mantena, Vila Nivi, São Paulo\/SP""}}"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="cep",
     *         in="query",
     *         description="Dados do Cep",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="string",
     *             example={"version":"1.0.0","status":500,"error":{"mensagem":"Não foi possível buscar o endereço a partir do cep informado."}}
     *         )
     *     )
     * )
     */
    public function getAllAddressInformationByCep(SearchCepRequest $request)
    {
        try {
            $addressAllReturned = $this->service->getAllData($request->validated('cep'));
            return $this->responseSuccess(RequestStatusCodeEnum::OK->value, $addressAllReturned);
        } catch (Exception $ex) {
            Cache::flush();
            return $this->responseError(RequestStatusCodeEnum::NOT_FOUND->value, $ex->getMessage());
        }
    }
}
