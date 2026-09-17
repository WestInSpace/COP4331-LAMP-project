Test connection

Endpoint: GET {{urlBase}}/api/index.php?ping=1

Purpose: Check the status of the api connection.

**Sample response:**  
Status: 200  
{  
  "status": "OK",  
  "timestamp": 1789573494  
}  

---
Login

Endpoint: POST {{urlBase}}/api/index.php

Purpose: Submit user credentials to login.

**Sample body:**  
{  
  "login": "testuser",  
  "password": "TEST_PASSWORD"  
}  

**Sample response on success:**  
Status: 200  
{  
  "id": 2,  
  "firstName": "Test",  
  "lastName": "User",  
  "role": "User",  
  "disabled": 0,  
  "token": "2",  
  "error": ""  
}  

**Sample response on fail:**  
Status: 401  
{  
  "id": 0,  
  "firstName": "",  
  "lastName": "",  
  "role": "",  
  "disabled": null,  
  "error": "Invalid login or password"  
}  

---
Create User

Endpoint: POST {{urlbase}}/api/index.php?action=register

Purpose: Register a new user by providing their first name, last name, username, and password

**Sample Body**

{
  "firstName": "John", 
  "lastName": "Smith", 
  "login": "johnsmith", 
  "password": "test123"
}

**Sample response on success:**
Status 201 Created
{
  "message": "User created successfully", 
  "id": 3, 
  "error": ""
}

**Sample response on duplicate username:**
Status 409 Conflict
{
  "error": "Username already exists"
}

**Sample response on missing required fields:**
Status 400 Bad Request
{
  "error": "All fields are required"
}

---
Search Contacts

Endpoint: GET {{urlBase}}/api/index.php?q=Name

Purpose: Search for a user's contact by first name and return matching results

**Sample params:**  
q=J

**Sample response on success:**  
Status: 200  
{  
  "results": [  
    "Jane",  
    "John"  
  ],  
  "contacts": [  
    {  
      "id": 2,  
      "name": "Jane",  
      "LastName": "Doe",  
      "Phone": "407-555-0102",  
      "Email": "jane@example.com"  
    },  
    {  
      "id": 1,  
      "name": "John",  
      "LastName": "Smith",  
      "Phone": "407-555-0101",  
      "Email": "john@example.com"  
    }  
  ],  
  "error": ""  
}  

**Sample response on no results:**  
Status: 200  
{  
  "results": [],  
  "contacts": [],  
  "error": "No Records Found"  
}  

---
Add contacts

Endpoint: POST {{urlBase}}/api/index.php

Pupose: Add a new contact.

**Example Body:**  
{  
  "firstName": "John",  
  "lastName": "Doe"  
}  

**Example Response on succsses:**  
Status: 200  
{  
  "message": "Contact created",  
  "id": 4,  
  "error": ""  
}

**Example Response on fail:**  
Status: 400  
{  
  "error": "First name or last name is required"  
}

---
Get Contacts by ID

Endpoint: POST {{urlBase}}/api/index.php?id=1

Purpose: Retrive a contact by it's ID.

**Example response on succsses:**  
Status: 200  
{  
  "id": 2,  
  "name": "Jane",  
  "LastName": "Doe",  
  "Phone": "407-555-0102",  
  "Email": "jane@example.com",  
  "user_id": 2  
}  

**Example Response on fail:**  
Status: 404  
{  
  "error": "Contact not found"  
}  

---
Update Contact

Endpoint: PUT {{urlBase}}/api/index.php?id=1

Purpose: Update the information on a contact with the passed id.

**Example paramater:**  
id=1

**Example body:**  
{  
  "firstName": "John",  
  "lastName": "Doe",  
  "phone": "555-555-5555",  
  "email": "fakeEmail@email.com"  
}  

**Example response on succsses:**  
Status: 200  
{  
  "message": "Contact updated",  
  "error": ""  
}  

**Example response on fail:**  
Status: 404  
{  
  "error": "Contact not found"  
}  

**Example response on fail due to missing info:**  
Status: 400  
{  
  "error": "firstName or lastName is required"  
}  

---
Delete contact

Endpoint: DELETE {{urlBase}}/api/index.php?id=4

Purpose: Delete a contact with a given id

**Example paramater:**  
id=1  

**Example response on succsses:**  
Status: 200  
{  
  "message": "Contact deleted",  
  "error": ""  
}  

**Example response on fail:**  
Status: 404  
{  
  "error": "Contact not found"  
}

---

