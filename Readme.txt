DB INSERT

$db_table = database table | $db_columns = columns in database table separated by ',' | 
$identifiers = variable identifiers | $variables = variables passed in as an array
EX: db_insert('users', 'username, password, name, email', 'ssss', $variables)


DB UPDATE

$db_table = database table | $db_columns = columns in database table separated by '=?,' | 
$where = table row identifier | $identifiers = variable identifiers
$variables = variables passed in as an array
$where VALUE HAVE TO BE ADDED LAST IN THE VARIABLES ARRAY!
EX: db_update('users', 'username, password', 'id', 'ss', $variables)


DB DELETE

$db_table = database table | $where = table row identifier 
$identifier = variable identifier | $variable = value of the $where 
EX: db_delete('users', 'id', 'i', 86);
