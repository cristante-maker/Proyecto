// idioma.js - CRUD Completo para Idioma

// 📌 RUTA DEL PHP
const PHP_URL = '/SystemRRHH/vista/Maestros/maestroidioma/idioma_crud.php';
let editMode = false;
let editId = null;

// ============================================
// INICIALIZACIÓN
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM cargado - Iniciando idioma.js');
    loadIdiomas();
    setupEventListeners();
});

// ============================================
// CONFIGURACIÓN DE EVENTOS
// ============================================
function setupEventListeners() {
    const form = document.getElementById('form-idioma');
    if (form) {
        form.addEventListener('submit', handleSubmit);
        form.addEventListener('reset', resetForm);
        console.log('✅ Eventos del formulario configurados');
    }
    
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            filterTable(e.target.value);
        });
        console.log('✅ Evento de búsqueda configurado');
    }
}

// ============================================
// FUNCIONES CRUD
// ============================================

function loadIdiomas() {
    console.log('🔄 Cargando idiomas...');
    const tbody = document.querySelector('.data-table tbody');
    if (!tbody) {
        console.error('❌ No se encontró el tbody');
        return;
    }
    
    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 30px;">⏳ Cargando datos...</td></tr>';
    
    fetch(`${PHP_URL}?action=list`)
        .then(response => response.json())
        .then(data => {
            console.log('📊 Datos recibidos:', data);
            if (data.success) {
                renderTable(data.data);
                actualizarContador(data.data ? data.data.length : 0);
            } else {
                showError('❌ Error al cargar los datos: ' + (data.message || 'Error desconocido'));
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 30px;">❌ Error al cargar datos</td></tr>';
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            showError('❌ Error de conexión: ' + error.message);
            tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 30px;">❌ Error de conexión al servidor</td></tr>';
        });
}

function renderTable(data) {
    const tbody = document.querySelector('.data-table tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 30px;">📭 No hay idiomas registrados</td></tr>';
        return;
    }
    
    data.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><strong>${row.id}</strong></td>
            <td>${row.nombre || 'N/A'}</td>
            <td><span class="badge badge-info">${row.codigo_iso || 'N/A'}</span></td>
            <td>
                <div class="action-btns">
                    <button class="action-btn action-btn-view" onclick="viewIdioma(${row.id})" title="Ver detalles">👁️ Ver</button>
                    <button class="action-btn action-btn-edit" onclick="editIdioma(${row.id})" title="Editar">✏️ Editar</button>
                    <button class="action-btn action-btn-delete" onclick="deleteIdioma(${row.id})" title="Eliminar">🗑️ Eliminar</button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
    console.log('✅ Tabla renderizada con', data.length, 'registros');
}

function actualizarContador(total) {
    const contador = document.getElementById('contador-registros');
    if (contador) {
        contador.textContent = total;
    }
}

function filterTable(searchTerm) {
    const rows = document.querySelectorAll('.data-table tbody tr');
    const term = searchTerm.toLowerCase().trim();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

// ============================================
// HANDLE SUBMIT - CON TODAS LAS VALIDACIONES
// ============================================
function handleSubmit(e) {
    e.preventDefault();
    console.log('📝 Enviando formulario...');
    
    const form = e.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // 1. Validar Nombre
    if (!data.nombre || data.nombre.trim() === '') {
        showError('❌ El nombre del idioma es requerido.');
        return;
    }
    if (data.nombre && data.nombre.length < 2) {
        showError('❌ El nombre debe tener al menos 2 caracteres.');
        return;
    }
    
    // 2. Validar Código ISO
    if (!data.codigo_iso || data.codigo_iso.trim() === '') {
        showError('❌ El código ISO es requerido.');
        return;
    }
    if (data.codigo_iso && !/^[A-Za-z]{2}$/.test(data.codigo_iso)) {
        showError('❌ El código ISO debe tener exactamente 2 letras.');
        return;
    }
    
    const url = editMode ? `${PHP_URL}?action=update` : `${PHP_URL}?action=create`;
    const method = editMode ? 'PUT' : 'POST';
    
    if (editMode) {
        data.id = editId;
    }
    
    const submitBtn = form.querySelector('.btn-success');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Guardando...';
    
    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        
        if (result.success) {
            showSuccess(result.message || (editMode ? '✅ Registro actualizado' : '✅ Registro creado'));
            resetForm();
            loadIdiomas();
        } else {
            if (result.errors) {
                showErrors(result.errors);
            } else {
                showError('❌ ' + (result.message || 'Error al guardar'));
            }
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        showError('❌ Error: ' + error.message);
    });
}

// ============================================
// FUNCIONES CRUD - VER, EDITAR, ELIMINAR
// ============================================

function viewIdioma(id) {
    console.log('👁️ Ver registro:', id);
    fetch(`${PHP_URL}?action=get&id=${id}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showViewModal(result.data);
            } else {
                showError('❌ ' + (result.message || 'Error al cargar'));
            }
        })
        .catch(error => showError('❌ Error: ' + error.message));
}

function showViewModal(data) {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay active';
    modal.id = 'modal-view-idioma';
    
    modal.innerHTML = `
        <div class="modal modal-sm">
            <div class="modal-header">
                <h2>🌐 Detalles del Idioma</h2>
                <button class="close-modal" onclick="closeModal('modal-view-idioma')">✕</button>
            </div>
            <div style="padding: 10px 0;">
                <div class="user-detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">ID</span>
                        <span class="detail-value">#${data.id}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nombre</span>
                        <span class="detail-value">${data.nombre || 'N/A'}</span>
                    </div>
                    <div class="detail-item" style="grid-column: span 2;">
                        <span class="detail-label">Código ISO</span>
                        <span class="detail-value"><span class="badge badge-info">${data.codigo_iso || 'N/A'}</span></span>
                    </div>
                </div>
                <div style="margin-top: 20px; text-align: center; display: flex; gap: 10px; justify-content: center;">
                    <button class="btn btn-secondary" onclick="closeModal('modal-view-idioma')">Cerrar</button>
                    <button class="btn btn-success" onclick="closeModal('modal-view-idioma'); editIdioma(${data.id})">✏️ Editar</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal('modal-view-idioma');
        }
    });
}

function editIdioma(id) {
    console.log('✏️ Editando registro:', id);
    fetch(`${PHP_URL}?action=get&id=${id}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                const data = result.data;
                const form = document.getElementById('form-idioma');
                
                form.querySelector('[name="nombre"]').value = data.nombre || '';
                form.querySelector('[name="codigo_iso"]').value = data.codigo_iso || '';
                
                editMode = true;
                editId = id;
                
                document.querySelector('.section-card h3').textContent = '✏️ Editar idioma';
                document.querySelector('.btn-success').textContent = '📝 Actualizar Registro';
                
                document.querySelector('.section-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
                showInfo('📝 Editando registro #' + id);
            } else {
                showError('❌ ' + (result.message || 'Error al cargar'));
            }
        })
        .catch(error => showError('❌ Error: ' + error.message));
}

function deleteIdioma(id) {
    console.log('🗑️ Eliminando registro:', id);
    let nombre = '';
    const rows = document.querySelectorAll('.data-table tbody tr');
    rows.forEach(row => {
        const firstCell = row.querySelector('td:first-child');
        if (firstCell && firstCell.textContent.trim() === String(id)) {
            const cells = row.querySelectorAll('td');
            if (cells.length > 1) {
                nombre = cells[1].textContent.trim();
            }
        }
    });
    
    const mensaje = nombre ? `¿Eliminar idioma "${nombre}"?` : `¿Eliminar registro #${id}?`;
    
    if (confirm(mensaje + '\n\n⚠️ Esta acción no se puede deshacer.')) {
        fetch(`${PHP_URL}?action=delete`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showSuccess('✅ ' + (result.message || 'Registro eliminado'));
                loadIdiomas();
            } else {
                showError('❌ ' + (result.message || 'Error al eliminar'));
            }
        })
        .catch(error => showError('❌ Error: ' + error.message));
    }
}

function resetForm() {
    console.log('🔄 Resetear formulario');
    editMode = false;
    editId = null;
    
    const form = document.getElementById('form-idioma');
    if (form) {
        form.reset();
        form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    }
    
    document.querySelector('.section-card h3').textContent = '➕ Agregar nuevo idioma';
    document.querySelector('.btn-success').textContent = '💾 Guardar Idioma';
    
    const errorGeneral = document.getElementById('error-general');
    if (errorGeneral) {
        errorGeneral.innerHTML = '';
        errorGeneral.style.display = 'none';
    }
}

// ============================================
// FUNCIONES DE UI - MENSAJES
// ============================================

function showError(message) {
    console.log('❌ Error:', message);
    const container = document.getElementById('error-general');
    if (container) {
        container.innerHTML = `<div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; padding: 12px; color: #721c24;">${message}</div>`;
        container.style.display = 'block';
        setTimeout(() => { container.style.display = 'none'; }, 6000);
    }
}

function showSuccess(message) {
    console.log('✅ Éxito:', message);
    const container = document.getElementById('error-general');
    if (container) {
        container.innerHTML = `<div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; padding: 12px; color: #155724;">${message}</div>`;
        container.style.display = 'block';
        setTimeout(() => { container.style.display = 'none'; }, 5000);
    }
}

function showInfo(message) {
    console.log('ℹ️ Info:', message);
    const container = document.getElementById('error-general');
    if (container) {
        container.innerHTML = `<div style="background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; padding: 12px; color: #0c5460;">${message}</div>`;
        container.style.display = 'block';
        setTimeout(() => { container.style.display = 'none'; }, 5000);
    }
}

function showErrors(errors) {
    const container = document.getElementById('error-general');
    if (!container) return;
    
    let html = '<div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; padding: 12px;"><ul style="margin:0;padding-left:20px;color:#721c24;">';
    if (Array.isArray(errors)) {
        errors.forEach(error => {
            html += `<li>❌ ${typeof error === 'string' ? error : error.message || error}</li>`;
        });
    } else if (typeof errors === 'object') {
        Object.values(errors).forEach(msg => {
            html += `<li>❌ ${msg}</li>`;
        });
    }
    html += '</ul></div>';
    
    container.innerHTML = html;
    container.style.display = 'block';
    setTimeout(() => { container.style.display = 'none'; }, 8000);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.remove();
}

function imprimirListado() {
    console.log('🖨️ Imprimiendo listado...');
    const tabla = document.querySelector('.data-table');
    if (!tabla) return;
    
    const ventana = window.open('', '_blank');
    const estilo = `
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #2c3e50; color: white; }
            .badge { padding: 3px 8px; border-radius: 12px; font-size: 12px; }
            .badge-info { background: #d1ecf1; color: #0c5460; }
        </style>
    `;
    
    ventana.document.write(`
        <html>
            <head><title>Listado de Idiomas</title>${estilo}</head>
            <body>
                <h1>📋 Listado de Idiomas</h1>
                <p>Fecha: ${new Date().toLocaleDateString()}</p>
                ${tabla.outerHTML}
            </body>
        </html>
    `);
    ventana.document.close();
    ventana.print();
}

// ============================================
// EXPORTAR FUNCIONES GLOBALES
// ============================================
console.log('📦 Exportando funciones globales...');

window.loadIdiomas = loadIdiomas;
window.editIdioma = editIdioma;
window.deleteIdioma = deleteIdioma;
window.viewIdioma = viewIdioma;
window.resetForm = resetForm;
window.closeModal = closeModal;
window.imprimirListado = imprimirListado;
window.filterTable = filterTable;

console.log('✅ Funciones exportadas correctamente');
console.log('📌 loadIdiomas disponible:', typeof window.loadIdiomas);