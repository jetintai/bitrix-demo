<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/*#TODO

*/

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Web\Cookie;
use Bitrix\Main\HttpResponse;
use Bitrix\Main\Server;
use Bitrix\Main\Entity;
\Bitrix\Main\Loader::includeModule('iblock');

class CertificatesForm {

    const CERTIFICATE_IBLOCK_ID = 16;

    public function __construct(CBitrixComponent $bxComponent)
    {
        $this->bxComponent = $bxComponent;
        $this->arResult =& $this->bxComponent->arResult;
        $this->arParams =& $this->bxComponent->arParams;

        $this->context = Context::getCurrent();
        $this->request = $this->context->getRequest();
        $this->formStep = $this->getCookie('CERTIFICATES_FORM_STEP');

        if (empty( $this->formStep )) {
            $this->formStep = 1;
            $this->setCookie('CERTIFICATES_FORM_STEP', $this->formStep);
        }

        $this->prepareCaptcha();
    }

    public function run() {
        try {
            $this->formStepController();
            echo "<div id = 'bx-certicates-form'>";
            $this->formTemplateController();
            echo "</div>";
        } catch (Exception $e) {
            $this->arResult['Exception'] = $e;
            $this->bxComponent->IncludeComponentTemplate('error');
        }
    }

    protected function formTemplateController() {
        if ($this->formStep == 1)
            $templateFile = 'form-first';
        else if ($this->formStep == 2)
            $templateFile = 'form-second';
        else if ($this->formStep == 3)
            $templateFile = 'report';


        $this->bxComponent->IncludeComponentTemplate($templateFile);

        if ( $this->arParams["AJAX_MODE"] == "Y" ) {
            $template = & $this->bxComponent->GetTemplate();
            $templateFolder = $template->GetFolder();
            $template->addExternalJS( $templateFolder . "/ajax.js");
        }
    }

    protected function formStepController() {
        if (($this->formStep == 1) && $this->isCaptchaPassed()) {
            if (!$this->request['InputEmail'] || !$this->request['InputCertificateCode']) {
                $this->arResult['NOTICE'] = 'Введены не все данные';
                return;
            }

            $confirmCode = $this->getConfirmCode();
            $b = $this->setCertificateOwner($confirmCode);
            $b &= $this->sendConfirmMessage($confirmCode);
            if ($b) {
                $this->formStep = 2;
                $this->setCookie('CERTIFICATES_FORM_CODE', $this->request['InputCertificateCode']);
                $this->setCookie('CERTIFICATES_FORM_STEP', $this->formStep);
            }
        } else if (($this->formStep == 2) && $this->isConfirmCodePassed()) {
            if ($this->activateCertificate()) {
                $this->formStep = 3;
                $this->setCookie('CERTIFICATES_FORM_STEP', $this->formStep);

                $code = $this->getCookie('CERTIFICATES_FORM_CODE');
                if (!$code) {
                    $this->arResult['NOTICE'] = 'ЧТо-то пошло не так пройдите процедуру активации занаво';
                    return;
                }
                $this->arResult['CERTIFICATE'] = $this->getCertificateByCode($code);
            }
        }
    }

    protected function sendConfirmMessage($confirmCode) {
        $arFields = Array(
            "EVENT_NAME" => "INTRAVISION_CERTIFICATES",
            "MESSAGE_ID" => 51,
            "LID" => "s1",
            "C_FIELDS" => Array (
                "CONFIRM_CODE" => $confirmCode,
                "EMAIL_TO" => $this->request['InputEmail'],
            ),
        );
        $res = \Bitrix\Main\Mail\Event::send( $arFields );
        if (!$res->getId()) {
            $this->arResult['NOTICE'] = 'Сообщение отправить не удалось';
            return;
        }

        return true;
    }

    protected function activateCertificate() {
        $code = $this->getCookie('CERTIFICATES_FORM_CODE');
        if (!$code) return;
        $arCert = Array (
            'CERT_ACTIVE' => 1,
            'CONFIRM_CODE' => '',
        );

        if (!$this->updateCertificateByCode($code, $arCert)) {
            $this->arResult['NOTICE'] = 'Активировать сертификат не удалось';
            return;
        }

        return true;
    }

    protected function setCertificateOwner($confirmCode) {
        $code = $this->request['InputCertificateCode'];
        if (!$code) {
            $this->arResult['NOTICE'] = 'неправильно введен номер сертификата';
            return;
        }
        $arCertOwner = Array (
            'CERT_EMAIL' => $this->request['InputEmail'],
            'CONFIRM_CODE' => $confirmCode,
        );

        $res = $this->updateCertificateByCode($code, $arCertOwner);
        if (!$res) {
            $this->arResult['NOTICE'] = 'Ну удалось обновить владельца сертификта, или сертификат введен неверно';
            return;
        }

        return true;
    }

    protected function prepareCaptcha() {
        global $APPLICATION;
        $this->arResult["CAPTCHA_CODE"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
    }

    protected function isConfirmCodePassed() {
        $code = $this->getCookie('CERTIFICATES_FORM_CODE');
        $confirmCode = $this->request['InputConfirmCode'];
        if (!$code) {
            $this->arResult['NOTICE'] = 'ЧТо-то пошло не так пройдите процедуру активации занаво';
            return;
        }

        $certificate = $this->getCertificateByCode($code);
        $res = $certificate['CONFIRM_CODE_VALUE'] == $confirmCode;
        if (!$res) {
            $this->arResult['NOTICE'] = 'Вы ввели не правильный проверочный код';
            return;
        }

        return $res;
    }

    protected function isCaptchaPassed() {
        global $APPLICATION;
        $res = $APPLICATION->CaptchaCheckCode(
            $this->request["captcha_word"],
            $this->request["captcha_code"]
        );

        if (!$res) {
            $this->arResult['NOTICE'] = 'Неправильная капча';
            return;
        }

        return true;
    }

    protected function updateCertificateByCode($code, $arFields) {
        $certificate = $this->getCertificateByCode($code);
        if (!$certificate) return;

        $b = true;
        foreach ($arFields as $key => $value)
            $b &= CIBlockElement::SetPropertyValueCode($certificate['ID'], $key, $value);

        return $b;
    }

    protected function getCertificateByCode($code) {
        if (empty($code)) return;

        return  \Bitrix\Iblock\ElementTable::getList(array(
            'select' => array(
                'ID', 'NAME', 'CODE', 'ACTIVE', 'ACTIVE_FROM', 'ACTIVE_TO',
                'CERT_EMAIL_' => 'CERT_EMAIL', 'CERT_ACTIVE_' => 'CERT_ACTIVE', 'CONFIRM_CODE_' => 'CONFIRM_CODE'),
            'filter' => array(
                'IBLOCK_ID' => $this::CERTIFICATE_IBLOCK_ID,
                'CODE' => $code,
            ),
        ))->fetch();
    }

    protected function getCookie($name) {
        return $this->request->getCookie($name);
    }

    protected function setCookie($name, $value) {
        $cookie = new Cookie($name, $value, time() + 60*60*24);
        $cookie->setSecure(false);
        $cookie->setDomain($this->context->getServer()->getServerName());
        //$cookie->setPath( "/");
        $response = $this->context->getResponse();
        $response->addCookie($cookie);

        return true;
    }

    protected function getConfirmCode() {
        return \Bitrix\Main\Security\Random::getString(6, true);
    }
}


$component = new CertificatesForm($this);
$component->run();