<div class="modal fade" id="editarRechazar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>×</span>
                </button>
                <h4>Aceptar o rechazar credencial</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input id="idCred" type="hidden" name="idCred">
                    <input id="loteCred" type="hidden" name="loteCred" value="{{ $listaLotes }}">
                    <input id="oficioCred" type="hidden" name="oficioCred" value="{{ $oficio }}">
                    <input id="cerysCred" type="hidden" name="cerysCred" value="{{ $cerys }}">
                    <div class="form-group input-group">
                        <span class="input-group-addon" style="width:150px;">#Empleado:</span>
                        <input type="text" style="width:350px;" class="form-control" id="NumEmp" readonly="readonly">
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon" style="width:150px;">Nombre:</span>
                        <input type="text" style="width:350px;" class="form-control" id="NomEmp" readonly="readonly">
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon" style="width:150px;">Estado:</span>
                        <select class="form-control" name="estadoCred" id="estadoCred" required style="width:350px">
                            @foreach($estado as $listas)
                                <option value="{{ $listas->Id }}">{{ $listas->Estado}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group input-group" id="divMotivo">
                        <span class="input-group-addon" style="width:150px;">Motivo:</span>
                        <select class="form-control" name="motivoCred" id="motivoCred" style="width:350px">
                            <option value="" />Seleccione un motivo</option>
                            @foreach($motivo as $motivos)
                                <option value="{{ $motivos->Id }}">{{ $motivos->Nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input id="btn-acepta-credencial" type="submit" class="btn btn-primary" value="Guardar">
                <input id="btn-cancela-credencial" type="submit" class="btn btn-danger" value="Cancelar" data-dismiss="modal">
            </div>
        </div>
    </div>
</div>