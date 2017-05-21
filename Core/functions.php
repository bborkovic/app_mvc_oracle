<?php 

function message( $text, $succ_flag="normal"){
	$color = "black";
	switch ($succ_flag) {
		case "success":
			$color="green";
			break;
		case "error":
			$color="red";
			break;
		case "normal":
			$color="black";
			break;
	}
	echo "<font color=\"$color\">" . $text . '</font>';
}

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}


function get_message() {
	$message = null;
	$message_array = Core\Session::message();

	if( !empty($message_array)){
		$message = $message_array[0];
		$message_type = $message_array[1];
		$message_types = array(
			'info'    => 'alert alert-info'
			, 'warning' => 'alert alert-warning'
			, 'danger'  => 'alert alert-danger'
			, 'error'   => 'alert alert-danger'
			, 'success' => 'alert alert-success'
		);

		if( isset($message_types[$message_type])) {
			$mess_class = $message_types[$message_type];
		} else {
			$mess_class = 'alert alert-info';
		}
		$ret = '';
		$ret .= '<div class="' . $mess_class . '">';
		$ret .= "<p>{$message}</p>";
		$ret .= '</div>';
		return $ret;
	}
}


function output_message() {
  $message = null;
  $message_array = Core\Session::message();

  if( !empty($message_array)){
    $message = $message_array[0];
    $message_type = $message_array[1];
    $message_types = array(
          'info'    => 'alert alert-info'
        , 'warning' => 'alert alert-warning'
        , 'danger'  => 'alert alert-danger'
        , 'error'   => 'alert alert-danger'
        , 'success' => 'alert alert-success'
    );
    
    if( isset($message_types[$message_type])) {
      $mess_class = $message_types[$message_type];
    } else {
      $mess_class = 'alert alert-info';
    }

    echo '<div class="' . $mess_class . '">';
    echo  "<p>{$message}</p>";
    echo '</div>';
  }
}


?>

