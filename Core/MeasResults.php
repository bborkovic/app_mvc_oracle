<?php  

namespace Core;

class MeasResults{

   public $data;
   public $data_partitioned;
   public $columns;
   public $id_column;
   public $meas_ids;
   public $time_column;

   public function __construct( $data, $columns, $id_column, $time_column ) {
      // $data should be sorted by time_column, id_column
      $this->data = $data;
      $this->columns = $columns;
      $this->id_column = $id_column;
      $this->time_column = $time_column;
   }

   public function partition_data_by_ids() {
      $partitioned_data = [];
      $id_column = $this->id_column;
      foreach ($this->data as $row) {
         $id = $row[$id_column];
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

   public static function generate_url_for_timestamp($row_data, $get_data, $column_with_id, $column_with_timestamp) {
      $starttime = \DateTime::createFromFormat( 'd.m.Y H:i', $row_data[$column_with_timestamp]);
      $endtime = clone $starttime;
      $endtime->modify('+1 day');
      $starttime_string = $starttime->format('d.m.Y');
      $endtime_string = $endtime->format('d.m.Y');

      $url_array_ids = [];
      $url_array_ids[ "meas_ids"] = array($row_data[$column_with_id] );
      $url_array_ids["date_from"] = $starttime_string;
      $url_array_ids["date_to"] = $endtime_string;
      $url_array_ids["time_level"] = "HOUR";
      $url_array_ids["meas_level"] = $get_data["meas_level"];
      $url_array_ids["meas_class"] = $get_data["meas_class"];
      $url_string_ids = http_build_query($url_array_ids);
      return $url_string_ids;
   }

   public static function generate_url_for_id($row_data, $get_data, $column_with_id, $column_with_timestamp) {
      $starttime = \DateTime::createFromFormat( 'd.m.Y H:i', $row_data[$column_with_timestamp]);
      $endtime = clone $starttime;
      $starttime->modify('-30 day');
      $endtime->modify('+10 day');
      $starttime_string = $starttime->format('d.m.Y');
      $endtime_string = $endtime->format('d.m.Y');

      $url_array_ids = [];
      $url_array_ids[ "meas_ids"] = array($row_data[$column_with_id] );
      $url_array_ids["date_from"] = $starttime_string;
      $url_array_ids["date_to"] = $endtime_string;
      $url_array_ids["time_level"] = $get_data["time_level"];
      $url_array_ids["meas_level"] = $get_data["meas_level"];
      $url_array_ids["meas_class"] = $get_data["meas_class"];
      $url_string_ids = http_build_query($url_array_ids);
      return $url_string_ids;
   }

   public static function trunc_hours_from_date($datetime) {
      return preg_replace('/(\d{1,2})\.(\d{1,2})\.(\d{1,4}) .*/', '$1.$2.$3',$datetime );
   }

}



?>