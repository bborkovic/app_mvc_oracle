<?php
   // function double_string($el) { 
   //    return $el . $el; 
   // }
   
   $double_string = function($item) {
       return $item . $item;
   };

   echo call_user_func($double_string, "jedan");

   // print_r($arr);
   // $arr2 = array_map( function($el) { return $el . $el; } , $arr);
   // echo "<br/>";
   // print_r( $arr2);

 ?>



