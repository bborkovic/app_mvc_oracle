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

}



?>