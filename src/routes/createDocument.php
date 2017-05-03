<?php

$app->post('/api/Eversign/createDocument', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['accessKey', 'businessId', 'files', 'signers']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/document";

    $params = [
        'access_key' => $postData['args']['accessKey'],
        'business_id' => $postData['args']['businessId'],
    ];

    $jsonErrors = [];

    $json['files'] = json_decode($postData['args']['files'], true);
    if (json_last_error()) {
        $jsonErrors[] = 'files';
    }
    $json['signers'] = json_decode($postData['args']['signers'], true);
    if (json_last_error()) {
        $jsonErrors[] = 'signers';
    }

    if (isset($postData['args']['isDraft']) && filter_var($postData['args']['isDraft'], FILTER_VALIDATE_BOOLEAN) == true) {
        $json['isDraft'] = 1;
    }
    if (isset($postData['args']['title']) && strlen($postData['args']['title']) > 0) {
        $json['title'] = $postData['args']['title'];
    }
    if (isset($postData['args']['message']) && strlen($postData['args']['message']) > 0) {
        $json['message'] = $postData['args']['message'];
    }
    if (isset($postData['args']['useSignerOrder']) && filter_var($postData['args']['useSignerOrder'], FILTER_VALIDATE_BOOLEAN) == true) {
        $json['useSignerOrder'] = 1;
    }
    if (isset($postData['args']['reminders']) && filter_var($postData['args']['reminders'], FILTER_VALIDATE_BOOLEAN) == true) {
        $json['reminders'] = 1;
    }
    if (isset($postData['args']['requireAllSigners']) && filter_var($postData['args']['requireAllSigners'], FILTER_VALIDATE_BOOLEAN) == true) {
        $json['requireAllSigners'] = 1;
    }
    if (isset($postData['args']['redirect']) && strlen($postData['args']['redirect']) > 0) {
        $json['redirect'] = $postData['args']['redirect'];
    }
    if (isset($postData['args']['client']) && strlen($postData['args']['client']) > 0) {
        $json['client'] = $postData['args']['client'];
    }
    if (isset($postData['args']['expires']) && strlen($postData['args']['expires']) > 0) {
        $json['expires'] = $postData['args']['expires'];
    }
    if (isset($postData['args']['recipients'])) {
        $json['recipients'] = json_decode($postData['args']['recipients'], true);
        if (json_last_error()) {
            $jsonErrors[] = 'recipients';
        }
    }
    if (isset($postData['args']['meta'])) {
        $json['meta'] = json_decode($postData['args']['meta'], true);
        if (json_last_error()) {
            $jsonErrors[] = 'meta';
        }
    }
    if (isset($postData['args']['fields'])) {
        $json['fields'] = json_decode($postData['args']['fields'], true);
        if (json_last_error()) {
            $jsonErrors[] = 'fields';
        }
    }

    if (empty($jsonErrors)) {
        try {
            /** @var GuzzleHttp\Client $client */
            $client = $this->httpClient;
            $vendorResponse = $client->post($url, [
                'query' => $params,
                'json' => $json
            ]);
            $vendorResponseBody = $vendorResponse->getBody()->getContents();
            if ($vendorResponse->getStatusCode() == 200) {
                $responseResponseJSON = json_decode($vendorResponse->getBody(), true);
                if (!isset($responseResponseJSON['success'])) {
                    $result['callback'] = 'success';
                    $result['contextWrites']['to'] = $responseResponseJSON;
                } else {
                    $result['callback'] = 'error';
                    $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                    $result['contextWrites']['to']['status_msg'] = $responseResponseJSON;
                }
            } else {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                $result['contextWrites']['to']['status_msg'] = is_array($vendorResponseBody) ? $vendorResponseBody : json_decode($vendorResponseBody);
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
            $vendorResponseBody = $exception->getResponse()->getBody();
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = json_decode($vendorResponseBody);
        }
    }
    else {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'JSON_VALIDATION';
        $result['contextWrites']['to']['status_msg'] = $jsonErrors;
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
