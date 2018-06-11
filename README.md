# Standard CRUD operations database handler

This is my Database class to make it easier to do standard CRUD database operations. It has support for selecting, inserting, updating, deleting, get row count, get last inserted row's id and get last executed SQL statement. It comes with some nice functionality, such as automatic prepared statements with binding of all variables on every SQL query to protect the database from malicious code. 

As establishing a connection with a database can be time consuming and slow down an application so the class also keeps track of the database connection in a static field to make sure only 1 connection is ever created through the application.

The only setup needed is to provide the Database class with your database details in the constants `HOST, DB_NAME, USERNAME, PASSWORD` in the top of the Database field and your ready to used it;

The `PDO::ATTR_ERRORMODE` attribute is set to `PDO::ERRMODE_SILENT`. If error reporting is needed this can be changed to `PDO::ERRMODE_EXCEPTION` in the class constructor.
___
### Initiating
##### Initiate a database connection by creating a new Database() object.
```php 
require_once('Database.php');
$db = new Database(); 
```
___
### Select
##### Select rows from a database table
```php
$db->select(string $table, array $where=[], string $columns="*", string $whereMode="AND", 
            string $order="",string $limit="", array $dataTypes=[], string $returnType="object", 
            string $returnClass="");
```
| Parameter | Description |
|--|--|
| string $table | Database Table. |
| array  $where | Optional: Array holding the filters/'WHERE' clause for the query. |
| string $columns | Optional: the column to select (SELECT count(*) FROM ...), defaults to *. |
| string $whereMode | Optional: Add an 'AND' or 'OR' after each item in the $where array, defaults to AND. |
| string $order | Optional: string holding the 'ORDER BY' clause.  
| string $limit | Optional: string holding the 'LIMIT' clause. |
| array  $dataTypes | Optional: Pass in data types as an array in equal order to the $where. | 
| | Options: int/integer, bool/boolean, str/string. |
| | Data type will default to string if nothing is passed in (PDO::PARAM_STR).| 
| string $returnType | Optional: Choose data type to get returned result as. |
| | Options: obj/object, class, array/arr/assoc. Defaults to object (PDO::FETCH_OBJ). |
| | Remember to set $returnClass if class is chosen or return type will be set to object. | 
| string $returnClass | Optional: Class to return data as when class is chosen as $returnType. |
| &nbsp; | &nbsp; |
| return mixed       | Returns as object, class or array based on $returnType choice. |
##### Example:
```php
$db->select("users", array("id" => 55, "firstName" => "Raymond"), "*", "OR", 
            "ORDER BY ID ASC", "LIMIT 5", array("int", "str"), "Class", "TestClass");
```
___
### Insert
##### Insert data into a database table
```php
$db->insert(string $table, Array $columnsData, Array $dataTypes=[]);
```
| Parameter | Description |
|--|--|
| string $table | Database Table. |
| array  $columnsData | Array of columns and data to insert to the assign columns. |
| array  $dataTypes | Optional: Pass in data types as an array in equal order to $columnsData. |
| | Options: int/integer, bool/boolean, str/string. |
| | Data type will default to string if nothing is passed in (PDO::PARAM_STR). |
| &nbsp; | &nbsp; |
| return boolean | True = Success, False = Error. |
##### Example:
```php
$db->insert("users",
            Array("firstName" => "Raymond",
                  "lastName" => "Johannessen",
                  "email" => "mail@rajohan.no"),
            Array("str", "str", "str"));
```
___
### Update
##### Update one or more rows of a database table
```php
$db->update(string $table, array $columnsData, array $where=[], 
            string $whereMode="AND", Array $dataTypes=[]);
```
| Parameter | Description |
|--|--|
| string $table | Database Table.|
| array  $columnsData | Array of columns and data to insert to the assign columns. |
| array  $where | Optional: Array holding the filters/'WHERE' clause for the query. |
| string $whereMode | Optional: Add an 'AND' or 'OR' after each item in the $where array, defaults to AND. |
| array  $dataTypes | Optional: Pass in data types as an array in equal order to $columnsData. |
| | Options: int/integer, bool/boolean, str/string. |
| | Data type will default to string if nothing is passed in (PDO::PARAM_STR). |
| &nbsp; | &nbsp; |
| return boolean | True = Success, False = Error. |
##### Example:
```php
$db->update("users",
            Array("firstName" => "Raymond",
                  "lastName" => "Johannessen",
                  "email" => "mail@rajohan.no"),
            Array("id" => 1,
                  "username" => "Rajohan"),
            "OR",
            Array("str", "str", "str", "int", "str"));
```
___
### Delete
##### Remove one or more rows from a database table
```php
$db->delete(string $table, array $where=[], string $whereMode="AND", Array $dataTypes=[]);
```
| Parameter | Description |
|--|--|
| string $table | Database Table. |
| array  $where | Optional: Array holding the filters/'WHERE' clause for the query. |
| string $whereMode | Optional: Add an 'AND' or 'OR' after each item in the $where array, defaults to AND. |
| array  $dataTypes | Optional: Pass in data types as an array in equal order to $where. |
| | Options: int/integer, bool/boolean, str/string. |
| | Data type will default to string if nothing is passed in (PDO::PARAM_STR). |
| &nbsp; | &nbsp; |
| return boolean | True = Success, False = Error. |
##### Example:
```php
$db->delete("users",
            Array("id" => 1,
                  "username" => "Rajohan"),
            "OR",
            Array("int", "str"));
```
___
### Count
##### Get row count from a database table
```php
$db->count(string $table, Array $where=[], $whereMode="AND", string $columns="*", Array $dataTypes=[]);
```
| Parameter | Description |
|--|--|
| string $table | Database Table. |
| array  $where | Optional: Array holding the filters/'WHERE' clause for the query. |
| string $whereMode | Optional: Add an 'AND' or 'OR' after each item in the $where array, defaults to AND. |
| string $columns | Optional: the column to select (SELECT count(*) FROM ...), defaults to *. |
| array  $dataTypes | Optional: Pass in data types as an array in equal order to the $where. |
| | Options: int/integer, bool/boolean, str/string. |
| | Data type will default to string if nothing is passed in (PDO::PARAM_STR). |
| &nbsp; | &nbsp; |
| return integer | Row count. |
##### Example:
```php
$db->count("users",
           Array("id" => 1,
           "firstName" => "Raymond"),
           "OR",
           "*",
           Array("int", "str"));
```
___
### Id
##### Last inserted row's id
```php
$db->id();
```
___
### Sql
##### Get last used query
```php
$db->sql();
```
___
### CloseConnection
##### Close the database connection
```php
$db->closeConnection();
```
___
### Licensed under the MIT License.
