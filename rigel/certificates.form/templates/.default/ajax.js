function ajaxSendCertificate(action, form) {
    if (!form) return;
    serializeData = BX.ajax.prepareForm(form).data;
    serializeData.action = action;

    console.log(serializeData);
    BX.ajax({
        url: '/local/components/intravision/certificates.form/ajax.php',
        data: serializeData,
        method: 'POST',
        dataType: 'html',
        timeout: 30,
        processData: false,
        scriptsRunFirst: true,
        emulateOnload: true,
        start: true,
        cache: false,
        onsuccess: function(data) {
            BX.cleanNode('bx-certicates-form');
            BX.adjust( BX('bx-certicates-form'), { html: data } );
        },
        onfailure: function() {
            BX.cleanNode('bx-certicates-form');
            BX('bx-certicates-form').append('<h1>Операция провалилась</h1>');
        }
    });
}

BX.ready(function() {
    BX.bindDelegate(
        BX('bx-certicates-form'), 'submit', { tagName: 'form' },
        function(e) {
            let form = BX(e.target.id);
            if (e.target.id == 'bx-certificateForm')
                ajaxSendCertificate('sendCertificate', form);
            else if (e.target.id == 'bx-certificateFormActivate')
                ajaxSendCertificate('sendCertificateAction', form);
            return BX.PreventDefault(e);
        }
    );
});
