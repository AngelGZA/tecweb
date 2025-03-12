$(function(){
    console.log('jQuery is Working');
    init();
});
let edit = false;
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

function init() {
    var JsonString = JSON.stringify(baseJSON, null, 2);
    $("#description").val(JsonString);
    listarProductos();
}

function listarProductos() {
    $.getJSON('./backend/product-list.php', function(productos) {
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
                    <td>
                    <a href="#" class="product-item">${producto.nombre}</a>
                    </td>
                    <td><ul>${descripcion}</ul></td>
                    <td>
                        <button class="product-delete btn btn-danger">Eliminar</button>
                    </td>
                </tr>
            `;
        });
        $("#products").html(template);
    });
}

$("#search").on("input", function() {
    let search = $(this).val();
    $.getJSON(`./backend/product-search.php?search=${search}`, function(productos) {
        let template = '', template_bar = '';
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
                    <td>${producto.nombre}</td>
                    <td><ul>${descripcion}</ul></td>
                    <td>
                        <button class="product-delete btn btn-danger">Eliminar</button>
                    </td>
                </tr>
            `;
            template_bar += `<li>${producto.nombre}</li>`;
        });
        $("#product-result").removeClass("d-none");
        $("#container").html(template_bar);
        $("#products").html(template);
    });
});

$("#product-form").submit(function(e) {
    e.preventDefault();
    let productoJsonString = $("#description").val();
    let finalJSON = JSON.parse(productoJsonString);
    finalJSON['nombre'] = $("#name").val();
    finalJSON['id'] = $("#productId").val();
    productoJsonString = JSON.stringify(finalJSON, null, 2);
    
    let url1 = edit === false ? './backend/product-add.php' : './backend/product-edit.php';
    console.log(url1)

    $.ajax({
        url: url1,
        type: 'POST',
        contentType: "application/json;charset=UTF-8",
        data: productoJsonString,
        success: function(response) {
            let respuesta = JSON.parse(response);
            let template_bar = `
                <li style="list-style: none;">status: ${respuesta.status}</li>
                <li style="list-style: none;">message: ${respuesta.message}</li>
            `;
            $("#product-result").removeClass("d-none");
            $("#container").html(template_bar);
            
            if (respuesta.status !== "error") {
                listarProductos();
                edit = false; // Reiniciar el modo edición
                $("#product-form").trigger("reset"); // Limpiar el formulario
            }
        }
    });
});

$(document).on("click", ".product-delete", function() {
    if(confirm("De verdad deseas eliminar el Producto?")) {
        let id = $(this).closest("tr").attr("productId");
        $.get(`./backend/product-delete.php?id=${id}`, function(response) {
            let respuesta = JSON.parse(response);
            let template_bar = `
                <li style="list-style: none;">status: ${respuesta.status}</li>
                <li style="list-style: none;">message: ${respuesta.message}</li>
            `;
            $("#product-result").removeClass("d-none");
            $("#container").html(template_bar);
            listarProductos();
        });
    }
});

$(document).on("click", ".product-item", function() {
    let element = $(this)[0].parentElement.parentElement;
    let id = $(element).attr("productId");

    $.post("./backend/product-single.php", { id }, function(response) {
        const producto = JSON.parse(response);

        // Estructurar la descripción en formato JSON
        let descripcionJson = {
            "precio": producto.precio,
            "unidades": producto.unidades,
            "modelo": producto.modelo,
            "marca": producto.marca,
            "detalles": producto.detalles,
            "imagen": producto.imagen
        };

        $("#name").val(producto.nombre);
        $("#description").val(JSON.stringify(descripcionJson, null, 2)); // Formateado bonito
        $("#productId").val(producto.id);
        edit = true;

    });
});