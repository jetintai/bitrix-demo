BX.ready(function () {
    function
    BX.bindDelegate(
        document.body, 'click', {className: 'js-vote--up'},
        function (e) {
            pr = BX.findParent(this, {"class": "highvote-form"});
            pr.submit();
        });

    BX.bindDelegate(
        document.body, 'click', {className: 'js-vote--down'},
        function (e) {
            pr = BX.findParent(this, {"class": "highvote-form"});
            pr.submit();
        });

});