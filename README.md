# Standard CRUD operations database handler v1.3

This is my Database class to make it easier to do standard CRUD database operations. It has support for selecting, searching, inserting, updating, deleting, get row count, get last inserted row's id and get last executed SQL statement. It comes with some nice functionality, such as automatic prepared statements with binding of all variables on every SQL query to protect the database from malicious code. You can also chose if you want the result when selecting to be returned as an object, class or an array

As establishing a connection with a database can be time consuming and slow down an application the class also keeps track of the database connection in a static field to make sure only 1 connection is ever created through the application.

The only setup needed is to provide the Database class with your database details in the constants `HOST, DB_NAME, USERNAME AND PASSWORD` in the top of the Database class. If you are using a other database then MySQL you will have to set up the connection in the constructor of the class appropriately. The class uses PDO so you will also have to make sure the appropriate PDO driver for your database is installed.

`PDO::ATTR_ERRORMODE` is set to `PDO::ERRMODE_SILENT`. If error reporting is needed this can be changed to `PDO::ERRMODE_EXCEPTION` in the class constructor.
___
### Initiating
##### Initiate a database connection by creating a new Database() object. [v1.0]
```php 
require_once('Database.php');
$db = new Database(); 
```
___
### Select
##### Select rows from a database table [v1.0, updated in v1.3]
```php
$db->select(string $table, array $where=[], string $columns="*", string $whereMode="AND", 
            string $order="",string $limit="", array $dataTypes=[], string $dateColumn="",
            array $dates=[], string $returnType="object", string $returnClass="");
```
| Parameter | Description |
|--|--|
| string $table | Database Table. |
| array  $where | Optional: Array holding the filters/'WHERE' clause for the query. |
| string $columns | Optional: the column to select (SELECT * FROM ...), defaults to *. |
| string $whereMode | Optional: Add an 'AND' or 'OR' after each item in the $where array, defaults to AND. |
| string $order | Optional: string holding the 'ORDER BY' clause.  
| string $limit | Optional: string holding the 'LIMIT' clause. |
| array  $dataTypes | Optional: Pass in data types as an array in equal order to the $where. | 
| | Options: int/integer, bool/boolean, str/string. |
| | Data type will default to string if nothing is passed in (PDO::PARAM_STR). | 
| string $dateColumn | Optional: Date column in the table. Will only be used if $dates are provided. |
| array $dates | Optional: Pass in two dates to limit the result to rows between two dates. |
| | Time will default to 00:00:00 if its not provided. |
| | Remember: Dates passed in have to be in the same format as the database. |
| | For MySQL this is YYYY-MM-DD HH:II:SS. Lowest date have to be passed in first. |
| | Correct: Array("2018-06-18 00:00:00", "2018-06-19 23:59:59"); |
| | Invalid: Array("2018-06-19 00:00:00", "2018-06-18 23:59:59"); |
| string $returnType | Optional: Choose data type to get returned result as. |
| | Options: obj/object, class, array/arr/assoc. Defaults to object (PDO::FETCH_OBJ). |
| | Remember to set $returnClass if class is chosen or return type will be set to object. | 
| string $returnClass | Optional: Class to return data as when class is chosen as $returnType. |
| &nbsp; | &nbsp; |
| return mixed       | Returns as object, class or array based on $returnType choice. |
##### Example:
```php
$db->select("users", array("id" => 55, "firstName" => "Raymond"), "*", "OR", 
            "ORDER BY ID ASC", "LIMIT 5", array("int", "str", "str", "str"), "DATE", 
            "Array("2018-06-21 00:00:00", "2018-06-22 23:59:59")", "Class", "TestClass");
```
___
### Search
##### Search for match in column(s) in a database table [v1.3]
```php
$db->search(string $table, array $where=[], string $columns="*", string $whereMode="OR", 
            string $order="",string $limit="", array $dataTypes=[], string $dateColumn="",
            array $dates=[], string $returnType="object", string $returnClass="");
```
| Parameter | Description |
|--|--|
| string $table | Database Table. |
| array  $where | Optional: Array holding the filters/'WHERE' clause for the query. |
| string $columns | Optional: the column to select (SELECT * FROM ...), defaults to *. |
| string $whereMode | Optional: Add an 'AND' or 'OR' after each item in the $where array, defaults to OR. |
| string $order | Optional: string holding the 'ORDER BY' clause.  
| string $limit | Optional: string holding the 'LIMIT' clause. |
| array  $dataTypes | Optional: Pass in data types as an array in equal order to the $where. | 
| | Options: int/integer, bool/boolean, str/string. |
| | Data type will default to string if nothing is passed in (PDO::PARAM_STR).|
| string $dateColumn | Optional: Date column in the table. Will only be used if $dates are provided. |
| array $dates | Optional: Pass in two dates to limit the result to rows between two dates. |
| | Time will default to 00:00:00 if its not provided. |
| | Remember: Dates passed in have to be in the same format as the database. |
| | For MySQL this is YYYY-MM-DD HH:II:SS. Lowest date have to be passed in first. |
| | Correct: Array("2018-06-18 00:00:00", "2018-06-19 23:59:59"); |
| | Invalid: Array("2018-06-19 00:00:00", "2018-06-18 23:59:59"); |
| string $returnType | Optional: Choose data type to get returned result as. |
| | Options: obj/object, class, array/arr/assoc. Defaults to object (PDO::FETCH_OBJ). |
| | Remember to set $returnClass if class is chosen or return type will be set to object. | 
| string $returnClass | Optional: Class to return data as when class is chosen as $returnType. |
| &nbsp; | &nbsp; |
| return mixed       | Returns as object, class or array based on $returnType choice. |
##### Example:
```php
$db->search("users", array("lastName" => "%Johannessen%", "firstName" => "%Raymond%"), "*", "OR", 
            "ORDER BY ID ASC", "LIMIT 5", array("str", "str", "str", "str"), "DATE", 
            "Array("2018-06-21 00:00:00", "2018-06-22 23:59:59")", "Class", "TestClass");
```
___
### Insert
##### Insert data into a database table [v1.0]
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
##### Update one or more rows of a database table [v1.0]
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
##### Remove one or more rows from a database table [v1.0]
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
##### Get row count from a database table [v1.0]
```php
$db->count(string $table, Array $where=[], $whereMode="AND", Array $dataTypes=[]);
```
| Parameter | Description |
|--|--|
| string $table | Database Table. |
| array  $where | Optional: Array holding the filters/'WHERE' clause for the query. |
| string $whereMode | Optional: Add an 'AND' or 'OR' after each item in the $where array, defaults to AND. |
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
           Array("int", "str"));
```
___
### Id
##### Last inserted row's id [v1.0]
```php
$db->id();
```
___
### Sql
##### Get last used query [v1.0]
```php
$db->sql();
```
___
### CloseConnection
##### Close the database connection [v1.0]
```php
$db->closeConnection();
```
___
### Licensed under the MIT License.
