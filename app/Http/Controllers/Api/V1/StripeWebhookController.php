<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\StripeHookRequest;
use App\Services\StripeHooksService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeWebhookController extends ApiController
{

    public function __construct(
        protected StripeHooksService $stripeHooksService
    ) {}


    /**
     * @param StripeHookRequest $request 
     * @return JsonResponse 
     */
    public function handleHookRequest(StripeHookRequest $request): JsonResponse
    {
        $data = $request->safe()->all();

        try {
            return $this->formatResponse([
                'status' => $this->stripeHooksService->handle($data, $data[StripeHookRequest::REQUEST_TYPE])
            ]);
        } catch (Exception $exception) {
            return $this->formatResponse([
                'status' => false,
                'message' => 'An error has occurred. ' . $exception->getMessage()
            ], $exception->getCode());
        }
    }
}
