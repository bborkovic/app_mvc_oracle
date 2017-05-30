<?php  

namespace App\Helper;

class MeasResults{

   public $data;
   public $data_partitioned;
   public $columns;
   public $column_with_id;
   public $meas_ids;
   public $column_with_timestamp;
   public $time_level;
   public $get_data;


   public function __construct( $data, $columns, $column_with_id, $column_with_timestamp, $time_level ) {
      // $data should be sorted by column_with_timestamp, column_with_id
      $this->data = $data;
      $this->columns = $columns;
      $this->column_with_id = $column_with_id;
      $this->column_with_timestamp = $column_with_timestamp;
      $this->time_level = $time_level;
   }

   public function partition_data_by_ids() {
      $partitioned_data = [];
      $column_with_id = $this->column_with_id;
      foreach ($this->data as $row) {
         $id = $row[$column_with_id];
         if ( !array_key_exists($id,$partitioned_data) ) {
            $partitioned_data[$id] = [];
         }
         array_push( $partitioned_data[$id], $row );
      }
      $this->partitioned_data = $partitioned_data;
      $this->meas_ids = array_keys($partitioned_data);
   }

   public function get_json_data() {
      $json_return = array();

      foreach ($this->partitioned_data as $ci => $values_for_ci) {
         $json_per_ci = array();
         foreach($this->columns as $column_name) {
            $tmp_arr = [];
            foreach ($values_for_ci as $k=>$row){
               $val = $row[$column_name];
               if(is_numeric($val)){
                  $val = round($val,2);
               }
               // array_push($tmp_arr, $row[$column_name]);
               array_push($tmp_arr, $val);
            }
            $tmp_json = array( "column_name" => $column_name, "values" => join('|', $tmp_arr));
            array_push($json_per_ci, $tmp_json);
         }
         $tmp_json = array( "graph_id" => $ci, "graph_data" => $json_per_ci );
         array_push($json_return, $tmp_json);
      }
      return $json_return;
   }

   public function add_links_to_data($get_data) {
      $column_with_id = $this->column_with_id;
      $column_with_timestamp = $this->column_with_timestamp;
      $time_level = $this->time_level;
      
      foreach ($this->data as $k=>$row) {
         $row_data = $this->data[$k];
         $url_for_timestamp = $this->generate_url_for_timestamp($row_data, $get_data, $column_with_id, $column_with_timestamp);
         $url_for_id = $this->generate_url_for_drill_id($row_data, $get_data, $column_with_id, $column_with_timestamp);
         
         if($time_level == 'DAY') {
            $date_truncated = $this->trunc_hours_from_date( $row[$column_with_timestamp] ) . "<br/>";
            $this->data[$k][$column_with_timestamp] = '<a href="display?' . $url_for_timestamp . '">' . $date_truncated . '</a>';
         }
         $this->data[$k][$column_with_id] = '<a href="display?' . $url_for_id . '">' . $this->data[$k][$column_with_id] . '</a>';

         foreach ($this->columns as $column) {
            $val = $row[$column];
            if(is_numeric($val)){
               $this->data[$k][$column] = round($val,2);
            }
         }
      }
      return $this->data;
   }

   public function generate_url_for_timestamp($row_data, $get_data, $column_with_id, $column_with_timestamp) {
      // drill down from day -> Hour
      $starttime = \DateTime::createFromFormat( 'd.m.Y H:i', $row_data[$column_with_timestamp]);
      $endtime = clone $starttime;
      $endtime->modify('+1 day');
      $starttime_string = $starttime->format('d.m.Y');
      $endtime_string = $endtime->format('d.m.Y');
      
      // new URL is old URL , some keys will be changed!
      $url_array_ids = $get_data;
      $url_array_ids["drill_down"] = "false";
      $url_array_ids[ "meas_ids"] = array($row_data[$column_with_id] );
      $url_array_ids["date_from"] = $starttime_string;
      $url_array_ids["date_to"] = $endtime_string;
      $url_array_ids["time_level"] = "HR";
      $url_string_ids = http_build_query($url_array_ids);
      return $url_string_ids;
   }

   public function generate_url_for_id($row_data, $get_data, $column_with_id, $column_with_timestamp) {
      $starttime = \DateTime::createFromFormat( 'd.m.Y H:i', $row_data[$column_with_timestamp]);
      $endtime = clone $starttime;
      $starttime->modify('-30 day');
      $endtime->modify('+10 day');
      $starttime_string = $starttime->format('d.m.Y');
      $endtime_string = $endtime->format('d.m.Y');

      // new URL is old URL , some keys will be changed!
      $url_array_ids = $get_data;

      // $url_array_ids = [];
      $url_array_ids[ "meas_ids"] = array($row_data[$column_with_id] );
      $url_array_ids["date_from"] = $starttime_string;
      $url_array_ids["date_to"] = $endtime_string;
      $url_string_ids = http_build_query($url_array_ids);
      return $url_string_ids;
   }

   public function generate_url_for_drill_id($row_data, $get_data, $column_with_id, $column_with_timestamp) {
      $starttime = \DateTime::createFromFormat( 'd.m.Y H:i', $row_data[$column_with_timestamp]);
      $endtime = clone $starttime;
      $starttime->modify('-30 day');
      $endtime->modify('+10 day');
      $starttime_string = $starttime->format('d.m.Y');
      $endtime_string = $endtime->format('d.m.Y');

      // new URL is old URL , some keys will be changed!
      $url_array_ids = $get_data;

      // $url_array_ids = [];
      $url_array_ids[ "meas_ids"] = array($row_data[$column_with_id] );
      $url_array_ids["drill_down"] = "true";

      $child_meas_level = $get_data['meas_level'];
      if ($get_data['meas_level'] == 'NODEID') {
         $child_meas_level = 'CARDID';
      } 
      if ($get_data['meas_level'] == 'CARDID') {
         $child_meas_level = 'PEER';
      }
      $url_array_ids["meas_level"] = $child_meas_level;
      // $url_array_ids["date_from"] = $starttime_string;
      // $url_array_ids["date_to"] = $endtime_string;
      // $url_array_ids["time_level"] = $get_data["time_level"];
      // $url_array_ids["meas_level"] = $get_data["meas_level"];
      // $url_array_ids["meas_class"] = $get_data["meas_class"];
      // $url_array_ids["kpi_counter"] = $get_data["kpi_counter"];
      $url_string_ids = http_build_query($url_array_ids);
      return $url_string_ids;
      // NODEID,CARDID,PEER
   }





   public function trunc_hours_from_date($datetime) {
      return preg_replace('/(\d{1,2})\.(\d{1,2})\.(\d{1,4}) .*/', '$1.$2.$3',$datetime );
   }

   public static function get_mhss_meas_classes_json($db) {
      $sql = "select meas_class , kpi , counter from mhss.v_meas_classes";
      $results = $db->query_select($sql);
      $json_arr = [];
      foreach ($results as $row) {
         $meas_class = $row['MEAS_CLASS'];
         $kpi = $row['KPI'];
         $json_arr[] = [ "id" => $meas_class, "value" => $meas_class , "kpi" => $kpi];
      }
      return json_encode($json_arr);
   }

   public static function get_mhss_meas_levels_json($db) {
      $sql = "select meas_class, levs from mhss.v_meas_levels";
      $results = $db->query_select($sql);
      $json_arr = [];
      foreach ($results as $row) {
         $meas_class = $row['MEAS_CLASS'];
         $levels = $row['LEVS'];
         $levels_arr = explode(',', $levels);
         $json_arr[$meas_class] = $levels_arr;
      }
      return json_encode($json_arr);
   }   

   public static function get_mhss_meas_ids_json($db) {
      $sql = "select meas_class_vrsta, ids from mhss.v_meas_ids";
      $results = $db->query_select($sql);
      $json_arr = [];
      foreach ($results as $row) {
         $meas_class_vrsta = $row['MEAS_CLASS_VRSTA'];
         $ids = $row['IDS'];
         $ids_arr = explode(',', $ids);
         $json_arr[$meas_class_vrsta] = $ids_arr;
      }
      return json_encode($json_arr);
   }

}



?>