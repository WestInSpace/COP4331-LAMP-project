# Database Documentation

The project uses a MySQL database called `ColorsAppDB`. It currently has two tables: `Users` and `Colors`.

## Users

Stores the information for each user.

| Column | Type |
|---|---|
| ID | INT (Primary Key, Auto Increment) |
| FirstName | VARCHAR(50) |
| LastName | VARCHAR(50) |
| Login | VARCHAR(50) |
| Password | VARCHAR(50) |

## Colors

Stores the colors added by each user.

| Column | Type |
|---|---|
| ID | INT (Primary Key, Auto Increment) |
| Name | VARCHAR(50) |
| UserID | INT |

`UserID` is used to associate a color with a user.

## SQL Files

- `create_tables.sql` - creates the database and tables
- `seed_data.sql` - adds test data
- `resetdb.sql` - resets the database

The database uses MySQL with InnoDB and the utf8mb4 character set.
