// ============================================
// VALIDACIONES PARA EL SISTEMA DE GESTIÓN RRHH
// VERSIÓN CORREGIDA - TODOS LOS CAMPOS VALIDADOS
// ============================================

/**
 * Función principal para validar un formulario completo
 * @param {string} formId - ID del formulario a validar
 * @param {Array} reglas - Array de reglas de validación
 * @returns {boolean} - true si es válido, false si no
 */
function validarFormulario(formId, reglas) {
    const form = document.getElementById(formId);
    if (!form) return false;

    let esValido = true;
    let errores = [];

    // Limpiar errores previos
    limpiarErrores(form);

    reglas.forEach(regla => {
        const campo = form.querySelector(`[name="${regla.campo}"]`);
        if (!campo) return;

        const valor = campo.value.trim();
        const errorElement = form.querySelector(`.error-${regla.campo}`) || crearErrorElement(campo);

        // Validar según el tipo de campo
        let valido = true;
        let mensaje = '';

        // VALIDACIÓN DE SELECT (cuando tiene opción vacía)
        if (campo.tagName === 'SELECT' && regla.obligatorio) {
            if (!valor || valor === '') {
                valido = false;
                mensaje = 'Seleccione una opción válida.';
            }
        }
        // VALIDACIÓN DE CHECKBOX (grupo)
        else if (campo.type === 'checkbox' && regla.obligatorio) {
            const grupo = form.querySelectorAll(`[name="${regla.campo}"]`);
            const algunSeleccionado = Array.from(grupo).some(cb => cb.checked);
            if (!algunSeleccionado) {
                valido = false;
                mensaje = 'Seleccione al menos una opción.';
            }
        }
        // VALIDACIONES ESTÁNDAR
        else if (regla.obligatorio && !valor) {
            valido = false;
            mensaje = regla.mensaje || 'Este campo es obligatorio.';
        } else if (regla.tipo === 'email' && valor && !validarEmail(valor)) {
            valido = false;
            mensaje = 'Ingrese un correo electrónico válido.';
        } else if (regla.tipo === 'telefono' && valor && !validarTelefono(valor)) {
            valido = false;
            mensaje = 'Ingrese un número de teléfono válido (ej: 0412-1234567).';
        } else if (regla.tipo === 'cedula' && valor && !validarCedula(valor)) {
            valido = false;
            mensaje = 'Ingrese una cédula válida (ej: V-12345678 o 12345678).';
        } else if (regla.tipo === 'numero' && valor && isNaN(valor)) {
            valido = false;
            mensaje = 'Debe ingresar un número válido.';
        } else if (regla.tipo === 'fecha' && valor && isNaN(Date.parse(valor))) {
            valido = false;
            mensaje = 'Ingrese una fecha válida.';
        } else if (regla.min && valor && Number(valor) < regla.min) {
            valido = false;
            mensaje = `El valor debe ser mayor o igual a ${regla.min}.`;
        } else if (regla.max && valor && Number(valor) > regla.max) {
            valido = false;
            mensaje = `El valor debe ser menor o igual a ${regla.max}.`;
        } else if (regla.minLength && valor && valor.length < regla.minLength) {
            valido = false;
            mensaje = `Debe tener al menos ${regla.minLength} caracteres.`;
        } else if (regla.maxLength && valor && valor.length > regla.maxLength) {
            valido = false;
            mensaje = `Debe tener máximo ${regla.maxLength} caracteres.`;
        } else if (regla.pattern && valor && !regla.pattern.test(valor)) {
            valido = false;
            mensaje = regla.mensajePattern || 'El formato no es válido.';
        }

        if (!valido) {
            esValido = false;
            campo.classList.add('input-error');
            errorElement.textContent = mensaje;
            errorElement.style.display = 'block';
            errores.push({ campo: regla.campo, mensaje });
        } else {
            campo.classList.remove('input-error');
            errorElement.style.display = 'none';
        }
    });

    // Mostrar mensaje de error general
    const errorGeneral = document.getElementById('error-general');
    if (errorGeneral) {
        if (!esValido) {
            errorGeneral.textContent = '⚠️ Por favor, corrija los errores marcados en el formulario.';
            errorGeneral.style.display = 'block';
        } else {
            errorGeneral.style.display = 'none';
        }
    }

    return esValido;
}

/**
 * Limpia los errores de un formulario
 */
function limpiarErrores(form) {
    form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    form.querySelectorAll('.error-message').forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
    const errorGeneral = document.getElementById('error-general');
    if (errorGeneral) {
        errorGeneral.style.display = 'none';
    }
}

/**
 * Crea un elemento para mostrar errores
 */
function crearErrorElement(campo) {
    const errorDiv = document.createElement('div');
    errorDiv.className = `error-message error-${campo.name}`;
    errorDiv.style.color = '#dc2626';
    errorDiv.style.fontSize = '0.75rem';
    errorDiv.style.marginTop = '4px';
    errorDiv.style.display = 'none';
    campo.parentNode.appendChild(errorDiv);
    return errorDiv;
}

// ============================================
// VALIDACIONES ESPECÍFICAS
// ============================================

function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validarTelefono(telefono) {
    const regex = /^(\+?\d{1,3}[\s-]?)?\d{4}[\s-]?\d{7}$/;
    return regex.test(telefono.replace(/\s/g, ''));
}

function validarCedula(cedula) {
    const regex = /^[VEve]?-?\d{7,8}$/;
    return regex.test(cedula);
}

// ============================================
// VALIDACIÓN DE DOCUMENTOS DE INGRESO
// ============================================

/**
 * Valida que al menos un documento de ingreso esté seleccionado
 * @param {string} formId - ID del formulario
 * @returns {boolean} - true si al menos uno está seleccionado
 */
function validarDocumentosIngreso(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const documentos = [
        'doc_sintesis',
        'doc_cedula', 
        'doc_titulos',
        'doc_constancias',
        'doc_certificados'
    ];
    
    let algunSeleccionado = false;
    documentos.forEach(nombre => {
        const cb = form.querySelector(`[name="${nombre}"]`);
        if (cb && cb.checked) {
            algunSeleccionado = true;
        }
    });
    
    if (!algunSeleccionado) {
        const errorDocs = document.getElementById('error-documentos');
        if (errorDocs) {
            errorDocs.textContent = '⚠️ Debe seleccionar al menos un documento de ingreso.';
            errorDocs.style.display = 'block';
        } else {
            const errorGeneral = document.getElementById('error-general');
            if (errorGeneral) {
                errorGeneral.textContent = '⚠️ Debe seleccionar al menos un documento de ingreso.';
                errorGeneral.style.display = 'block';
            }
        }
        return false;
    }
    
    const errorDocs = document.getElementById('error-documentos');
    if (errorDocs) {
        errorDocs.style.display = 'none';
    }
    
    return true;
}

// ============================================
// REGLAS DE VALIDACIÓN POR PÁGINA
// ============================================

// ===== 1. MAESTRO: EMPLEADO =====
const reglasEmpleado = [
    { campo: 'codigo', obligatorio: true, minLength: 3, maxLength: 20 },
    { campo: 'nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'email', obligatorio: true, tipo: 'email' },
    { campo: 'telefono', obligatorio: false, tipo: 'telefono' },
    { campo: 'fecha_ingreso', obligatorio: true, tipo: 'fecha' },
    { campo: 'cargo', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'departamento', obligatorio: true, tipo: 'select' },
    { campo: 'estatus', obligatorio: true, tipo: 'select' },
];

// ===== 2. MAESTRO: PAÍS =====
const reglasPais = [
    { campo: 'nombre', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'codigo_iso', obligatorio: true, minLength: 2, maxLength: 2, pattern: /^[A-Za-z]{2}$/, mensajePattern: 'El código ISO debe tener exactamente 2 letras.' },
];

// ===== 3. MAESTRO: ESTADO =====
const reglasEstado = [
    { campo: 'nombre', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'pais', obligatorio: true, tipo: 'select' },
    { campo: 'capital', obligatorio: true, minLength: 2, maxLength: 100 },
];

// ===== 4. MAESTRO: CIUDAD =====
const reglasCiudad = [
    { campo: 'nombre', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'estado', obligatorio: true, tipo: 'select' },
    { campo: 'codigo_postal', obligatorio: true, tipo: 'numero' },
    { campo: 'zona_horaria', obligatorio: true, minLength: 2, maxLength: 20 },
];

// ===== 5. MAESTRO: TIPO INSTITUCIÓN =====
const reglasTipoInstitucion = [
    { campo: 'nombre', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'descripcion', obligatorio: false, minLength: 2, maxLength: 500 },
];

// ===== 6. MAESTRO: INSTITUCIÓN =====
const reglasInstitucion = [
    { campo: 'nombre', obligatorio: true, minLength: 3, maxLength: 200 },
    { campo: 'tipo', obligatorio: true, tipo: 'select' },
    { campo: 'ubicacion', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'telefono', obligatorio: false, tipo: 'telefono' },
];

// ===== 7. MAESTRO: DOCUMENTOS =====
const reglasDocumentos = [
    { campo: 'nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'categoria', obligatorio: true, tipo: 'select' },
    { campo: 'formato', obligatorio: true, tipo: 'select' },
    { campo: 'descripcion', obligatorio: false, minLength: 2, maxLength: 500 },
];

// ===== 8. MAESTRO: IDIOMA =====
const reglasIdioma = [
    { campo: 'nombre', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'codigo_iso', obligatorio: true, minLength: 2, maxLength: 2, pattern: /^[A-Za-z]{2}$/, mensajePattern: 'El código ISO debe tener exactamente 2 letras.' },
];

// ===== 9. MAESTRO: TIPO PERMISO =====
const reglasTipoPermiso = [
    { campo: 'nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'categoria', obligatorio: true, tipo: 'select' },
    { campo: 'dias', obligatorio: false, tipo: 'numero', min: 0, max: 365 },
    { campo: 'requiere_soporte', obligatorio: true, tipo: 'select' },
    { campo: 'base_legal', obligatorio: false, minLength: 2, maxLength: 500 },
];

// ===== 10. MAESTRO: EDUCACIÓN =====
const reglasEducacion = [
    { campo: 'empleado', obligatorio: true, tipo: 'select' },
    { campo: 'nivel', obligatorio: true, tipo: 'select' },
    { campo: 'institucion', obligatorio: true, minLength: 3, maxLength: 200 },
    { campo: 'titulo', obligatorio: true, minLength: 3, maxLength: 200 },
    { campo: 'fecha_inicio', obligatorio: false, tipo: 'fecha' },
    { campo: 'fecha_fin', obligatorio: false, tipo: 'fecha' },
    { campo: 'anios', obligatorio: false, tipo: 'select' },
    { campo: 'estatus', obligatorio: true, tipo: 'select' },
];

// ===== 11. MAESTRO: FORMACIÓN =====
const reglasFormacion = [
    { campo: 'empleado', obligatorio: true, tipo: 'select' },
    { campo: 'curso', obligatorio: true, minLength: 3, maxLength: 200 },
    { campo: 'institucion', obligatorio: true, minLength: 3, maxLength: 200 },
    { campo: 'duracion', obligatorio: false, tipo: 'numero', min: 1 },
    { campo: 'fecha', obligatorio: false, tipo: 'fecha' },
    { campo: 'certificado', obligatorio: true, tipo: 'select' },
];

// ===== 12. MAESTRO: FAMILIA =====
const reglasFamilia = [
    { campo: 'empleado', obligatorio: true, tipo: 'select' },
    { campo: 'nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'parentesco', obligatorio: true, tipo: 'select' },
    { campo: 'fecha_nacimiento', obligatorio: false, tipo: 'fecha' },
    { campo: 'cedula', obligatorio: false, tipo: 'cedula' },
    { campo: 'convive', obligatorio: true, tipo: 'select' },
];

// ===== 13. MAESTRO: VEHÍCULO =====
const reglasVehiculo = [
    { campo: 'empleado', obligatorio: true, tipo: 'select' },
    { campo: 'posee', obligatorio: true, tipo: 'select' },
    { campo: 'marca', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'modelo', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'anio', obligatorio: false, tipo: 'numero', min: 1900, max: 2099 },
    { campo: 'color', obligatorio: false, minLength: 3, maxLength: 30 },
    { campo: 'placa', obligatorio: false, minLength: 5, maxLength: 10 },
    { campo: 'tipo', obligatorio: false, tipo: 'select' },
];

// ===== 14. MAESTRO: EXTRANJERO =====
const reglasExtranjero = [
    { campo: 'empleado', obligatorio: true, tipo: 'select' },
    { campo: 'pais_origen', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'pasaporte', obligatorio: true, minLength: 5, maxLength: 20 },
    { campo: 'fecha_emision', obligatorio: false, tipo: 'fecha' },
    { campo: 'fecha_vencimiento', obligatorio: false, tipo: 'fecha' },
    { campo: 'tipo_visa', obligatorio: true, tipo: 'select' },
    { campo: 'tiempo_estadia', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'permiso_trabajo', obligatorio: true, tipo: 'select' },
];

// ===== 15. MAESTRO: NACIONALIZACIÓN =====
const reglasNacionalizacion = [
    { campo: 'empleado', obligatorio: true, tipo: 'select' },
    { campo: 'pais_origen', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'gaceta', obligatorio: true, minLength: 3, maxLength: 20 },
    { campo: 'fecha_gaceta', obligatorio: false, tipo: 'fecha' },
    { campo: 'fecha_nacionalizacion', obligatorio: false, tipo: 'fecha' },
    { campo: 'cedula', obligatorio: false, tipo: 'cedula' },
    { campo: 'estado', obligatorio: true, tipo: 'select' },
    { campo: 'observaciones', obligatorio: false, minLength: 2, maxLength: 500 },
];

// ===== 16. RECLUTAMIENTO (CON DOCUMENTOS DE INGRESO - CORREGIDO) =====
const reglasReclutamiento = [
    // ========================================
    // DATOS PERSONALES (OBLIGATORIOS)
    // ========================================
    { campo: 'primer_apellido', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'segundo_apellido', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'nombres', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'nacionalidad', obligatorio: true, tipo: 'select' },
    { campo: 'cedula', obligatorio: true, tipo: 'cedula' },
    { campo: 'estado_civil', obligatorio: true, tipo: 'select' },
    { campo: 'sexo', obligatorio: true, tipo: 'select' },
    { campo: 'fecha_nacimiento', obligatorio: true, tipo: 'fecha' },
    { campo: 'edad', obligatorio: true, tipo: 'numero', min: 18, max: 99 },
    { campo: 'pais_nacimiento', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'estado_nacimiento', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'ciudad_nacimiento', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'direccion', obligatorio: true, minLength: 5, maxLength: 200 },
    { campo: 'telefono', obligatorio: true, tipo: 'telefono' },
    
    // ========================================
    // DATOS FAMILIARES (TODOS OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'conyuge_nombre', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'conyuge_ocupacion', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'conyuge_nacionalidad', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'num_hijos', obligatorio: false, tipo: 'numero', min: 0 },
    { campo: 'familiar_institucion_nombre', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'familiar_institucion_parentesco', obligatorio: false, minLength: 2, maxLength: 50 },
    
    // ========================================
    // FAMILIARES (TABLA - OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'familiar1_nombre', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'familiar1_cedula', obligatorio: false, tipo: 'cedula' },
    { campo: 'familiar1_parentesco', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar1_sexo', obligatorio: false, minLength: 1, maxLength: 10 },
    { campo: 'familiar1_nacionalidad', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar1_fecha_nac', obligatorio: false, tipo: 'fecha' },
    { campo: 'familiar1_nivel', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar1_convive', obligatorio: false, tipo: 'select' },
    
    { campo: 'familiar2_nombre', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'familiar2_cedula', obligatorio: false, tipo: 'cedula' },
    { campo: 'familiar2_parentesco', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar2_sexo', obligatorio: false, minLength: 1, maxLength: 10 },
    { campo: 'familiar2_nacionalidad', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar2_fecha_nac', obligatorio: false, tipo: 'fecha' },
    { campo: 'familiar2_nivel', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar2_convive', obligatorio: false, tipo: 'select' },
    
    { campo: 'familiar3_nombre', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'familiar3_cedula', obligatorio: false, tipo: 'cedula' },
    { campo: 'familiar3_parentesco', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar3_sexo', obligatorio: false, minLength: 1, maxLength: 10 },
    { campo: 'familiar3_nacionalidad', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar3_fecha_nac', obligatorio: false, tipo: 'fecha' },
    { campo: 'familiar3_nivel', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'familiar3_convive', obligatorio: false, tipo: 'select' },
    
    // ========================================
    // EDUCACIÓN - ESTUDIO 1 (OBLIGATORIO)
    // ========================================
    { campo: 'estudio1_nivel', obligatorio: true, minLength: 2, maxLength: 50 },
    { campo: 'estudio1_especialidad', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'estudio1_institucion', obligatorio: true, minLength: 2, maxLength: 200 },
    { campo: 'estudio1_lugar', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'estudio1_anios', obligatorio: false, tipo: 'numero', min: 0 },
    { campo: 'estudio1_egreso', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'estudio1_titulo', obligatorio: true, minLength: 2, maxLength: 100 },
    
    // ========================================
    // EDUCACIÓN - ESTUDIO 2 (OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'estudio2_nivel', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'estudio2_especialidad', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'estudio2_institucion', obligatorio: false, minLength: 2, maxLength: 200 },
    { campo: 'estudio2_lugar', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'estudio2_anios', obligatorio: false, tipo: 'numero', min: 0 },
    { campo: 'estudio2_egreso', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'estudio2_titulo', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // EDUCACIÓN ACTUAL (OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'estudio_actual_nivel', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'estudio_actual_institucion', obligatorio: false, minLength: 2, maxLength: 200 },
    { campo: 'estudio_actual_lugar', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'estudio_actual_titulo', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // CURSOS (OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'curso1_denominacion', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'curso1_institucion', obligatorio: false, minLength: 2, maxLength: 200 },
    { campo: 'curso1_localidad', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'curso1_duracion', obligatorio: false, tipo: 'numero', min: 1 },
    { campo: 'curso1_fecha', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'curso1_certificado', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'curso2_denominacion', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'curso2_institucion', obligatorio: false, minLength: 2, maxLength: 200 },
    { campo: 'curso2_localidad', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'curso2_duracion', obligatorio: false, tipo: 'numero', min: 1 },
    { campo: 'curso2_fecha', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'curso2_certificado', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // IDIOMAS (OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'idioma1_nombre', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'idioma1_comprende', obligatorio: false, tipo: 'select' },
    { campo: 'idioma1_habla', obligatorio: false, tipo: 'select' },
    { campo: 'idioma1_lee', obligatorio: false, tipo: 'select' },
    { campo: 'idioma1_escribe', obligatorio: false, tipo: 'select' },
    { campo: 'idioma2_nombre', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'idioma2_comprende', obligatorio: false, tipo: 'select' },
    { campo: 'idioma2_habla', obligatorio: false, tipo: 'select' },
    { campo: 'idioma2_lee', obligatorio: false, tipo: 'select' },
    { campo: 'idioma2_escribe', obligatorio: false, tipo: 'select' },
    
    // ========================================
    // HABILIDADES (OPCIONAL) ✅ CORREGIDO
    // ========================================
    { campo: 'habilidades', obligatorio: false, minLength: 2, maxLength: 500 },
    
    // ========================================
    // EXPERIENCIA LABORAL - EMPRESA 1 (OBLIGATORIA)
    // ========================================
    { campo: 'experiencia1_empresa', obligatorio: true, minLength: 2, maxLength: 200 },
    { campo: 'experiencia1_cargo', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'experiencia1_desde', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'experiencia1_hasta', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'experiencia1_sueldo', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'experiencia1_motivo', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // EXPERIENCIA LABORAL - EMPRESA 2 (OPCIONAL) ✅ CORREGIDO
    // ========================================
    { campo: 'experiencia2_empresa', obligatorio: false, minLength: 2, maxLength: 200 },
    { campo: 'experiencia2_cargo', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'experiencia2_desde', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'experiencia2_hasta', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'experiencia2_sueldo', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'experiencia2_motivo', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // DATOS ADICIONALES DE EXPERIENCIA (OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'experiencia_telefono', obligatorio: false, tipo: 'telefono' },
    { campo: 'experiencia_lugar', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'experiencia_supervisor', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // REFERENCIAS PERSONALES
    // ========================================
    // REFERENCIA 1 - OBLIGATORIA
    { campo: 'referencia1_nombre', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'referencia1_direccion', obligatorio: true, minLength: 2, maxLength: 200 },
    { campo: 'referencia1_telefono', obligatorio: true, tipo: 'telefono' },
    { campo: 'referencia1_ocupacion', obligatorio: true, minLength: 2, maxLength: 100 },
    
    // REFERENCIA 2 - OBLIGATORIA
    { campo: 'referencia2_nombre', obligatorio: true, minLength: 2, maxLength: 100 },
    { campo: 'referencia2_direccion', obligatorio: true, minLength: 2, maxLength: 200 },
    { campo: 'referencia2_telefono', obligatorio: true, tipo: 'telefono' },
    { campo: 'referencia2_ocupacion', obligatorio: true, minLength: 2, maxLength: 100 },
    
    // REFERENCIA 3 - OPCIONAL ✅ CORREGIDO
    { campo: 'referencia3_nombre', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'referencia3_direccion', obligatorio: false, minLength: 2, maxLength: 200 },
    { campo: 'referencia3_telefono', obligatorio: false, tipo: 'telefono' },
    { campo: 'referencia3_ocupacion', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // DATOS SOCIOECONÓMICOS
    // ========================================
    { campo: 'tipo_vivienda', obligatorio: true, tipo: 'select' },
    { campo: 'tenencia_vivienda', obligatorio: true, tipo: 'select' },
    { campo: 'monto_vivienda', obligatorio: false, tipo: 'numero', min: 0 },
    { campo: 'ingreso_familiar', obligatorio: false, tipo: 'numero', min: 0 },
    { campo: 'personas_cargo', obligatorio: false, tipo: 'numero', min: 0 },
    
    // ========================================
    // DATOS DE PENSIÓN
    // ========================================
    { campo: 'pension_apellido1', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'pension_apellido2', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'pension_nombres', obligatorio: false, minLength: 2, maxLength: 50 },
    { campo: 'tipo_trabajador', obligatorio: true, tipo: 'select' },
    
    // ========================================
    // 📎 DOCUMENTOS MÍNIMOS DE INGRESO (CHECKBOXES - OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'doc_sintesis', obligatorio: false },
    { campo: 'doc_cedula', obligatorio: false },
    { campo: 'doc_titulos', obligatorio: false },
    { campo: 'doc_constancias', obligatorio: false },
    { campo: 'doc_certificados', obligatorio: false },
    
    // ========================================
    // 📎 DOCUMENTACIÓN POSTERIOR (TEXTOS - OPCIONALES) ✅ CORREGIDO
    // ========================================
    { campo: 'doc_partida_nacimiento', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'doc_acta_matrimonio', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'doc_cedula_conyuge', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'doc_observaciones_rrhh', obligatorio: false, minLength: 2, maxLength: 500 },
    
    // ========================================
    // DECLARACIÓN FINAL (OPCIONAL) ✅ CORREGIDO
    // ========================================
    { campo: 'fecha_declaracion', obligatorio: false, tipo: 'fecha' },
];

// ===== 17. PERMISOS (AMPLIADO - IGUAL QUE RECLUTAMIENTO, ASISTENCIA, VACACIONES Y REPOSOS) =====
const reglasPermisos = [
    // ========================================
    // DATOS DEL TRABAJADOR (OBLIGATORIOS)
    // ========================================
    { campo: 'empleado_nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado_cedula', obligatorio: true, tipo: 'cedula' },
    { campo: 'empleado_fecha_ingreso', obligatorio: true, tipo: 'fecha' },
    { campo: 'empleado_cargo', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado_dependencia', obligatorio: true, minLength: 3, maxLength: 100 },
    
    // ========================================
    // CONCESIÓN OBLIGATORIA (CHECKBOXES - OPCIONALES)
    // ========================================
    { campo: 'permiso1_sel', obligatorio: false },
    { campo: 'permiso1_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso2_sel', obligatorio: false },
    { campo: 'permiso2_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso3_sel', obligatorio: false },
    { campo: 'permiso3_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso4_sel', obligatorio: false },
    { campo: 'permiso4_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso5_sel', obligatorio: false },
    { campo: 'permiso5_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso6_sel', obligatorio: false },
    { campo: 'permiso6_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso7_sel', obligatorio: false },
    { campo: 'permiso7_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso8_sel', obligatorio: false },
    { campo: 'permiso8_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso9_sel', obligatorio: false },
    { campo: 'permiso9_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso10_sel', obligatorio: false },
    { campo: 'permiso10_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso11_sel', obligatorio: false },
    { campo: 'permiso11_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso12_sel', obligatorio: false },
    { campo: 'permiso12_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso13_sel', obligatorio: false },
    { campo: 'permiso13_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso14_sel', obligatorio: false },
    { campo: 'permiso14_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    
    // ========================================
    // CONCESIÓN POTESTATIVA (CHECKBOXES - OPCIONALES)
    // ========================================
    { campo: 'permiso_potestativo1_sel', obligatorio: false },
    { campo: 'permiso_potestativo1_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso_potestativo2_sel', obligatorio: false },
    { campo: 'permiso_potestativo2_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso_potestativo3_sel', obligatorio: false },
    { campo: 'permiso_potestativo3_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'permiso_potestativo4_sel', obligatorio: false },
    { campo: 'permiso_potestativo4_otro', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'permiso_potestativo4_consigno', obligatorio: false, minLength: 2, maxLength: 10 },
    
    // ========================================
    // OBSERVACIONES (OBLIGATORIO)
    // ========================================
    { campo: 'observaciones', obligatorio: false, minLength: 2, maxLength: 500 },
    
    // ========================================
    // DURACIÓN DEL PERMISO (OPCIONALES)
    // ========================================
    { campo: 'permiso_desde_dia', obligatorio: false, tipo: 'numero', min: 1, max: 31 },
    { campo: 'permiso_desde_mes', obligatorio: false, tipo: 'numero', min: 1, max: 12 },
    { campo: 'permiso_desde_anio', obligatorio: false, tipo: 'numero', min: 2000, max: 2100 },
    { campo: 'permiso_hasta_dia', obligatorio: false, tipo: 'numero', min: 1, max: 31 },
    { campo: 'permiso_hasta_mes', obligatorio: false, tipo: 'numero', min: 1, max: 12 },
    { campo: 'permiso_hasta_anio', obligatorio: false, tipo: 'numero', min: 2000, max: 2100 },
    { campo: 'permiso_total_dias', obligatorio: false, tipo: 'numero', min: 0 },
    
    // ========================================
    // FIRMAS (OPCIONALES)
    // ========================================
    { campo: 'firma_trabajador', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'firma_superior', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'firma_secretario', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // SOLO PARA USO DE RRHH (OPCIONALES)
    // ========================================
    { campo: 'rrhh_observaciones', obligatorio: false, minLength: 2, maxLength: 500 },
    { campo: 'rrhh_recibido', obligatorio: false, minLength: 2, maxLength: 100 },
];

// ===== 18. VACACIONES (AMPLIADO - IGUAL QUE RECLUTAMIENTO Y ASISTENCIA) =====
const reglasVacaciones = [
    // FECHA DE SOLICITUD
    { campo: 'fecha_solicitud', obligatorio: true, tipo: 'fecha' },
    
    // DATOS DEL TRABAJADOR
    { campo: 'empleado_nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado_cedula', obligatorio: true, tipo: 'cedula' },
    { campo: 'empleado_cargo', obligatorio: true, minLength: 3, maxLength: 100 },
    
    // TIPO DE PERSONAL (CHECKBOXES - OPCIONALES)
    { campo: 'tipo_empleado_fijo', obligatorio: false },
    { campo: 'tipo_empleado_contratado', obligatorio: false },
    { campo: 'tipo_obrero_fijo', obligatorio: false },
    { campo: 'tipo_obrero_contratado', obligatorio: false },
    
    // FECHAS DE INGRESO Y ANTIGÜEDAD
    { campo: 'fecha_ingreso', obligatorio: true, tipo: 'fecha' },
    { campo: 'fecha_antiguedad', obligatorio: true, tipo: 'fecha' },
    { campo: 'anios_servicio', obligatorio: true, tipo: 'numero', min: 0 },
    
    // VACACIONES PENDIENTES (OPCIONALES) - FILA 1
    { campo: 'vac_pendiente1_periodo', obligatorio: true, minLength: 2, maxLength: 20 },
    { campo: 'vac_pendiente1_quinquenio', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'vac_pendiente1_dias', obligatorio: false, tipo: 'numero', min: 0 },
    
    // VACACIONES PENDIENTES (OPCIONALES) - FILA 2
    { campo: 'vac_pendiente2_periodo', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'vac_pendiente2_quinquenio', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'vac_pendiente2_dias', obligatorio: false, tipo: 'numero', min: 0 },
    
    // VACACIONES PENDIENTES (OPCIONALES) - FILA 3
    { campo: 'vac_pendiente3_periodo', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'vac_pendiente3_quinquenio', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'vac_pendiente3_dias', obligatorio: false, tipo: 'numero', min: 0 },
    
    // VACACIONES A DISFRUTAR (OPCIONALES) - FILA 1
    { campo: 'vac_disfrutar1_periodo', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'vac_disfrutar1_quinquenio', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'vac_disfrutar1_dias', obligatorio: false, tipo: 'numero', min: 0 },
    
    // VACACIONES A DISFRUTAR (OPCIONALES) - FILA 2
    { campo: 'vac_disfrutar2_periodo', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'vac_disfrutar2_quinquenio', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'vac_disfrutar2_dias', obligatorio: false, tipo: 'numero', min: 0 },
    
    // VACACIONES A DISFRUTAR (OPCIONALES) - FILA 3
    { campo: 'vac_disfrutar3_periodo', obligatorio: false, minLength: 2, maxLength: 20 },
    { campo: 'vac_disfrutar3_quinquenio', obligatorio: false, minLength: 2, maxLength: 10 },
    { campo: 'vac_disfrutar3_dias', obligatorio: false, tipo: 'numero', min: 0 },
    
    // DESARROLLO DE LAS VACACIONES (OBLIGATORIOS)
    { campo: 'dias_disfrutar', obligatorio: true, tipo: 'numero', min: 1 },
    { campo: 'fecha_inicio', obligatorio: true, tipo: 'fecha' },
    { campo: 'fecha_fin', obligatorio: true, tipo: 'fecha' },
    { campo: 'fecha_reintegro', obligatorio: true, tipo: 'fecha' },
    
    // OBSERVACIONES
    { campo: 'observaciones', obligatorio: false, minLength: 2, maxLength: 500 },
    
    // FIRMAS (OPCIONALES)
    { campo: 'firma_solicitante', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'firma_superior', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // SOLO PARA USO DE RRHH (OPCIONALES)
    { campo: 'rrhh_fecha', obligatorio: false, tipo: 'fecha' },
    { campo: 'rrhh_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'rrhh_bono', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'rrhh_nomina', obligatorio: false, minLength: 2, maxLength: 100 },
];

// ===== 19. REPOSOS (AMPLIADO - IGUAL QUE RECLUTAMIENTO, ASISTENCIA Y VACACIONES) =====
const reglasReposos = [
    // ========================================
    // DATOS DEL TRABAJADOR (OBLIGATORIOS)
    // ========================================
    { campo: 'empleado_nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado_cedula', obligatorio: true, tipo: 'cedula' },
    { campo: 'empleado_fecha_nac', obligatorio: false, tipo: 'fecha' },
    { campo: 'empleado_cargo', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado_departamento', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado_telefono', obligatorio: false, tipo: 'telefono' },
    
    // ========================================
    // DATOS DEL REPOSO (OBLIGATORIOS)
    // ========================================
    { campo: 'tipo_reposo', obligatorio: true, tipo: 'select' },
    { campo: 'numero_reposo', obligatorio: true, minLength: 3, maxLength: 20 },
    { campo: 'numero_expediente', obligatorio: false, minLength: 3, maxLength: 20 },
    { campo: 'diagnostico', obligatorio: true, minLength: 5, maxLength: 500 },
    
    // ========================================
    // FECHAS DEL REPOSO (OBLIGATORIAS)
    // ========================================
    { campo: 'fecha_emision', obligatorio: true, tipo: 'fecha' },
    { campo: 'fecha_inicio', obligatorio: true, tipo: 'fecha' },
    { campo: 'fecha_fin', obligatorio: true, tipo: 'fecha' },
    { campo: 'total_dias', obligatorio: false, tipo: 'numero', min: 0 },
    { campo: 'fecha_reintegro', obligatorio: true, tipo: 'fecha' },
    { campo: 'prorrogable', obligatorio: false, tipo: 'select' },
    { campo: 'estado_reposo', obligatorio: false, tipo: 'select' },
    
    // ========================================
    // DATOS DEL MÉDICO (OBLIGATORIOS)
    // ========================================
    { campo: 'medico_nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'medico_colegiatura', obligatorio: true, minLength: 3, maxLength: 20 },
    { campo: 'medico_especialidad', obligatorio: false, minLength: 3, maxLength: 50 },
    { campo: 'medico_institucion', obligatorio: true, minLength: 3, maxLength: 200 },
    { campo: 'medico_telefono', obligatorio: false, tipo: 'telefono' },
    { campo: 'medico_rif', obligatorio: false, minLength: 3, maxLength: 20 },
    { campo: 'medico_direccion', obligatorio: false, minLength: 2, maxLength: 200 },
    
    // ========================================
    // DOCUMENTOS ADJUNTOS (CHECKBOXES - OPCIONALES)
    // ========================================
    { campo: 'doc_reposo', obligatorio: false },
    { campo: 'doc_lab', obligatorio: false },
    { campo: 'doc_historia', obligatorio: false },
    { campo: 'doc_referencia', obligatorio: false },
    { campo: 'doc_accidente', obligatorio: false },
    
    // ========================================
    // OBSERVACIONES DE DOCUMENTOS (OPCIONAL)
    // ========================================
    { campo: 'doc_observaciones', obligatorio: false, minLength: 2, maxLength: 500 },
    
    // ========================================
    // FIRMAS (OPCIONALES)
    // ========================================
    { campo: 'firma_trabajador', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'firma_medico', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'firma_rrhh', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // SOLO PARA USO DE RRHH (OPCIONALES)
    // ========================================
    { campo: 'rrhh_fecha_recepcion', obligatorio: false, tipo: 'fecha' },
    { campo: 'rrhh_funcionario', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'rrhh_registrado', obligatorio: false, tipo: 'select' },
    { campo: 'rrhh_pago', obligatorio: false, tipo: 'select' },
    { campo: 'rrhh_observaciones', obligatorio: false, minLength: 2, maxLength: 500 },
];

// ===== 20. ASISTENCIA (AMPLIADO - IGUAL QUE RECLUTAMIENTO) =====
const reglasAsistencia = [
    // ========================================
    // DATOS DEL TRABAJADOR 1
    // ========================================
    { campo: 'empleado1_nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado1_cedula', obligatorio: true, tipo: 'cedula' },
    { campo: 'empleado1_tipo', obligatorio: false, tipo: 'select' },
    
    // EMPLEADO 1 - REGISTRO SEMANAL (OPCIONALES)
    { campo: 'empleado1_lunes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado1_lunes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_lunes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_lunes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado1_martes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado1_martes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_martes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_martes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado1_miercoles_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado1_miercoles_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_miercoles_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_miercoles_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado1_jueves_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado1_jueves_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_jueves_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_jueves_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado1_viernes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado1_viernes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_viernes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado1_viernes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // DATOS DEL TRABAJADOR 2
    // ========================================
    { campo: 'empleado2_nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado2_cedula', obligatorio: true, tipo: 'cedula' },
    { campo: 'empleado2_tipo', obligatorio: false, tipo: 'select' },
    
    // EMPLEADO 2 - REGISTRO SEMANAL (OPCIONALES)
    { campo: 'empleado2_lunes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado2_lunes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_lunes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_lunes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado2_martes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado2_martes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_martes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_martes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado2_miercoles_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado2_miercoles_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_miercoles_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_miercoles_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado2_jueves_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado2_jueves_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_jueves_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_jueves_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado2_viernes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado2_viernes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_viernes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado2_viernes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // DATOS DEL TRABAJADOR 3
    // ========================================
    { campo: 'empleado3_nombre', obligatorio: true, minLength: 3, maxLength: 100 },
    { campo: 'empleado3_cedula', obligatorio: true, tipo: 'cedula' },
    { campo: 'empleado3_tipo', obligatorio: false, tipo: 'select' },
    
    // EMPLEADO 3 - REGISTRO SEMANAL (OPCIONALES)
    { campo: 'empleado3_lunes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado3_lunes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_lunes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_lunes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado3_martes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado3_martes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_martes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_martes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado3_miercoles_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado3_miercoles_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_miercoles_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_miercoles_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado3_jueves_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado3_jueves_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_jueves_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_jueves_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    { campo: 'empleado3_viernes_fecha', obligatorio: false, minLength: 8, maxLength: 10 },
    { campo: 'empleado3_viernes_entrada', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_viernes_salida', obligatorio: false, minLength: 4, maxLength: 5 },
    { campo: 'empleado3_viernes_firma', obligatorio: false, minLength: 2, maxLength: 100 },
    
    // ========================================
    // CABECERA Y OBSERVACIONES
    // ========================================
    { campo: 'mes', obligatorio: false, tipo: 'select' },
    { campo: 'anio', obligatorio: false, tipo: 'numero', min: 2020, max: 2100 },
    { campo: 'fecha_emision', obligatorio: false, tipo: 'fecha' },
    { campo: 'fecha_revision', obligatorio: false, tipo: 'fecha' },
    { campo: 'revisado_por', obligatorio: false, minLength: 2, maxLength: 100 },
    { campo: 'observaciones', obligatorio: false, minLength: 2, maxLength: 500 },
];

// ============================================
// FUNCIONES DE UTILIDAD
// ============================================

function inicializarFormulario(formId, reglas, onSuccess = null) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const esValido = validarFormulario(formId, reglas);
        
        // Si es el formulario de reclutamiento, validar documentos
        if (formId === 'form-reclutamiento' && esValido) {
            const docsValidos = validarDocumentosIngreso(formId);
            if (!docsValidos) {
                return;
            }
        }
        
        if (esValido && onSuccess) {
            onSuccess();
        } else if (esValido) {
            alert('✅ Formulario validado correctamente. Datos listos para guardar.');
        }
    });

    form.querySelectorAll('input, select, textarea').forEach(campo => {
        campo.addEventListener('blur', function() {
            const regla = reglas.find(r => r.campo === this.name);
            if (regla) {
                validarFormulario(formId, [regla]);
            }
        });
        campo.addEventListener('input', function() {
            this.classList.remove('input-error');
            const errorElement = form.querySelector(`.error-${this.name}`);
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        });
    });
}

// ============================================
// INICIALIZACIÓN AUTOMÁTICA
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const formularios = {
        'form-empleado': { reglas: reglasEmpleado },
        'form-pais': { reglas: reglasPais },
        'form-estado': { reglas: reglasEstado },
        'form-ciudad': { reglas: reglasCiudad },
        'form-tipo-institucion': { reglas: reglasTipoInstitucion },
        'form-institucion': { reglas: reglasInstitucion },
        'form-documentos': { reglas: reglasDocumentos },
        'form-idioma': { reglas: reglasIdioma },
        'form-tipo-permiso': { reglas: reglasTipoPermiso },
        'form-educacion': { reglas: reglasEducacion },
        'form-formacion': { reglas: reglasFormacion },
        'form-familia': { reglas: reglasFamilia },
        'form-vehiculo': { reglas: reglasVehiculo },
        'form-extranjero': { reglas: reglasExtranjero },
        'form-nacionalizacion': { reglas: reglasNacionalizacion },
        'form-reclutamiento': { reglas: reglasReclutamiento },
        'form-permiso': { reglas: reglasPermisos },        // ✅ AMPLIADO
        'form-vacaciones': { reglas: reglasVacaciones },   // ✅ AMPLIADO
        'form-reposo': { reglas: reglasReposos },          // ✅ AMPLIADO
        'form-asistencia': { reglas: reglasAsistencia },   // ✅ AMPLIADO
    };

    Object.keys(formularios).forEach(formId => {
        if (document.getElementById(formId)) {
            inicializarFormulario(formId, formularios[formId].reglas);
        }
    });
});

// Exportar funciones para uso global
window.validarFormulario = validarFormulario;
window.inicializarFormulario = inicializarFormulario;
window.limpiarFormulario = function(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('input:not([readonly]), textarea, select').forEach(campo => {
        if (campo.type === 'checkbox' || campo.type === 'radio') {
            campo.checked = false;
        } else if (campo.tagName === 'SELECT') {
            campo.selectedIndex = 0;
        } else {
            campo.value = '';
        }
    });
    limpiarErrores(form);
};

// ============================================
// FUNCIONES PARA CONSUMIR LA API
// ============================================

const API_URL = 'http://localhost/proyecto-rrhh/api/';

/**
 * Obtiene todos los registros de una tabla
 * @param {string} table - Nombre de la tabla
 * @returns {Promise}
 */
function getAll(table) {
    return fetch(`${API_URL}generic.php?table=${table}`)
        .then(response => response.json())
        .catch(error => console.error('Error:', error));
}

/**
 * Obtiene un registro por ID
 * @param {string} table - Nombre de la tabla
 * @param {number} id - ID del registro
 * @returns {Promise}
 */
function getById(table, id) {
    return fetch(`${API_URL}generic.php?table=${table}&id=${id}`)
        .then(response => response.json())
        .catch(error => console.error('Error:', error));
}

/**
 * Crea un nuevo registro
 * @param {string} table - Nombre de la tabla
 * @param {object} data - Datos a guardar
 * @returns {Promise}
 */
function create(table, data) {
    return fetch(`${API_URL}generic.php?table=${table}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .catch(error => console.error('Error:', error));
}

/**
 * Actualiza un registro
 * @param {string} table - Nombre de la tabla
 * @param {number} id - ID del registro
 * @param {object} data - Datos a actualizar
 * @returns {Promise}
 */
function update(table, id, data) {
    return fetch(`${API_URL}generic.php?table=${table}&id=${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .catch(error => console.error('Error:', error));
}

/**
 * Elimina un registro
 * @param {string} table - Nombre de la tabla
 * @param {number} id - ID del registro
 * @returns {Promise}
 */
function deleteRecord(table, id) {
    return fetch(`${API_URL}generic.php?table=${table}&id=${id}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .catch(error => console.error('Error:', error));
}

/**
 * Carga los datos de una tabla en un select
 * @param {string} table - Nombre de la tabla
 * @param {string} selectId - ID del select
 * @param {string} valueField - Campo para el value
 * @param {string} textField - Campo para el texto
 */
function loadSelect(table, selectId, valueField = 'id', textField = 'nombre') {
    const select = document.getElementById(selectId);
    if (!select) return;
    
    // Guardar opción por defecto
    const defaultOption = select.querySelector('option[value=""]');
    
    getAll(table).then(data => {
        // Limpiar select (excepto la opción por defecto)
        select.innerHTML = '';
        if (defaultOption) {
            select.appendChild(defaultOption);
        } else {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Seleccione...';
            select.appendChild(opt);
        }
        
        // Agregar opciones
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueField];
            option.textContent = item[textField];
            select.appendChild(option);
        });
    });
}

// Exportar funciones para uso global
window.getAll = getAll;
window.getById = getById;
window.create = create;
window.update = update;
window.deleteRecord = deleteRecord;
window.loadSelect = loadSelect;

window.imprimirPDF = function() {
    window.print();
};
window.validarDocumentosIngreso = validarDocumentosIngreso;