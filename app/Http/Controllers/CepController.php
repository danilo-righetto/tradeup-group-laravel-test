<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ViaCepService;
use App\Traits\ResponseUtils;
use App\Enums\RequestStatusCodeEnum;
use App\Http\Requests\SearchCepRequest;
use Exception;
use Illuminate\Http\Request;

class CepController extends Controller
{
    use ResponseUtils;

    public function __construct(
        private ViaCepService $service
    ) {}

    /**
     * Search for address information using zip code
     */
    public function getAddressByCep(SearchCepRequest $request)
    {
        try {
            $addressReturned = $this->service->getAddress($request->validated('cep'));
            return $this->responseSuccess(RequestStatusCodeEnum::OK->value, $addressReturned);
        } catch (Exception $ex) {
            return $this->responseError(RequestStatusCodeEnum::INTERNAL_SERVER_ERROR->value, $ex->getMessage());
        }
    }

    public function getAllAddressInformationByCep(SearchCepRequest $request)
    {
        try {
            $addressAllReturned = $this->service->getAllData($request->validated('cep'));
            return $this->responseSuccess(RequestStatusCodeEnum::OK->value, $addressAllReturned);
        } catch (Exception $ex) {
            return $this->responseError(RequestStatusCodeEnum::INTERNAL_SERVER_ERROR->value, $ex->getMessage());
        }
    }
}
