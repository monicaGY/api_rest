<em> ROLE_USER </em>

* List products -> METHOD:GET *
https://127.0.0.1:8000/api/doctrine/client/products

* List sections -> METHOD:GET *
https://127.0.0.1:8000/api/doctrine/client/sections

* List products by section -> METHOD:GET *
https://127.0.0.1:8000/api/doctrine/client/products/section/{id_section}


<em> ROLE_ADMIN </em>

* Create a product -> METHOD:POST *
https://127.0.0.1:8000/api/doctrine/admin/create/product
body => json
{
    "nombre":"nombre",
    "seccion_id": 1,
    "precio":1
}

* Create a section -> METHOD:POST *
https://127.0.0.1:8000/api/doctrine/admin/create/section
body => json
{
    "nombre":"nombre",
}