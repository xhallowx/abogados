// js/scripts.js
$(document).ready(function(){
    $('form').on('submit', function(e){
        if ($(this).find('button[name="eliminar"]').length) {
            if (!confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
                e.preventDefault();
            }
        }
    });
});
