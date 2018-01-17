<?php 

    class Database {

        private $connect;

        function __construct() {

            if (!defined('DB_HOST')) { 
                define('DB_HOST','localhost');
            }
            if (!defined('DB_USER')) {
                define('DB_USER','username');
            }
            if (!defined('DB_PASS')) {
                define('DB_PASS','password');
            }
            if (!defined('DB_NAME')) { 
                define('DB_NAME','db_name'); 
            }

            $this->connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
        }

        /////////////////////////////////////////////////////////
        // Method to generate placeholders based on identifiers
        ////////////////////////////////////////////////////////
        private function placeholders($identifiers) {
            $replace = array("i", "d", "s", "m"); // characters to replace
            $replace_with = array("?,", "?,", "?,", "?,"); // characters to replace with
            $placeholders = str_replace($replace, $replace_with, $identifiers); // replace 'i', 'd', 's', 'm' with '?,'
            $placeholders = rtrim($placeholders,", "); // remove last ',';
            return $placeholders;
        }


        /////////////////////////////////////////////////////////
        // Method to add placeholders to the inputed db columns
        ////////////////////////////////////////////////////////
        private function placeholders_columns($db_columns) {
            $replace = array(","); // characters to replace
            $replace_with = array("=?,"); // characters to replace with
            $db_columns = str_replace($replace, $replace_with, $db_columns); // replace ',' with '=?,'
            $db_columns = rtrim($db_columns,", ")."=?"; // remove last ',' and add "=? at the end";
            return $db_columns;
        }


        /////////////////////////////////////////////////////////
        // Method to close connection
        /////////////////////////////////////////////////////////
        private function close_connection($stmt) {
            $stmt->close(); // Close statement
            $this->connect->close(); // Close connection
        }


        ////////////////////////////////////////////////////////
        // Method to insert data to database
        ///////////////////////////////////////////////////////
        function db_insert($db_table, $db_columns, $identifiers, $variables) {
            
            // $db_table = database table | $db_columns = columns in database table separated by ',' | 
            // $identifiers = variable identifiers | $variables = variables passed in as an array
            // EX: db_insert('users', 'username, password, name, email', 'ssss', $variables)
            
            $placeholders = $this->placeholders($identifiers); // Generate placeholders based on the value of $identifiers
            
            $stmt = $this->connect->prepare("INSERT INTO $db_table ($db_columns) VALUES ($placeholders)"); // prepare statement
            $stmt->bind_param($identifiers, ...$variables); // bind parameters 
            
            // insert to database
            if($stmt->execute()) {
                $this->close_connection($stmt); // Close connection
                return true;
            }
            else {
                $this->close_connection($stmt); // Close connection
                return false;
            }

        }


        ///////////////////////////////////////////////////////
        // Method to update data in database
        ///////////////////////////////////////////////////////
        function db_update($db_table, $db_columns, $where, $identifiers, $variables) {

            // $db_table = database table | $db_columns = columns in database table separated by '=?,' | 
            // $where = table row identifier | $identifiers = variable identifiers
            // $variables = variables passed in as an array
            // $where VALUE HAVE TO BE ADDED LAST IN THE VARIABLES ARRAY!
            // EX: db_update('users', 'username, password', 'id', 'ss', $variables)
            
            $db_columns = $this->placeholders_columns($db_columns); // Generate placeholders based on the value of $identifiers
            $where = $where."=?"; // add placeholder to $where 

            $stmt = $this->connect->prepare("UPDATE $db_table SET $db_columns WHERE $where"); // prepare statement
            $stmt->bind_param($identifiers, ...$variables); // bind parameters 
            
            // update database
            if($stmt->execute()) {
                $this->close_connection($stmt); // Close connection
                return true;
            }
            else {
                $this->close_connection($stmt); // Close connection
                return false;
            }

        }


        ///////////////////////////////////////////////////////
        // Method to delete data from the database
        ///////////////////////////////////////////////////////
        function db_delete($db_table, $where, $identifier, $variable) {

            // $db_table = database table | $where = table row identifier 
            // $identifier = variable identifier | $variable = value of the $where 
            // EX: db_delete('users', 'id', 'i', '86');

            $where = $where."=?"; // add placeholder to $where 

            $stmt = $this->connect->prepare("DELETE FROM $db_table WHERE $where"); // prepare statement
            $stmt->bind_param($identifier, $variable); // bind parameters 
            
            // update database
            if($stmt->execute()) {
                $this->close_connection($stmt); // Close connection
                return true;
            }
            else {
                $this->close_connection($stmt); // Close connection
                return false;
            }

        }

    }


    /* $data = 86;
    $db_conn = new Database();
    if($db_conn->db_delete("users", "id", "i", $data)) {
        echo "ok!";
    }
    else {
        echo "error!";
    }*/
?>



