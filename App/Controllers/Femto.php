<?php 


namespace App\Controllers;

use Core\View;
	use Core\Form;
	use Core\Util;
	use Core\Upload;
	use Core\Session;
   use Core\ModelOracle;
   use App\Helper\MeasResultsFemto;
	use App\Config;
	use App\Models\User;
	use Core\Paginator;

class Femto extends \Core\Controller {
	
	public function searchAction() {
      $db = ModelOracle::getDB(); // Get Access to DatabaseOracle Class
      $this->messages["page_title"] = "Femto/search";
     
      $json_femto_cells  = MeasResultsFemto::get_femto_cells($db);

      View::renderTemplate('Femto/search.html', array(
            "messages" => $this->messages,
            "json_femto_cells" => $json_femto_cells,
         )
      );
	}

   public function displayAction() {
      $db = ModelOracle::getDB(); // Get Access to DatabaseOracle Class
      $this->messages["page_title"] = "Femto/display";
      
      $get_data = $_GET;
      $column_with_id = "CI";
      $column_with_id_alias = "NAZIV";
      $column_with_timestamp = "PERIOD_START_TIME";
      $time_level = $_GET['time_level'];

      // Select table based on meas_class/time_level combination
      $table_name = ( $time_level == 'RAW' ) ? 'test.v_ft_wcell_sve' : 'test.v_ft_wcell_sum_sve';

      // select fields from $_GET
      $date_from = $_GET['date_from']; $date_to = $_GET['date_to'];
      
      // Prepare list of ids for sql creation
      $meas_ids_string = join(',', $_GET['ci_list'] );

      // Create SQL
      $sql = 'select "CI","NAZIV","NAZIV_FT","TKC","PERIOD_START_TIME","AMR_Traffic_Carried(Erl)","Data_Traffic_R99_UL(MByte)","Data_Traffic_R99_DL(MByte)","Data_Traffic_HSDPA(MByte)","Data_Traffic_HSUPA(MByte)","RAB_Setup_Att_AMR(Nr)","RAB_Assign_AMR_Succ_Rate(%)","RAB_Setup_Att_PSR99(Nr)","RAB_Assign_PSR99_Succ_Rate(%)","RAB_Setup_Att_HSDPA(Nr)","RAB_Assign_HSDPA_Succ_Rate(%)","RAB_Setup_Att_HSUPA(Nr)","RAB_Assign_HSUPA_Succ_Rate(%)","RRC_Setup_Fail_Congestion(%)","Paging_CongRatio(%)","Att_IntraFreq_AMRtoAP(Nr)","SuccRate_IntraFreq_AMRtoAP(%)","Att_InterFreq_AMRtoAP(Nr)","SuccRate_InterFreq_AMRtoAP(%)","SuccRate_InterRAT_AMRto2G(%)"';
      $sql .= ' from ' . $table_name . " where 1=1";
      $sql .= " and period_start_time >= to_date('" . $date_from . "','dd.mm.yyyy')";
      $sql .= " and period_start_time < to_date('" . $date_to . "','dd.mm.yyyy') + 1";
      $sql .= " and ci in (" . $meas_ids_string . ")";
      $sql .= " order by period_start_time, ci";

      // Get DB data
      $res = $db->run_select_sql_with_columns($sql);
      $columns = $res[0];
      $data = $res[1];
      
      // Generate instance of MeasResults, that handle json format export and links
      $mr = new MeasResultsFemto($data, $columns, $column_with_id , $column_with_timestamp, $column_with_id_alias, $time_level);
      $mr->partition_data_by_ids();
      $json_per_ci = $mr->get_json_data(); // Data used for graphs

      $json_parameters = [ // pass this parameters to javascript ...
         "column_with_id" => $column_with_id,
         "column_with_timestamp" => $column_with_timestamp,
         "time_level" => $time_level,
      ];

      // List of columns in specific format for FusionGraphs
      $json_columns = array();
      foreach ($columns as $col) { $json_columns[] = [ "mData" => $col ]; }

      // Get data for datatables, with links, formatted numbers ...
      $datatables_data = $mr->add_links_to_data($_GET);
      //$datatables_data = $data;

      // Pass data to view
      View::renderTemplate('Femto/display.html', array(
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
         Session::message( [ "You have to login first to access Femto measurements!", "warning" ] );
         redirect_to('/users/login');
      }
      $this->messages = [];
      $this->messages["username"] = User::get_logged_username();
      $this->messages["message"] = get_message();
   }



}

?>