alertify.set({ labels: {
    ok     : "Aceptar",
    cancel : "Cancelar"
} });

$('#btn-cerys').click(function(event) {
    var cerys = $('select[id=cerys]').val();
    if(cerys===''){
        alertify.alert("<center><h3>Seleccione un Cerys.</h3></center><br>");
        return false;
    }
});

/*$('#btn-send').on('click', function(event) {
        event.preventDefault();
        $.ajaxSetup({headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content')}  })
        var firma = '1zzzAAAAAAAAA111224545454544ssdsdds==';
        var sello = '2aaaaaZZZZZZZZZ222222222223234wssddsdsdsdsdssd==';
        var nombre = 'Alexis Torres';
        var oficio = 'OF-020';
        var rfcSession = 'TOCA790826UU6';
        var url = route('sello');

        var param = {
                      '_token': $('#token').val(),
                      'nombre': nombre,
                      'firma':  firma,
                      'sello':  sello,
                      'oficio': oficio,
                      'rfc':    rfcSession
                    };

        $.ajax({
            url     :  url,
            type    :  'post',
            dataType:  'json',
            data    :   param,
            success :   function (result) {
                alert(result.dato);
            },
            error   :   function() {
                alert('error');
            }
        });
    });*/