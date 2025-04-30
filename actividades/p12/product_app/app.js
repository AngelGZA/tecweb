$(document).ready(function() {
    let edit = false;
    const API_BASE = 'http://localhost/tecweb/actividades/p12/product_app/backend';

    // Función para mostrar notificaciones
    function showAlert(message, type = 'success') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        $('#alerts-container').html(alertHtml);
        setTimeout(() => $('.alert').alert('close'), 3000);
    }

    // Listar productos al cargar la página
    listarProductos();

    // FUNCIÓN PARA LISTAR PRODUCTOS
    function listarProductos() {
        $.get(`${API_BASE}/products`, function(response) {
            try {
                const productos = JSON.parse(response);
                let template = '';
                
                if (productos && productos.length > 0) {
                    productos.forEach(producto => {
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td>
                                    <ul>
                                        <li>Precio: ${producto.precio}</li>
                                        <li>Unidades: ${producto.unidades}</li>
                                        <li>Modelo: ${producto.modelo}</li>
                                        <li>Marca: ${producto.marca}</li>
                                        <li>Detalles: ${producto.detalles}</li>
                                    </ul>
                                </td>
                                <td>
                                    <button class="product-delete btn btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#products').html(template);
                } else {
                    $('#products').html('<tr><td colspan="4" class="text-center">No hay productos registrados</td></tr>');
                }
            } catch (e) {
                console.error("Error al parsear productos:", e);
                showAlert("Error al cargar productos", "danger");
            }
        }).fail(function() {
            showAlert("Error al conectar con el servidor", "danger");
        });
    }

    // BUSCAR PRODUCTOS
    $('#search').keyup(function() {
        const search = $(this).val().trim();
        if (search) {
            $.get(`${API_BASE}/products`, { search }, function(response) {
                try {
                    const productos = JSON.parse(response);
                    let template = '';

                    if (productos && productos.length > 0) {
                        productos.forEach(producto => {
                            template += `
                                <tr productId="${producto.id}">
                                    <td>${producto.id}</td>
                                    <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                    <td>
                                        <ul>
                                            <li>Precio: ${producto.precio}</li>
                                            <li>Unidades: ${producto.unidades}</li>
                                            <li>Modelo: ${producto.modelo}</li>
                                            <li>Marca: ${producto.marca}</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <button class="product-delete btn btn-danger">Eliminar</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#products').html(template);
                        $('#product-result').show();
                    } else {
                        $('#products').html('<tr><td colspan="4" class="text-center">No se encontraron resultados</td></tr>');
                        $('#product-result').hide();
                    }
                } catch (e) {
                    console.error("Error al buscar productos:", e);
                }
            });
        } else {
            listarProductos();
            $('#product-result').hide();
        }
    });

    // VALIDACIÓN DE CAMPOS
    function validarCampo(elemento) {
        const valor = elemento.val().trim();
        let mensaje = "";

        if (!valor) {
            mensaje = `El campo ${elemento.attr('name') || elemento.attr('id')} es obligatorio`;
        } else {
            switch (elemento.attr('id')) {
                case 'precio':
                    if (isNaN(valor) || parseFloat(valor) <= 0) mensaje = "El precio debe ser un número positivo";
                    break;
                case 'unidades':
                    if (!/^\d+$/.test(valor) || parseInt(valor) <= 0) mensaje = "Las unidades deben ser un entero positivo";
                    break;
            }
        }

        mostrarEstado(mensaje, elemento);
        return !mensaje;
    }

    function mostrarEstado(mensaje, elemento) {
        elemento.next('.invalid-feedback').remove();
        if (mensaje) {
            elemento.addClass('is-invalid');
            elemento.after(`<div class="invalid-feedback text-warning">${mensaje}</div>`);
        } else {
            elemento.removeClass('is-invalid');
        }
    }

    // Validación en tiempo real
    $("#name, #precio, #unidades, #modelo, #marca").blur(function() {
        validarCampo($(this));
    });

    // Validación de imagen
    $("#imagen").blur(function() {
        if (!$(this).val().trim()) {
            $(this).val("default.jpg");
            showAlert("Se asignó imagen por defecto", "info");
        }
    });

    // ENVÍO DEL FORMULARIO
    $('#product-form').submit(function(e) {
        e.preventDefault();
        let valido = true;

        // Validar todos los campos obligatorios
        $("#name, #precio, #unidades, #modelo, #marca").each(function() {
            if (!validarCampo($(this))) valido = false;
        });

        if (!valido) {
            showAlert("Por favor complete todos los campos correctamente", "warning");
            return;
        }

        const formData = {
            nombre: $('#name').val().trim(),
            precio: parseFloat($('#precio').val()),
            unidades: parseInt($('#unidades').val()),
            modelo: $('#modelo').val().trim(),
            marca: $('#marca').val().trim(),
            detalles: $('#detalles').val().trim(),
            imagen: $('#imagen').val().trim(),
            id: $('#productId').val()
        };

        if (edit) {
            // Editar producto (PUT)
            $.ajax({
                url: `${API_BASE}/product`,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(response) {
                    handleResponse(response);
                },
                error: function(xhr) {
                    showAlert(`Error: ${xhr.responseText || 'Error al actualizar'}`, "danger");
                }
            });
        } else {
            // Crear producto (POST)
            $.post(`${API_BASE}/product`, formData)
                .done(handleResponse)
                .fail(function(xhr) {
                    showAlert(`Error: ${xhr.responseText || 'Error al crear'}`, "danger");
                });
        }
    });

    function handleResponse(response) {
        try {
            const res = JSON.parse(response);
            showAlert(res.message || "Operación exitosa");
            
            $('#product-form')[0].reset();
            $('#productId').val('');
            $('button.btn-primary').text("Agregar Producto");
            edit = false;
            listarProductos();
        } catch (e) {
            showAlert("Error al procesar la respuesta", "danger");
        }
    }

    // ELIMINAR PRODUCTO
    $(document).on('click', '.product-delete', function() {
        if (confirm('¿Está seguro de eliminar este producto?')) {
            const id = $(this).closest('tr').attr('productId');
            
            $.ajax({
                url: `${API_BASE}/product/${id}`,
                type: 'DELETE',
                success: function(response) {
                    handleResponse(response);
                },
                error: function(xhr) {
                    showAlert(`Error: ${xhr.responseText || 'Error al eliminar'}`, "danger");
                }
            });
        }
    });

    // EDITAR PRODUCTO (al hacer clic en un producto)
    $(document).on('click', '.product-item', function(e) {
        e.preventDefault();
        const id = $(this).closest('tr').attr('productId');
        
        $.get(`${API_BASE}/product`, { id }, function(response) {
            try {
                const product = JSON.parse(response);
                
                $('#name').val(product.nombre);
                $('#productId').val(product.id);
                $('#precio').val(product.precio);
                $('#unidades').val(product.unidades);
                $('#modelo').val(product.modelo);
                $('#marca').val(product.marca);
                $('#detalles').val(product.detalles);
                $('#imagen').val(product.imagen);
                
                edit = true;
                $('button.btn-primary').text("Modificar Producto");
                showAlert("Producto cargado para edición", "info");
            } catch (e) {
                showAlert("Error al cargar producto", "danger");
            }
        });
    });
});