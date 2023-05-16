<?php

namespace ChezRD\Jivochat\Webhooks;

/**
 * Class Response
 *
 * @author Oleg Fedorov <olegf39@gmail.com>
 * @author Evgeny Rumiantsev <chezrd@gmail.com>
 * @package ChezRD\Jivochat\Webhooks\Response
 */
class Response
{
     /**
     * @var string JSON representation of Callback response data.
     */
    protected $response;

    /**
     * Returns Jivochat Webhook response string.
     *
     * @return string Webhook response JSON string.
     * @throws \RuntimeException
     */
    public function getResponse(): string {
        $this->buildResponse();

        return $this->response;
    }

    /**
     * Builds response data JSON string.
     *
     * @throws \RuntimeException in case if error occurs during encoding response to JSON.
     */
    protected function buildResponse($extended_data = []) {
        $response = array_merge(['result' => 'ok'], $extended_data);

        $encodedResponse = json_encode($response, JSON_UNESCAPED_UNICODE);

        if (false === $encodedResponse) {
            $errorCode = json_last_error();
            $errorMsg = json_last_error_msg();
            $responseExport = var_export($response, true);
            $message = <<<MSG
An error occurred on encoding Response to JSON!
Error code - #{$errorCode}, message: "{$errorMsg}".
Export of the response content:
{$responseExport}
MSG;
            throw new \RuntimeException($message);
        }

        $this->response = $encodedResponse;
    }
}