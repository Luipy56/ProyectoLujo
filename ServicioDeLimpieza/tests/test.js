$(document).ready(function(){
    $('#enviarBtn').click(function(){
        var dato = $('#datoInput').val();

        $.ajax({
            url: 'test.php',
            method: 'POST',
            data: {dato: dato},
            success: function(response){
               console.log(response);
		$('#datoProcesado').text(response);

            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            }
        });
    });
});
