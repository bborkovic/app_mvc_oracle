<?php 


namespace App\Controllers;

use Core\View;
	use Core\Form;
	use Core\Util;
	use Core\Upload;
	use Core\Session;
   use Core\ModelOracle;
   use App\Helper\MeasResults;
	use App\Config;
	use App\Models\User;
	use Core\Paginator;

class Mhss extends \Core\Controller {
	
	public function searchAction() {
      $this->messages["page_title"] = "mHSS/index";
      View::renderTemplate('Mhss/search.html', array(
         "messages" => $this->messages,
         )
      );
	}

   public function displayAction() {
      $db = ModelOracle::getDB(); // Get Access to DatabaseOracle Class
      $this->messages["page_title"] = "mHSS/display";
      
      $get_data = $_GET;
      $column_with_id = "IDS"; $column_with_timestamp = "STARTTIME"; // For this measurements , define columns with ids, and time

      // Select table based on meas_class/time_level combination
      $table_name = 'mhss.V_' . $_GET['meas_class'] . '_' . $_GET['time_level'];

      // select fields from $_GET
      $date_from = $_GET['date_from']; $date_to = $_GET['date_to']; $time_level = $_GET['time_level'];
      $meas_level = $_GET['meas_level']; $meas_class = $_GET['meas_class'];
      
      // Prepare list of ids for sql creation
      foreach ($_GET['meas_ids'] as $k => $v) { $meas_ids_arr[$k] = "'" . $v . "'"; }
      $meas_ids_string = join(',', $meas_ids_arr );

      // Get Columns for SQL
      $columns = $db->get_columns_of_table($table_name);
      $columns = array_diff($columns, ["STOPTIME","ENTRYDATE"]); // remove unnecessary columns
      
      // Create SQL
      $sql = "select " . join(', ', $columns) . " from " . $table_name;
         $sql .= " where starttime >= to_date('" . $date_from . "' ,'dd.mm.yyyy')";
         $sql .= " and starttime < to_date('" . $date_to . "' ,'dd.mm.yyyy') + 1";
         $sql .= " and vrsta = '" . $meas_level . "'";
         $sql .= " and ids in ( " . $meas_ids_string . " )";
         $sql = "select * from ( " . $sql . " ) where rownum <= 10000";
         $sql .= " order by starttime, ids";

      // Get DB data
      $res = $db->run_select_sql_with_columns($sql);
      $data = $res[1];
      
      // Generate instance of MeasResults, that handle json format export and links
      $mr = new MeasResults($data, $columns, $column_with_id , $column_with_timestamp, $time_level);
      $mr->partition_data_by_ids();
      $json_per_ci = $mr->get_json_data(); // Data used for graphs

      $json_parameters = [ // pass this parameters to javascript ...
         "column_with_id" => $column_with_id,
         "column_with_timestamp" => $column_with_timestamp,
         "time_level" => $time_level,
         "meas_level" => $meas_level,
         "meas_class" => $meas_class,
      ];

      // List of columns in specific format for FusionGraphs
      $json_columns = array();
      foreach ($columns as $col) { $json_columns[] = [ "mData" => $col ]; }

      // Get data for datatables, with links, formatted numbers ...
      $datatables_data = $mr->add_links_to_data($_GET);

      // Pass data to view
      View::renderTemplate('Mhss/display.html', array(
         "messages" => $this->messages,
         "json_per_ci" => json_encode($json_per_ci),
         "json_parameters" => json_encode($json_parameters),
         "json_columns" => json_encode($json_columns),
         "json_data" => json_encode($datatables_data),
         "columns" => $columns,
         )
      );

   }

   public function before() {
      if( !Session::is_logged_in()){
         Session::message( [ "You have to login first to access mHSS measurements!", "warning" ] );
         redirect_to('/users/login');
      }
      $this->messages = [];
      $this->messages["username"] = User::get_logged_username();
      $this->messages["message"] = get_message();
   }


}

?>