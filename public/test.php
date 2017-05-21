<?php

   $dt = '25.10.2016 10:30';
   echo $dt;
   $starttime = DateTime::createFromFormat( 'd.m.Y H:i', $dt);
   var_dump($starttime);

 ?>



