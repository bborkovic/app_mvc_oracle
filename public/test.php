<?php
   
   function sort_by_column(&$a, $col) {
      usort( $a, function($one, $two) use ($col){
         if ( $one[$col] > $two[$col] ) {
            return 1;
         } elseif( $one[$col] == $two[$col] ) { 
            return 0;
         } else {
            return -1;
         }
      } );
   }

   // function sort_by_term_meta(&$terms, $meta) {
   //     usort($terms, function($a, $b) use ($meta) {
   //         $name_a = get_term_meta($a->term_id, 'artist_lastname', true);
   //         $name_b = get_term_meta($b->term_id, 'artist_lastname', true);
   //         return strcmp($name_a, $name_b);  
   //     });
   // }



   $myarr = [ 
         ["col1" => 10, "col2" => "bla", "col3" => 50],
         ["col1" => 5, "col2" => "eee", "col3" => 100],
         ["col1" => 15, "col2" => "aaa", "col3" => 20],
         ["col1" => 25, "col2" => "zzz", "col3" => 200],
         ["col1" => 2, "col2" => "a", "col3" => 10],
      ];

   foreach ($myarr as $row) {
      print_r($row);
      echo "<br/>";
   }

   echo "<br/>";
   sort_by_column($myarr, "col3");


   foreach ($myarr as $row) {
      print_r($row);
      echo "<br/>";
   }



 ?>



