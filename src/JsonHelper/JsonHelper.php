<?php

namespace App\JsonHelper;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonHelper
{
    /**
     * @param array<string,mixed> $data
     */
    public function getJsonPrettyPrint(array $data): JsonResponse
    {
        $responseJson = new JsonResponse($data);
        $responseJson->setEncodingOptions(
            $responseJson->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $responseJson;
    }
}
