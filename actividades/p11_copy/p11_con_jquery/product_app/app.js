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
            success: function (response) {
                const productos = JSON.parse(response);
                if (Object.keys(productos).length > 0) {
                    let template = '';
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
                    });
                    $('#products').html(template);
                }
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

        let idProducto = $('#productId').val();
        console.log("ID del producto:", idProducto); // Verifica si se está enviando el ID

        if (edit === true && (idProducto === "" || idProducto === undefined)) {
            alert("Error: No se encontró el ID del producto.");
            return;
        }
        if (!validarFormulario()) {
            alert("Por favor, corrige los errores antes de agregar el producto.");
            return;
        }
    
        // Crear un objeto con los datos del formulario
        let postData = {
            nombre: $('#nombre').val(),
            marca: $('#marca').val(),
            modelo: $('#modelo').val(),
            precio: $('#precio').val(),
            detalles: $('#detalles').val(),
            unidades: $('#unidades').val(),
            imagen: $('#imagen').val(),
            id: $('#productId').val() // Asegúrate de que el ID se esté enviando
        };
    
        console.log("Datos enviados:", postData); // Imprime los datos en la consola
    
        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';
    
        $.post(url, postData, (response) => {
            console.log("Respuesta del servidor:", response); // Imprime la respuesta en la consola
            try {
                let respuesta = JSON.parse(response);
                if (respuesta.status === "success") {
                    // Mostrar mensaje de éxito
                    let template_bar = `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;
                    $('#product-result').show();
                    $('#container').html(template_bar);
    
                    // Volver a cargar la lista de productos
                    listarProductos();
    
                    // Reiniciar el formulario
                    edit = false;
                    $('button.btn-primary').text("Agregar Producto");
                    $('#product-form')[0].reset();
                } else {
                    alert(respuesta.message); // Mostrar mensaje de error
                }
            } catch (error) {
                console.error("Error al parsear la respuesta:", error);
                console.error("Respuesta del servidor:", response);
            }
        });
    });

    // Eliminar producto
    $(document).on('click', '.product-delete', (e) => {
        if (confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(e.target).closest('tr'); // Obtiene la fila del producto
            const id = $(element).attr('productId');
            $.post('./backend/product-delete.php', { id }, (response) => {
                $('#product-result').hide();
                listarProductos();
            });
        }
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
});