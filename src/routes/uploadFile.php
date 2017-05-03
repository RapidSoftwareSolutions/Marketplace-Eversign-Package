<?php

$app->post('/api/Eversign/uploadFile', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['accessKey', 'businessId', 'file']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/file";

    $file = file_get_contents($postData['args']['file']);

    $params = [
        'access_key' => $postData['args']['accessKey'],
        'business_id' => $postData['args']['businessId']
    ];

    if ($file) {
        try {
            /** @var GuzzleHttp\Client $client */
            $client = $this->httpClient;
            $vendorResponse = $client->post($url, [
                'query' => $params,
                'multipart' => [
                    [
                        'name' => 'upload',
                        'filename' => 'test.pdf',
                        'contents' => $file
                    ]
                ]
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
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Cant download file';
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
