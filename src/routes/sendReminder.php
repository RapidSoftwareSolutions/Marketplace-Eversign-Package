<?php

$app->post('/api/Eversign/sendReminder', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['accessKey', 'businessId', 'documentHash', 'signerId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/send_reminder";

    $params = [
        'access_key' => $postData['args']['accessKey'],
        'business_id' => $postData['args']['businessId']
    ];

    $json = [
        'document_hash' => $postData['args']['documentHash'],
        'signer_id' => $postData['args']['signerId']
    ];

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->post($url, [
            'query' => $params,
            'json' => $json
        ]);
        $responseResponseJSON = json_decode($vendorResponse->getBody(), true);
        if (isset($responseResponseJSON['success']) && filter_var($responseResponseJSON['success'], FILTER_VALIDATE_BOOLEAN) == true) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = $responseResponseJSON;
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = $responseResponseJSON;
        }
    } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
        $vendorResponseBody = $exception->getResponse()->getBody();
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = json_decode($vendorResponseBody);
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
