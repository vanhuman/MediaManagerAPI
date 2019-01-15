<?php

namespace Controllers;

use Slim\Http\Response;

class MessageController
{
    /**
     * Generic error messaging.
     * @param Response $response
     * @param \Exception $e
     * @return Response
     */
    public function showError(Response $response, \Exception $exeption)
    {
        $code = $exeption->getCode();
        try {
            $response->withStatus($code);
        } catch (\Exception $e) {
            $code = 500;
        }
        $reference = explode('/', $exeption->getFile());
        $reference = explode('.', end($reference));
        $reference = current($reference);
        $returnedError = [
            'message' => $exeption->getMessage(),
            'type' => 'ERROR',
            'reference' => $reference,
            'status' => $exeption->getCode(),
        ];
        return $response->withJson($returnedError, $code);
    }

    /**
     * @param Response $response
     * @param string $message
     * @return Response
     */
    public function showMessage(Response $response, string $message)
    {
        $returnedMessage = [
            'message' => $message,
            'type' => 'INFORMATION',
        ];
        return $response->withJson($returnedMessage, 200);
    }
}