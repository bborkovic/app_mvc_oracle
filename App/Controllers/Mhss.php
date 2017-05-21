<?php 


namespace App\Controllers;

use Core\View;
	use Core\Form;
	use Core\Util;
	use Core\Upload;
	use Core\Session;
   use Core\ModelOracle;
   use Core\MeasResults;
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
      $db = ModelOracle::getDB();
      $this->messages["page_title"] = "mHSS/display";
      
      if( ! isset($_GET["search"])) {
         Session::message( array("Please make a selection!","info") );
         redirect_to('search');
      }

      $date_from = $_GET['date_from'];
      $date_to = $_GET['date_to'];
      $min_date_string = $max_date_string = $date_from;

      $time_level = $_GET['time_level'];
      $meas_level = $_GET['meas_level'];
      $meas_class = $_GET['meas_class'];
      $table_name = 'V_' . $_GET['meas_class'] . '_' . $time_level;
      if( !isset($_GET['meas_ids'])) {
         print "Meas Ids not selected, select all";
      } else {
         $meas_ids_arr = $_GET['meas_ids'];
         foreach ($meas_ids_arr as $k => $v) { $meas_ids_arr[$k] = "'" . $v . "'"; }
         $meas_ids_string = join(',', $meas_ids_arr );
      }

      $table_name = "mhss.V_PM_408_AVGOVLDPERF_DAY";

      // Get Columns
      $columns = $db->get_columns_of_table($table_name);
      $columns = array_diff($columns, ["STOPTIME","ENTRYDATE"]); // remove columns not necessary
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
      $column_with_id = "IDS";
      $column_with_timestamp = "STARTTIME";

      $mr = new MeasResults($data, $columns, $column_with_id , $column_with_timestamp);
      $mr->partition_data_by_ids();
      $json_per_ci = $mr->get_json_data();

      $json_parameters = [
         "column_with_id" => $column_with_id,
         "column_with_timestamp" => $column_with_timestamp,
         "time_level" => $time_level,
         "meas_level" => $meas_level,
         "meas_class" => $meas_class,
      ];
      $json_columns = array();
      foreach ($columns as $col) {
         $json_columns[] = [ "mData" => $col ];
      }

      // add links to data
      foreach ($data as $k=>$row) {
         $row_data = $data[$k];
         $get_data = $_GET;
         $url_for_timestamp = MeasResults::generate_url_for_timestamp($row_data, $get_data, $column_with_id, $column_with_timestamp);
         $url_for_id = MeasResults::generate_url_for_id($row_data, $get_data, $column_with_id, $column_with_timestamp);
         if($time_level == 'DAY') {
            $date_truncated = MeasResults::trunc_hours_from_date( $row[$column_with_timestamp] ) . "<br/>";
            $data[$k][$column_with_timestamp] = '<a href="display?' . $url_for_timestamp . '">' . $date_truncated . '</a>';
            $data[$k][$column_with_id] = '<a href="display?' . $url_for_id . '">' . $data[$k][$column_with_id] . '</a>';
         }
         foreach ($columns as $column) {
            $val = $row[$column];
            if(is_numeric($val)){
               $data[$k][$column] = round($val,2);
            }
         }
      }

      // Pass data to view
      View::renderTemplate('Mhss/display.html', array(
         "messages" => $this->messages,
         "json_per_ci" => json_encode($json_per_ci),
         "json_parameters" => json_encode($json_parameters),
         )
      );

   }



   public function before() {
      $this->messages = [];
      $this->messages["username"] = User::get_logged_username();
      $this->messages["message"] = get_message();
   }


}

?>