<?php  

namespace App\Helper;

class MeasResultsFemto{

   public $data;
   public $data_partitioned;
   public $id_aliases_hash;
   public $columns;
   public $id_column;
   public $meas_ids;
   public $time_column;
   public $alias_column;

   public function __construct( $data, $columns, $id_column, $time_column, $alias_column, $time_level ) {
      // $data should be sorted by time_column, id_column
      $this->data = $data;
      $this->columns = $columns;
      $this->id_column = $id_column;
      $this->time_column = $time_column;
      $this->alias_column = $alias_column;
      $this->time_level = $time_level;
   }

   public function partition_data_by_ids(){

      $id_aliases_hash = [];
      $partitioned_data = [];
      $id_column = $this->id_column;
      $alias_column = $this->alias_column;
      foreach ($this->data as $row) {
         $id = $row[$id_column];
         $alias = $row[$alias_column];
         $id_aliases_hash[$id] = $alias;
         if ( !array_key_exists($id,$partitioned_data) ) {
            $partitioned_data[$id] = [];
         }
         array_push( $partitioned_data[$id], $row );
      }
      $this->partitioned_data = $partitioned_data;
      $this->meas_ids = array_keys($partitioned_data);
      $this->id_aliases_hash = $id_aliases_hash;
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
         $tmp_json = array( "graph_id" => $ci, "graph_alias" => $this->id_aliases_hash[$ci], "graph_data" => $json_per_ci );
         array_push($json_return, $tmp_json);
      }
      return $json_return;
   }

   public function add_links_to_data($get_data) {
      $column_with_id = $this->id_column;
      $column_with_timestamp = $this->time_column;
      $time_level = $this->time_level;
      
      foreach ($this->data as $k=>$row) {
         $row_data = $this->data[$k];
         $url_for_timestamp = $this->generate_url_for_timestamp($row_data, $get_data, $column_with_id, $column_with_timestamp);
         // $url_for_id = $this->generate_url_for_id($row_data, $get_data, $column_with_id, $column_with_timestamp);
         if($time_level == 'DAY') {
            $date_truncated = $this->trunc_hours_from_date( $row[$column_with_timestamp] ) . "<br/>";
            $this->data[$k][$column_with_timestamp] = '<a href="display?' . $url_for_timestamp . '">' . $date_truncated . '</a>';
            // $this->data[$k][$column_with_id] = '<a href="display?' . $url_for_id . '">' . $this->data[$k][$column_with_id] . '</a>';
         }
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
      $starttime = \DateTime::createFromFormat( 'd.m.Y H:i', $row_data[$column_with_timestamp]);
      $endtime = clone $starttime;
      $endtime->modify('+1 day');
      $starttime_string = $starttime->format('d.m.Y');
      $endtime_string = $starttime->format('d.m.Y');

      $url_array_ids = [];
      $url_array_ids[ "ci_list"] = array($row_data[$column_with_id] );
      $url_array_ids["date_from"] = $starttime_string;
      $url_array_ids["date_to"] = $endtime_string;
      $url_array_ids["time_level"] = "RAW";
      $url_string_ids = http_build_query($url_array_ids);
      return $url_string_ids;
   }


   public static function get_femto_cells($db) {
      $sql = "select ci, naziv from test.celije_umts_pegabase where naziv like 'ft\_%' escape '\' order by naziv";
      $results = $db->query_select($sql);
      $cells_hash = [];
      foreach ($results as $cell) {
         $cells_hash[] = [ "ci" => $cell["CI"], "naziv" => $cell["NAZIV"] ];
      }
      return json_encode($cells_hash);
   }

   public function trunc_hours_from_date($datetime) {
      return preg_replace('/(\d{1,2})\.(\d{1,2})\.(\d{1,4}) .*/', '$1.$2.$3',$datetime );
   }

}

?>