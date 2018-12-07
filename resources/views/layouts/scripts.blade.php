<script>
    //Carga Datatable Oficios
    $(document).ready(function() {
        //Tabla de oficios
        $('#tabla-oficios').DataTable({
            "serverSide": true,
            "ajax": "{{ url("api/oficios", [isset(Auth::user()->username) ? Auth::user()->username : '']) }}",
            "columns": [
                {data: 'id', className: "CentraColumna"},
                {data: 'oficio'},
                {data: 'cerys'},
                {data: 'lotes'},
                {data: 'creado'},
                {data: 'estado', className: "CentraColumna"},
                {data: 'firmado', className: "CentraColumna"},
                {data: 'usuario', "visible": false, "searchable": false },
                {data: 'estadoOficio', "visible": false, "searchable": false },
                {data: 'firmaOficio', "visible": false, "searchable": false },
                {data: 'numCerys', "visible": false, "searchable": false },
                {data: 'botones', className: "CentraColumna"},
            ],
            "stateSave": true,
            "language": {
                "url": '{{ asset('js/Spanish.json') }}'
            },
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "pagingType": "full_numbers"
        });

        //Tabla de usuarios
        $('#tabla-users').DataTable({
            "serverSide": true,
            "ajax": "{{ url("api/users") }}",
            "columns": [
                {data: 'id', className: "CentraColumna"},
                {data: 'rfc'},
                {data: 'nombre'},
                {data: 'correo'},
                {data: 'cerys'},
                {data: 'btn', className: "CentraColumna"},
            ],
            "stateSave": true,
            "language": {
                "url": '{{ asset('js/Spanish.json') }}'
            },
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "pagingType": "full_numbers"
        });

        //Tabla de lotes
        $('#tabla-lotesFirmar').DataTable({
            "stateSave": true,
            "language": {
                "url": '{{ asset('js/Spanish.json') }}'
            },
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "pagingType": "full_numbers"
        });

        //Tabla de lotes
        $('#tabla-lotes').DataTable({
            "stateSave": true,
            "language": {
                "url": '{{ asset('js/Spanish.json') }}'
            },
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "pagingType": "full_numbers"
        });

        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
        });

        $("#divMotivo").hide();
    });

    //Valida si se selecciono un cerys para ver los oficios
    $('#btn-cerys').click(function() {
        let cerys = $('select[id=cerys]').val();
        if(cerys===''){
            alertify.alert("<center><h3>Seleccione un Cerys.</h3></center><br>");
            return false;
        }
    });

    //Función para actualizar DataTable
    let refreshTable = function(){
        $('.dataTable').each(function() {
            let dt = $(this).dataTable();
            dt.fnDraw();
        })
    };

    //Procesa la solicitud de aceptación de oficio en el Cerys
    let aceptar = function(id, of, cer){
        let token   = $('#token').val();
        let url     = route('statusOficio');
        let param   = {
            '_token': token,
            'id': id,
            'tipo': 1,
            'comment': '',
            'oficio': of
        };
        alertify.confirm("<center><h3>¿Confirma la aceptación del oficio: <b>"+ of +"</b> del Cerys: <b>" + cer + "</b>?</h3></center><br>", function (e) {
            if (e) {
                $.ajax({
                    url:        url,
                    data:       param,
                    type:       'post',
                    dataType:   'json',
                    success: function(result){
                        if(result.valor === "OK"){
                            let url  = 'home';
                            $.get(url, function(){
                                refreshTable();
                            }).fail(function(){
                                alertify.alert("<center><h3>Ocurrio un error al rechazar el oficio.</h3></center><br>");
                                return false;
                            });
                        }else if(result.valor === "ER"){
                            alertify.alert("<center><h3>"+result.msg+"</h3></center><br>");
                            return false;
                        }else{
                            alertify.alert("<center><h3>Ocurrio un error al aceptar el oficio.</h3></center><br>");
                            return false;
                        }
                    }
                });
            } else {
                alertify.error("<h3>Se cancelo la operación</h3>");
                return false;
            }
        });
    };

    //Envia valores a ventana modal
    let rechazar = function(id){
        $('#idMotivo').val(id);
        $('#motivo').val('');
    };

    //Detecta los cambios de estado  de la credencial
    $('#estadoCred').on('change', function() {
        let estado = $('#estadoCred').val();
        if(estado == 1){
            $("#motivoCred").val('');
            $("#divMotivo").hide();
        }else{
            $("#divMotivo").show();
        }
    });

    //Envia valores a ventana modal
    let editarCredencial = function(id){
        let url = route('obtenerDatosEmpleado', id);
        $.get(url, function(response){
            if(response.valor === "OK") {
                $('#idCred').val(id);
                $('#NumEmp').val(response.numEmp);
                $('#NomEmp').val(response.nombre);
                $("#estadoCred").val(response.estado);
                $("#motivoCred").val(response.motivo);
                if(response.estado == 0){
                    $("#divMotivo").show();
                }else{
                    $("#motivoCred").val('');
                    $("#divMotivo").hide();
                }
            }else if(response.valor === "ER"){
                cerrarModal('editarRechazar');
                alertify.alert("<center><h3>"+response.msg+"</h3></center><br>");
                return false;
            }
        }).fail(function(){
            alertify.alert("<center><h3>Ocurrio un error.</h3></center><br>");
            return false;
        });
    };

    $('#btn-acepta-credencial').click(function() {
        //Obtenemos los valores marcados en el formulario modal
        let id      = $("#idCred").val();
        let estado  = $("#estadoCred").val();
        let motivo  = $("#motivoCred").val();
        if(estado == 0 && (motivo == '' || motivo == null)){
            $('#motivoCred').focus();
            $('#motivoCred').effect('highlight', {color:'#ff0000'}, 6000);
            alertify.error("<h4>El motivo no puede ser nulo</h4>");
            return false;
        }
        let token   = $('#token').val();
        let url     = route('guardaEstado');
        let param   = {
            '_token': token,
            'id': id,
            'estado': estado,
            'motivo': motivo,
        };
        alertify.confirm("<center><h3>¿Confirma el cambio de estado?</h3></center><br>", function (e) {
            if (e) {
                $.ajax({
                    url:        url,
                    data:       param,
                    type:       'post',
                    dataType:   'json',
                    beforeSend: function () {
                        cerrarModal('editarRechazar');
                    },
                    success: function(result){
                        if(result.valor === "OK"){
                            location.reload();
                        }else if(result.valor === "ER"){
                            alertify.alert("<center><h3>"+result.msg+"</h3></center><br>");
                            return false;
                        }else{
                            alertify.alert("<center><h3>Ocurrio un error al cambiar el estado.</h3></center><br>");
                            return false;
                        }
                    }
                });
            } else {
                cerrarModal('editarRechazar');
                alertify.error("<h3>Se cancelo la operación</h3>");
                return false;
            }
        });
    });

    //Funcion para cerrar modal, recibe nombre del modal
    let cerrarModal = function(nameModal){
        let nombre = '#' + nameModal;
        //ocultamos el modal
        $(nombre).modal('hide');
        //eliminamos la clase del body para poder hacer scroll
        $('body').removeClass('modal-open');
        //eliminamos el backdrop del modal
        $('.modal-backdrop').remove();
    };

    //Envia mensaje de cancelacion de operacion
    $('#btn-cancela').click(function() {
        alertify.error("<h3>Se cancelo la operación</h3>");
    });

    //Evento click del boton aceptar del modal de rechazar oficio
    $('#btn-acepta').click(function() {
        let motivo = $('#motivo').val();
        let id = $('#idMotivo').val();
        if(motivo === '' || motivo === null){
            $('#motivo').focus();
            $('#motivo').effect('highlight', {color:'#ff0000'}, 6000);
            alertify.error("<h4>El motivo no puede ser nulo</h4>");
            return false;
        }else if(motivo.length < 10){
            $('#motivo').focus();
            $('#motivo').effect('highlight', {color:'#ff0000'}, 6000);
            alertify.error("<h4>La descripción del motivo es muy corta</h4>");
            return false;
        }else{
            let token   = $('#token').val();
            let url     = route('statusOficio');
            let param   = {
                '_token': token,
                'id': id,
                'tipo': 2,
                'comment': motivo,
            };
            alertify.confirm("<center><h3>¿Confirma el rechazo del oficio?</h3></center><br>", function (e) {
                if (e) {
                    $.ajax({
                        url:        url,
                        data:       param,
                        type:       'post',
                        dataType:   'json',
                        beforeSend: function () {
                            cerrarModal('rechazar');
                        },
                        success: function(result){
                            if(result.valor === "OK"){
                                let url  = 'home';
                                $.get(url, function(response){
                                    refreshTable();
                                }).fail(function(){
                                    alertify.alert("<center><h3>Ocurrio un error al rechazar el oficio.</h3></center><br>");
                                    return false;
                                });
                            }else if(result.valor === "ER"){
                                alertify.alert("<center><h3>"+result.msg+"</h3></center><br>");
                                return false;
                            }else{
                                alertify.alert("<center><h3>Ocurrio un error al rechazar el oficio.</h3></center><br>");
                                return false;
                            }
                        }
                    });
                } else {
                    cerrarModal('rechazar');
                    alertify.error("<h3>Se cancelo la operación</h3>");
                    return false;
                }
            });
        }
    });

    let borrarUsuario = function (id) {
        let token   = $('#token').val();
        let url     = route('borraUsuario');
        let param   = {
            '_token': token,
            'id': id,
        };
        alertify.confirm("<center><h3>¿Confirma la eliminación del usuario?</h3></center><br>", function (e) {
            if (e) {
                $.ajax({
                    url:        url,
                    data:       param,
                    type:       'post',
                    dataType:   'json',
                    success: function(result){
                        if(result.valor === "OK"){
                            refreshTable();
                        }else if(result.valor === "ER"){
                            alertify.alert("<center><h3>"+result.msg+"</h3></center><br>");
                            return false;
                        }else{
                            alertify.alert("<center><h3>Ocurrio un error al borrar el usuario.</h3></center><br>");
                            return false;
                        }
                    }
                });
            } else {
                alertify.error("<h3>Se cancelo la operación</h3>");
                return false;
            }
        });
    };
</script>