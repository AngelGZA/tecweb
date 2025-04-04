var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

$(document).ready(function () {

    let edit = false;
    console.log('jQuery is Working');
    listarProductos(); // Listar productos al cargar la página

    // Función para listar productos
    function listarProductos() {
        $.get("./backend/product-list.php", function (data) {
            console.log("Tipo de dato recibido:", typeof data);
            console.log("Respuesta del servidor:", data);
            let productos = JSON.parse(data);
            let template = "";
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
                        <td>
                            <a href="#" class="product-item">${producto.nombre}</a>
                        </td>
                        <td><ul>${descripcion}</ul></td>
                        <td><button class="product-delete btn btn-danger">Eliminar</button></td>
                    </tr>
                `;
            });
            $("#products").html(template);
        });
    }

    // Función de búsqueda de productos
    $("#search").on("input", function () {
        let search = $(this).val();
        $.get("./backend/product-search.php", { search: search }, function (data) {
            let productos = JSON.parse(data);
            let template = "", template_bar = "";
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
                        <td>
                            <a href="#" class="product-item">${producto.nombre}</a>
                        </td>
                        <td><ul>${descripcion}</ul></td>
                        <td><button class="product-delete btn btn-danger">Eliminar</button></td>
                    </tr>
                `;
                template_bar += `<li>${producto.nombre}</li>`;
            });
            $("#container").html(template_bar);
            $("#products").html(template);
            $("#product-result").addClass("d-block");
        });
    });

    $('#name').on('keyup', function() {
        const nombre = $(this).val();

        if (nombre) {
            $.ajax({
                url: './backend/product-search.php', 
                method: 'GET',
                data: { search: nombre },
                success: function(response) {
                    const data = JSON.parse(response);
                    
                    if (data.length > 0) {
                        $('#error-nombre').text('El nombre del producto ya existe en la base de datos').css('color', 'yellow');
                        $('#product-form button').prop('disabled', true);
                    } else {
                        $('#error-nombre').text('');
                        $('#product-form button').prop('disabled', false);
                    }
                },
                error: function() {
                    $('#error-nombre').text('Hubo un error en la validación').css('color', 'red');
                }
            });
        } else {
            $('#error-nombre').text('');
            $('#product-form button').prop('disabled', false);
        }
    });

    $('#product-form').submit(e => {
        e.preventDefault();
        
        // Validaciones
        let mensajeError = "";
        const nombre = $('#name').val(); 
        const precio = $('#form-precio').val();
        const unidades = $('#form-unidades').val();
        const modelo = $('#form-modelo').val();
        const marca = $('#form-marca').val();
        const descripcion = $('#form-descripcion').val();
    
        if(!nombre || nombre.length > 100){
            mensajeError = "El nombre es obligatorio y no puede contener más de 100 caracteres"; 
        }else if(!precio || precio <= 0) {
            mensajeError = "El precio debe ser mayor a 0.";
        } else if (unidades < 1) {
            mensajeError = "Debe haber al menos una unidad.";
        } else if (!modelo || modelo.length > 25) {
            mensajeError = "El modelo no debe exceder los 25 caracteres.";
        } else if (!marca) {
            mensajeError = "Debe seleccionar una marca.";
        } else if (descripcion.length > 250) {
            mensajeError = "La descripción no debe exceder los 250 caracteres.";
        }
        
        if (mensajeError) {
            $('#container').html(`<li style="list-style: none;">${mensajeError}</li>`);
            $("#product-result").addClass("d-block");
            return;
        }
        
        // Preparar los datos del producto en formato JSON
        let postData = JSON.stringify({
            nombre: $('#name').val(),
            precio: precio,
            unidades: unidades,
            modelo: modelo,
            marca: marca,
            detalles: descripcion,
            imagen: $('#imagen_defecto').val(),
            id: $('#productId').val() 
        });
        
        // Determinar la URL dependiendo si estamos editando o agregando
        const url = edit ? './backend/product-edit.php' : './backend/product-add.php';
        
        $.ajax({
            url: url,
            method: 'POST',
            data: postData,
            contentType: 'application/json', // Asegura que el contenido se envíe como JSON
            success: function(response) {
                console.log("Respuesta del servidor:", response); 
                let respuesta = JSON.parse(response);
                let template_bar = `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;
                $("#container").html(template_bar);
                $("#product-result").addClass("d-block");

                $('#product-form').trigger('reset');
                listarProductos();  
                edit = false;  
                $('button.btn-primary').text("Agregar Producto");
               
            }
        });
    });
    

    // Eliminar producto
    $(document).on("click", ".product-delete", function () {
        if (confirm("¿De verdad deseas eliminar el Producto?")) {
            let id = $(this).closest("tr").attr("productId");
            $.get("./backend/product-delete.php", { id: id }, function (response) {
                let respuesta = JSON.parse(response);
                let template_bar = `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;
                $("#container").html(template_bar);
                $("#product-result").addClass("d-block");
                listarProductos();
            });
        }
    });

    // Editar producto
    $(document).on('click', '.product-item', function() {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('productId'); 
    
        $.post('./backend/product-single.php', { id }, function(response) {
            console.log('Respuesta del servidor:', response);  
            let product = JSON.parse(response);  
        
            // Aquí se rellenan los datos del formulario obtenidos de la BD
            $('#name').val(product.nombre);
            $('#form-precio').val(product.precio);
            $('#form-unidades').val(product.unidades);
            $('#form-modelo').val(product.modelo);
            $('#form-marca').val(product.marca);
            $('#form-descripcion').val(product.detalles);
            $('#imagen_defecto').val(product.imagen); 
        
            //id de producto a editar 
            $('#productId').val(product.id);
        
            // Cambiar el texto del botón
            edit = true;
            $('button.btn-primary').text("Modificar Producto");
        });        
    });
    

    init();
});

function init() {
    $("#description").val(JSON.stringify(baseJSON, null, 2));
}