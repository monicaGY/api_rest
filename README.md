<h1>¿Qué presenta esta API?</h1>
Esta API se caracteriza por la autenticación y autorización. 
Los tipos de usuarios son: 
    - ROLE_USER: que podrán solo listar (productos , secciones y productos por secciones) y modificar productos.
    - ROLE_ADMIN: las acciones asignadas son eliminar productos y crear productos y secciones.

Por otro lado he capturado la excepciones de Symfony para devolverlos en formado JSON mediante un controlador.
En la autenticación, cuando la acción se realiza con éxito le devolverá un token para poder acceder a las urls que le permite según su rol,
se deberá de colocar en el body como " X-AUTH-TOKEN " y caducará al haber pasado 30 minutos.
Mientras en la autorización se encarga de limitar el acceso a las URLS según el rol que presente.
<em> ROLE_USER </em>

* List products -> METHOD:GET *
https://127.0.0.1:8000/api/doctrine/v1/client/products

* List sections -> METHOD:GET *
https://127.0.0.1:8000/api/doctrine/v1/client/sections

* List products by section -> METHOD:GET *
https://127.0.0.1:8000/api/doctrine/v1/client/products/section/{id_section}

* Update a product -> METHOD:PUT *
https://127.0.0.1:8000/api/doctrine/v1/client/products/{id_product}
body => json
{
    "nombre":"nombre",
    "seccion_id": 1,
    "precio":1
}


<em> ROLE_ADMIN </em>

* Create a product -> METHOD:POST *
https://127.0.0.1:8000/api/doctrine/v1/admin/product
body => json
{
    "nombre":"nombre",
    "seccion_id": 1,
    "precio":1
}

* Create a section -> METHOD:POST *
https://127.0.0.1:8000/api/doctrine/v1/admin/section
body => json
{
    "nombre":"nombre",
}

* Delete a product -> METHOD:DELETE*
https://127.0.0.1:8000/api/doctrine/v1/admin/product/{id_product}