/*BX.ready(function() {
    BX.bindDelegate(
        BX('bx-certicates-form'), 'click', { tagName: 'a' },
        function(e) {
            if (e.target.id == 'bx-certificate-begin') {
                BX.setCookie('BITRIX_SM_CERTIFICATES_FORM_CODE', 'a', {path: '/intravision/'});
                BX.setCookie('BITRIX_SM_CERTIFICATES_FORM_STEP', 'a', {path: '/intravision/'});
                location.reload();
            }
            return BX.PreventDefault(e);
        }
    );
});
