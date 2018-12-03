<div class="modal fade" id="rechazar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>×</span>
                </button>
                <h4>Rechazar Oficio</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input id="idMotivo" type="hidden" name="idMotivo">
                    <label for="motivo" class="">Motivo</label>
                    <textarea name="motivo" id="motivo" class="form-control" cols="20" rows="5" placeholder="Indique el motivo de rechazo"
                    minlength="10" maxlength="100" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <input id="btn-acepta" type="submit" class="btn btn-primary" value="Guardar">
                <input id="btn-cancela" type="submit" class="btn btn-danger" value="Cancelar" data-dismiss="modal">
            </div>
        </div>
    </div>
</div>