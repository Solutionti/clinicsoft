// =========================================================================
// SISTEMA GLOBAL ANTI-DOBLE CLIC Y LOADING AUTOMÁTICO PARA TODOS LOS BOTONES
// =========================================================================

let botonClickeado = null;

// 1. Detectar el último botón que el usuario presionó en toda la página
// Usamos addEventListener con {capture: true} para interceptar el evento ANTES que los handlers de jQuery
document.addEventListener('click', function(e) {
    // Buscamos el elemento botón o a.btn más cercano al target del evento
    let target = e.target.closest('button, a.btn');
    if (target) {
        botonClickeado = $(target);
    }
}, true);

// 2. Interceptar TODAS las peticiones AJAX justo antes de que salgan al servidor
$(document).ajaxSend(function() {
    // Si hay un botón clickeado y no tiene la clase 'no-loading' (por si quieres excluir alguno)
    if (botonClickeado && !botonClickeado.hasClass('no-loading')) {
        // Guardamos su texto/HTML original en su memoria
        botonClickeado.data('texto-original', botonClickeado.html());
        
        // Lo deshabilitamos y le ponemos el spinner de FontAwesome
        // Nota: para <a> la propiedad disabled no existe nativamente, así que usamos CSS pointer-events
        botonClickeado.prop('disabled', true).addClass('disabled').html('<i class="fas fa-spinner fa-spin me-2"></i> Procesando...');
    }
});

// 3. Interceptar el FINAL de TODAS las peticiones AJAX (Éxito o Error)
$(document).ajaxComplete(function() {
    if (botonClickeado && botonClickeado.data('texto-original')) {
        // Restauramos el botón a su estado original y lo volvemos a habilitar
        botonClickeado.prop('disabled', false).removeClass('disabled').html(botonClickeado.data('texto-original'));
        botonClickeado = null; // Limpiamos la variable para el siguiente clic
    }
});

// 4. (EXTRA) Protección para formularios tradicionales que NO usan AJAX sino recarga de página
$(document).on('submit', 'form', function() {
    let btnSubmit = $(this).find('button[type="submit"]');
    if (btnSubmit.length > 0 && !btnSubmit.hasClass('no-loading')) {
        btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Cargando...');
    }
});