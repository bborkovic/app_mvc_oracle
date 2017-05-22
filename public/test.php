<?php

   // class Test {
   //    public $arr;
   //    public function __initialize( $in_array){
   //       $this->arr = $in_array;
   //    }
   // }
   function change_array($in_arr) { 
      $in_arr[0] = "Jedan";
      echo join(', ', $in_arr) . "<br/>";
   }


   $arr = [ "jedan", "dva", "tri"];
   echo join(', ', $arr) . "<br/>";

   change_array($arr);

   echo join(', ', $arr) . "<br/>";



 ?>



