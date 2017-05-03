<?php
namespace Tests;

require_once(__DIR__ . '/../src/Models/checkRequest.php');

class EversingTest extends BaseTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testRoutes($url) {
        $response = $this->runApp("POST", '/api/Eversign/'.$url);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function dataProvider() {
        return [
            ['getBusinesses'],
            ['getDocuments'],
            ['getSingleDocument'],
            ['createDocument'],
            ['cancelDocument'],
            ['deleteDocument'],
            ['downloadOriginalPDF'],
            ['downloadFinalPDF'],
            ['uploadFile'],
            ['sendReminder'],
            ['useTemplate']
        ];
    }
}