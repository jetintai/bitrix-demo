BX.ready(function () {
    BX.bindDelegate(
        document.body, 'click', {className: 'js-user-form--filter'},
        function (e) {
            let form = BX.findChildrenByClassName(document, 'js-user-form', true)[0];
            let container = BX.findChildrenByClassName(document, 'js-user-form--content', true)[0];
            let strSerialized = BX.ajax.prepareForm(form).data;
            console.log(strSerialized);
            BX.ajax.runComponentAction('rigel:simpleuser', 'filter', {
                    mode: 'class',
                    data: {post: strSerialized},
                })
                .then(function(response) {
                    if (response.status === 'success') {
                        let fields = ['FULL_NAME', 'PERSONAL_BIRTHDAY', 'LOGIN', 'EMAIL'];
                        BX.cleanNode(container);
                        console.log(response.data);
                        if (response.data) {
                            response.data.map((element) => {
                                let columns = new Array();
                                fields.forEach((field) => {
                                    columns.push(BX.create('td', {
                                        'text': element[field],
                                    }));
                                })
                                BX.append(BX.create('tr', {
                                    children: columns,
                                }), container);
                            })

                        }
                    }
                }, function (response) {
                    console.log('error');
                    console.log(response.error);
                });
        });
});