---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#Authentication


Registers a new user
<!-- START_3157fb6d77831463001829403e201c3e -->
## Handle a registration request for the application.

Creates a user into the application

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/auth/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"first_name":"Francis","last_name":"Etoka","username":"etoks","email":"francis.dretoka@gmail.com","password":"12345678","password_confirmation":"12345678"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "Francis",
    "last_name": "Etoka",
    "username": "etoks",
    "email": "francis.dretoka@gmail.com",
    "password": "12345678",
    "password_confirmation": "12345678"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": true,
    "message": "Successful created",
    "data": {}
}
```

### HTTP Request
`POST api/v1/auth/register`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `first_name` | string |  required  | User's first name.
        `last_name` | string |  required  | User's last name.
        `username` | string |  required  | User's username.
        `email` | string |  required  | User's email.
        `password` | string |  required  | User's password.
        `password_confirmation` | string |  required  | User's password confirmation.
    
<!-- END_3157fb6d77831463001829403e201c3e -->

#general


<!-- START_cb859c8e84c35d7133b6a6c8eac253f8 -->
## Show the application dashboard.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/home" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/home"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET home`


<!-- END_cb859c8e84c35d7133b6a6c8eac253f8 -->


