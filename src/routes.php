<?php
$routes = [
    'metadata',
    'getBusinesses',
    'getDocuments',
    'getSingleDocument',
    'createDocument',
    'cancelDocument',
    'deleteDocument',
    'downloadOriginalPDF',
    'downloadFinalPDF',
    'uploadFile',
    'sendReminder',
    'useTemplate'
];
foreach($routes as $file) {
    require __DIR__ . '/../src/routes/'.$file.'.php';
}

