# Database Documentation

The project uses a MySQL database called `ContactsAppDB`. It has two tables: `Users` and `Contacts`.

## Users

Stores the information for each user.

| Column | Type |
|---|---|
| ID | INT (Primary Key, Auto Increment) |
| FirstName | VARCHAR(50) |
| LastName | VARCHAR(50) |
| Login | VARCHAR(50) |
| Password | VARCHAR(255) |
| Role | ENUM (Admin or User) |
| IsDisabled | BOOLEAN |

`Login` must be unique. Passwords are stored as hashes by the application.

## Contacts

Stores the contacts added by each user.

| Column | Type |
|---|---|
| ID | INT (Primary Key, Auto Increment) |
| FirstName | VARCHAR(50) |
| LastName | VARCHAR(50) |
| Phone | VARCHAR(25) |
| Email | VARCHAR(100) |
| UserID | INT |

`UserID` connects each contact to the user who owns it.

## SQL Files

- `create_tables.sql` - creates the database and tables
- `seed_data.sql` - adds test data
- `resetdb.sql` - resets the database

The database uses MySQL with InnoDB and the utf8mb4 character set.
