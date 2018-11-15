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