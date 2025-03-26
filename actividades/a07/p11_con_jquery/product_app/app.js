$(document).ready(function () {
    let edit = false;

    // Ocultar la barra de resultados al cargar la página
    $('#product-result').hide();

    // Listar productos al cargar la página
    listarProductos();

    // Función para listar productos
    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    let template = '';
                    response.forEach(producto => {
                        let descripcion = `
                            <li>precio: ${producto.precio}</li>
                            <li>unidades: ${producto.unidades}</li>
                            <li>modelo: ${producto.modelo}</li>
                            <li>marca: ${producto.marca}</li>
                            <li>detalles: ${producto.detalles}</li>
                        `;
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#products').html(template);
                } else {
                    $('#products').html('<tr><td colspan="4">No hay productos disponibles</td></tr>');
                }
            },
            error: function(xhr) {
                console.error("Error al cargar productos:", xhr.responseText);
                $('#products').html('<tr><td colspan="4">Error al cargar los productos</td></tr>');
            }
        });
    }

    // Función para mostrar errores
    function mostrarError(input, mensaje) {
        let errorSpan = $(input).next(".error-text");
        if (errorSpan.length === 0) {
            errorSpan = $("<span>").addClass("error-text").css("color", "red");
            $(input).after(errorSpan);
        }
        errorSpan.text(mensaje);
        $(input).addClass("is-invalid");
    }

    // Función para limpiar errores
    function limpiarError(input) {
        $(input).next(".error-text").remove();
        $(input).removeClass("is-invalid");
    }

    function validarNombreExistente(nombre) {
        $.ajax({
            url: './backend/product-validate-name.php',
            type: 'GET',
            data: { nombre: nombre },
            success: function (response) {
                let data = JSON.parse(response);
                let estadoNombre = $(".estado-nombre");
    
                if (data.status === 'error') {
                    estadoNombre.text("❌ " + data.message).css("color", "red");
                } else {
                    estadoNombre.text("✅ " + data.message).css("color", "green");
                }
            },
            error: function () {
                console.log("Error en la validación del nombre.");
            }
        });
    }
    
    // Vincular la validación al evento input (cuando el usuario escribe)
    $("#nombre").on("input", function () {
        let nombre = $(this).val().trim();
        if (nombre.length >= 3) { // Validar solo si el nombre tiene 3 o más caracteres
            validarNombreExistente(nombre);
        } else {
            $(".estado-nombre").text("").css("color", ""); // Limpiar el mensaje si el nombre es muy corto
        }
    });

    // Validaciones individuales
    function validarNombre() {
        let nombre = $("#nombre").val().trim();
        let nombreOriginal = $("#nombre").data("original"); // Obtener nombre original
        let estadoNombre = $(".estado-nombre");
    
        if (nombre === "" || nombre.length > 100) {
            mostrarError("#nombre", "El nombre es obligatorio y debe tener máximo 100 caracteres.");
            estadoNombre.text("❌ El nombre es inválido.").css("color", "red");
            return false;
        }
    
        // ⚡ Si está en modo edición y el nombre no cambió, permitirlo
        if (edit && nombre === nombreOriginal) {
            limpiarError("#nombre");
            estadoNombre.text("✅ El nombre no ha cambiado.").css("color", "green");
            return true;
        }
    
        // ⚠️ Validar si el nombre existe en la base de datos
        let nombreExiste = false;
        $.ajax({
            url: './backend/product-validate-name.php',
            type: 'GET',
            data: { nombre: nombre },
            async: false,
            success: function (response) {
                let data = JSON.parse(response);
                if (data.status === 'error') {
                    nombreExiste = true;
                    estadoNombre.text("❌ " + data.message).css("color", "red");
                } else {
                    estadoNombre.text("✅ " + data.message).css("color", "green");
                }
            },
            error: function () {
                console.log("Error en la validación del nombre.");
            }
        });
    
        // ❌ Si el nombre ya existe y no estamos en edición, bloquearlo
        if (!edit && nombreExiste) {
            return false;
        }
    
        limpiarError("#nombre");
        return true;
    }
    

    function validarModelo() {
        let modelo = $("#modelo").val().trim();
        let estadoModelo = $(".estado-modelo");
    
        if (!/^[a-zA-Z0-9\s]+$/.test(modelo) || modelo.length > 25) {
            mostrarError("#modelo", "El modelo debe ser alfanumérico y tener máximo 25 caracteres.");
            estadoModelo.text("❌ El modelo es inválido.").css("color", "red");
            return false;
        } else {
            limpiarError("#modelo");
            estadoModelo.text("✅ El modelo es válido.").css("color", "green");
            return true;
        }
    }
    
    function validarPrecio() {
        let precio = parseFloat($("#precio").val());
        let estadoPrecio = $(".estado-precio");
    
        if (isNaN(precio) || precio <= 99.99) {
            mostrarError("#precio", "El precio debe ser mayor a 99.99.");
            estadoPrecio.text("❌ El precio es inválido.").css("color", "red");
            return false;
        } else {
            limpiarError("#precio");
            estadoPrecio.text("✅ El precio es válido.").css("color", "green");
            return true;
        }
    }
    
    function validarUnidades() {
        let unidades = parseInt($("#unidades").val());
        let estadoUnidades = $(".estado-unidades");
    
        if (isNaN(unidades) || unidades < 0) {
            mostrarError("#unidades", "Las unidades deben ser 0 o más.");
            estadoUnidades.text("❌ Las unidades son inválidas.").css("color", "red");
            return false;
        } else {
            limpiarError("#unidades");
            estadoUnidades.text("✅ Las unidades son válidas.").css("color", "green");
            return true;
        }
    }
    
    function validarMarca() {
        let marca = $("#marca").val();
        let estadoMarca = $(".estado-marca");
    
        if (marca === "" || marca === "NA") {
            mostrarError("#marca", "Debes seleccionar una marca válida.");
            estadoMarca.text("❌ La marca es inválida.").css("color", "red");
            return false;
        } else {
            limpiarError("#marca");
            estadoMarca.text("✅ La marca es válida.").css("color", "green");
            return true;
        }
    }
    
    function validarImagen() {
        let imagen = $("#imagen").val().trim();
        let estadoImagen = $(".estado-imagen");
    
        // Si el campo de imagen está vacío, asignar una imagen por defecto
        if (!imagen) {
            $("#imagen").val("http://localhost/tecweb/practicas/p09/img/imagen.png");
            limpiarError("#imagen");
            estadoImagen.text("✅ Se asignó una imagen por defecto.").css("color", "green");
            return true;
        }
    
        limpiarError("#imagen");
        return true;
    }

    // Validar el formulario completo
    function validarFormulario() {
        return (
            validarNombre() &&
            validarModelo() &&
            validarPrecio() &&
            validarUnidades() &&
            validarMarca() &&
            validarImagen()
        );
    }

    // Vincular validaciones al evento blur
    $("#nombre").on("blur", validarNombre);
    $("#modelo").on("blur", validarModelo);
    $("#precio").on("blur", validarPrecio);
    $("#unidades").on("blur", validarUnidades);
    $("#marca").on("blur", validarMarca);
    $("#imagen").on("blur", validarImagen);

    // Enviar formulario
    $('#product-form').submit(e => {
        e.preventDefault();
    
        if (!validarFormulario()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, corrige los errores en el formulario'
            });
            return;
        }
    
        const formData = {
            nombre: $('#nombre').val(),
            marca: $('#marca').val(),
            modelo: $('#modelo').val(),
            precio: $('#precio').val(),
            detalles: $('#detalles').val(),
            unidades: $('#unidades').val(),
            imagen: $('#imagen').val() || 'http://localhost/tecweb/practicas/p09/img/imagen.png'
        };
    
        if (edit) {
            formData.id = $('#productId').val();
        }
    
        const url = edit ? './backend/product-edit.php' : './backend/product-add.php';
    
        // Mostrar loader mientras se procesa
        Swal.fire({
            title: edit ? 'Actualizando producto...' : 'Agregando producto...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        if (response.status === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Resetear y actualizar
                                edit = false;
                                $('#product-form')[0].reset();
                                $('#productId').val('');
                                $('button.btn-primary').text("Agregar Producto");
                                listarProductos(); // Actualizar la lista
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Operación fallida'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo comunicar con el servidor'
                        });
                        console.error("Error:", xhr.responseText);
                    }
                });
            }
        });
    });

    $('#search-form').submit(function (e) {
    e.preventDefault();
    console.log("Búsqueda iniciada"); // Verifica que el evento se esté ejecutando
    let search = $('#search').val();
    console.log("Término de búsqueda:", search); // Verifica el término de búsqueda

    if (search) {
        $.ajax({
            url: './backend/product-search.php',
            type: 'GET',
            data: { search: search },
            success: function (response) {
                console.log("Respuesta del servidor:", response); // Verifica la respuesta del servidor
                if (!response.error) {
                    const productos = JSON.parse(response);
                    console.log("Productos encontrados:", productos); // Verifica los productos encontrados
                    if (Object.keys(productos).length > 0) {
                        let template = '';
                        let template_bar = '';

                        productos.forEach(producto => {
                            let descripcion = `
                                <li>precio: ${producto.precio}</li>
                                <li>unidades: ${producto.unidades}</li>
                                <li>modelo: ${producto.modelo}</li>
                                <li>marca: ${producto.marca}</li>
                                <li>detalles: ${producto.detalles}</li>
                            `;
                            template += `
                                <tr productId="${producto.id}">
                                    <td>${producto.id}</td>
                                    <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                    <td><ul>${descripcion}</ul></td>
                                    <td>
                                        <button class="product-delete btn btn-danger">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            `;

                            template_bar += `<li>${producto.nombre}</li>`;
                        });

                        // Mostrar resultados
                        $('#product-result').show();
                        $('#container').html(template_bar);
                        $('#products').html(template);
                    } else {
                        $('#products').html('<tr><td colspan="4">No se encontraron productos.</td></tr>');
                        $('#product-result').hide();
                    }
                }
            },
            error: function () {
                console.log("Error en la búsqueda.");
            }
        });
    } else {
        $('#product-result').hide();
        listarProductos(); // Mostrar todos los productos si el campo de búsqueda está vacío
    }
});

    // Eliminar producto
    $(document).on('click', '.product-delete', function(e) {
        e.preventDefault();
        
        const element = $(this).closest('tr');
        const id = element.attr('productId');
        const productName = element.find('.product-item').text();
        
        Swal.fire({
            title: `¿Eliminar "${productName}"?`,
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        
                        $.ajax({
                            url: './backend/product-delete.php',
                            type: 'POST',
                            data: { id: id },
                            dataType: 'json',
                            success: function(response) {
                                Swal.close();
                                if (response.status === "success") {
                                    Swal.fire(
                                        '¡Eliminado!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        listarProductos(); // Actualizar la lista
                                    });
                                } else {
                                    Swal.fire(
                                        'Error',
                                        response.message || 'Error al eliminar',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error',
                                    'Error de conexión con el servidor',
                                    'error'
                                );
                                console.error("Error:", xhr.responseText);
                            }
                        });
                    }
                });
            }
        });
    });

    // Editar producto
    $(document).on('click', '.product-item', (e) => {
        const element = $(e.target).closest('tr'); // Obtiene la fila del producto
        const id = $(element).attr('productId'); // Obtiene el ID del producto
        $.post('./backend/product-single.php', { id }, (response) => {
            let product = JSON.parse(response);
            $('#nombre').val(product.nombre).data("original", product.nombre); // Guardar nombre original
            $('#marca').val(product.marca);
            $('#modelo').val(product.modelo);
            $('#precio').val(product.precio);
            $('#detalles').val(product.detalles);
            $('#unidades').val(product.unidades);
            $('#imagen').val(product.imagen);
            $('#productId').val(product.id); // Llenar el campo oculto con el ID
            edit = true; // Activa el modo edición
            $('button.btn-primary').text("Modificar Producto"); // Cambia el texto del botón
        });
        e.preventDefault();
    });

    function showNotification(message, type = 'success') {
        // Eliminar notificaciones anteriores para evitar acumulación
        $('.notification').remove();
        
        // Crear la notificación
        const notification = $(`
            <div class="notification ${type}">
                ${message}
            </div>
        `);
        
        // Añadir al cuerpo del documento
        $('body').append(notification);
        
        // Eliminar después de 3 segundos
        setTimeout(() => {
            notification.fadeOut(300, () => {
                notification.remove();
            });
        }, 3000);
    }
});