// Validación del formulario de egresados
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        let valid = true;
        let mensajesError = [];
        
        // Validar DNI
        const dni = form.querySelector('input[name="dni"]');
        if (dni && !/^\d{8}$/.test(dni.value)) {
            mensajesError.push('El DNI debe tener exactamente 8 dígitos');
            valid = false;
        }
        
        // Validar años básicos
        const añoIngreso = document.getElementById('año_ingreso');
        const añoEgreso = document.getElementById('año_egreso');
        if (añoIngreso && añoEgreso && añoIngreso.value && añoEgreso.value) {
            const ingreso = parseInt(añoIngreso.value);
            const egreso = parseInt(añoEgreso.value);
            const añoActual = new Date().getFullYear();
            
            if (egreso < ingreso) {
                mensajesError.push('El año de egreso no puede ser menor al año de ingreso');
                valid = false;
            } else if (egreso > añoActual) {
                mensajesError.push(`El año de egreso no puede ser mayor al año actual (${añoActual})`);
                valid = false;
            }
        }
        
        if (!valid) {
            e.preventDefault();
            alert('Por favor corrija los siguientes errores:\n\n' + mensajesError.join('\n'));
        }
    });
    
    // Auto-eliminar mensajes de éxito después de 8 segundos
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(alert => {
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            alert.style.transition = 'all 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 8000);
    });
    
    // Auto-eliminar mensajes de error después de 10 segundos
    const errorAlerts = document.querySelectorAll('.alert-error');
    errorAlerts.forEach(alert => {
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            alert.style.transition = 'all 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 10000);
    });
});

