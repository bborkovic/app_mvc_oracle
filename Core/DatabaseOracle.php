<?php
namespace Core;
use App\Config;
use \Exception;


// Oracle Database Class
class DatabaseOracle {
	
	private $connection;
	public $last_query;
	private $magic_quotes_active;
	private $real_escape_string_exists;
	private $last_modified_id;

	
	
	function __construct() {
		$this->open_connection();
		// $this->magic_quotes_active = get_magic_quotes_gpc();
		// $this->real_escape_string_exists = function_exists( "mysqli_real_escape_string" );
	}

	public function open_connection() {
		try {
			$conn = oci_connect(Config::DB_USER, Config::DB_PASS, Config::DB_SERVER . '/' . Config::DB_NAME, 'AL32UTF8');
         $stid = oci_parse($conn, "alter session set nls_date_format = 'dd.mm.yyyy hh24:mi'");
         oci_execute($stid);
         $stid = oci_parse($conn, "ALTER SESSION SET NLS_NUMERIC_CHARACTERS = '. '");
         oci_execute($stid);
		} catch (\Exception $e) {
			throw new \Exception("Error: Database Connection Failed! " 
				. "<br/>" . $e->getMessage() 
				. "<br/>" . $e->getFile()
			);
		}
		$this->connection = $conn;
	}

	public function query($sql) {
		try {
			$sth = $this->connection->query($sql);
		} catch (\Exception $e) {
			die("Database query failed ". $e->getMessage() );
		}
		return $sth;
	}

	public function fetch_array($result) {
		//
		return $result->fetchAll();
	}

   public function run_select_sql_with_columns($sql){
    // Prepare the statement
       $stid = oci_parse($this->connection, $sql);
       if (!$stid) {
           $e = oci_error($this->connection);
           trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
       }
       // get columns first
      oci_execute($stid, OCI_DESCRIBE_ONLY);
      $columns = array();
      $ncols = oci_num_fields($stid);
      for ($i = 1; $i <= $ncols; $i++){
         array_push($columns, oci_field_name($stid, $i));
      }
       // Perform the logic of the query
       $r = oci_execute($stid);
       if (!$r) {
           $e = oci_error($stid);
           trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
       }
       oci_fetch_all($stid,$out,null,null,OCI_FETCHSTATEMENT_BY_ROW);
       //oci_fetch_all($stid, $out);
       oci_free_statement($stid);
       
       return( array($columns, $out));
   } // End run_select_sql_with_columnss


   public function get_columns_of_table($table_name){
    // Prepare the statement
       $sql = "select * from $table_name where 1=2";
       $stid = oci_parse($this->connection, $sql);
       if (!$stid) {
           $e = oci_error($this->connection);
           trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
       }
       // get columns first
      oci_execute($stid, OCI_DESCRIBE_ONLY);
      $columns = array();
      $ncols = oci_num_fields($stid);
      for ($i = 1; $i <= $ncols; $i++){
         array_push($columns, oci_field_name($stid, $i));
      }
      oci_free_statement($stid);
      return $columns;
   } // end of run_sql


	public function query_select($sql) {
		// fetch data as array(number based) of records ( associative array indexed by column name)
		try {
			$sth = oci_parse( $this->connection , $sql );
			oci_execute($sth);
		} catch (Exception $e) {
			//$bind_array_text = var_export($bind_array,true);
			throw new Exception("Error: The Database Query Failed! , " 
				. "<br/>sql: " . $sql 
				//. "<br/>binds: " . $bind_array_text
				. "<br/>" . $e->getMessage()
			);
		}
		$num_rows = oci_fetch_all($sth, $ret, 0, 0, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
		return $ret; // return array to use in Model class
	}

	public function query_select_prepared($sql , $bind_array=[]) {
		try {
			$sth = oci_parse( $this->connection , $sql );

			foreach ($bind_array as $key => $val) {
				oci_bind_by_name($sth, $key, $bind_array[$key]);
				// oci_bind_by_name($sth, $key, $val);
			}
			oci_execute($sth);

		} catch (Exception $e) {
			$bind_array_text = var_export($bind_array,true);
			throw new Exception("Error: The Database Query Failed! , " 
				. "<br/>sql: " . $sql 
				. "<br/>binds: " . $bind_array_text
				. "<br/>" . $e->getMessage()
			);
		}
		$num_rows = oci_fetch_all($sth, $ret, 0, 0, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
		return $ret; // return array to use in Model class
	}

	public function query_dml_prepared($sql , $bind_array=[]) {
		try {
			$sql = trim($sql);
			$operation = ( strtolower(substr($sql,0,6)) == 'insert' ) ? 'i' : 'u';
			$sql_tail = ( $operation == 'i' ) ? " returning id into :id" : "";
			$sql .= $sql_tail;

			$sth = oci_parse( $this->connection , $sql );
			foreach ($bind_array as $key => $val) {
				oci_bind_by_name($sth, $key, $bind_array[$key]);
			}
			if( $operation == 'i') { oci_bind_by_name($sth, ':id', $id, -1, SQLT_INT); }
			$num_rows = oci_execute($sth);
		} catch (Exception $e) {
			$bind_array_text = var_export($bind_array,true);
			throw new Exception("Error: The Database Query Failed! , " 
				. "<br/>sql: " . $sql 
				. "<br/>binds: " . $bind_array_text
				. "<br/>" . $e->getMessage()
			);
		}
		if( $operation == 'i') {
			$this->last_modified_id = $id;
		}
		if( $operation == 'u') {
			$this->last_modified_id = $bind_array[":id"];
		}
		return true;
	}

	public function count_by_sql_prepared($sql, $bind_array=[]) {
		// sql must be select count(*) from where id = :id and name > :name
		$result_set = $this->query_select_prepared($sql, $bind_array);
		

		// result_set is array of records as hashes, get the first hash ( should be only one)
		$first_row = array_pop($result_set);
		
		// get only values from the hash
		$first_row_values = array_values($first_row);

		// return first one ( only one )
		return array_pop($first_row_values);
	}
	
	public function last_insert_id() {
		//
		// return $this->connection->lastInsertId();
		return $this->last_modified_id;
	}

	public function last_modified_id() {
		//
		// return $this->connection->lastInsertId();
		return $this->last_modified_id;
	}
}

// $database = new Database();
// $db =& $database;

?>
