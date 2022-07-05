<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Context;
use Bitrix\Main\HttpResponse;

$request = Context::getCurrent()->getRequest();

if ($request['action'] == 'sendCertificate') {
    $APPLICATION->IncludeComponent(
        "intravision:certificates.form", "", Array(
            'AJAX_MODE' => 'Y',
        )
    );
} else if ($request['action'] == 'sendCertificateAction' ) {
    $APPLICATION->IncludeComponent(
        "intravision:certificates.form", "", Array(
            'AJAX_MODE' => 'Y',
        )
    );
}

$response = Context::getCurrent()->getResponse();
$response->writeHeaders("");