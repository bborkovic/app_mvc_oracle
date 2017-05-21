<?php 

namespace Core;

class Upload {

	protected $destination;
	protected $messages = [];
	protected $max_size;
	protected $permitted_types = array(
			'image/jpeg'
			, 'image/pjpeg'
			, 'image/gif'
			, 'image/png'
			, 'image/webp'
		);
	protected $newName;
	protected $typeCheckingOn = true;
	protected $notTrusted = ['bin', 'cgi', 'exe', 'js', 'pl', 'php', 'py', 'sh', 'bash'];
	protected $suffix = '.upload';
	protected $renameDuplicates;

	public function __construct($upload_folder) {

		if( !is_dir($upload_folder) || !is_writable($upload_folder)) {
			//
			throw new \Exception("$upload_folder must be valid, writable folder", 1);
		}

		if( substr($upload_folder,-1) != '/') {
			$upload_folder .= '/';
		}
		$this->destination = $upload_folder;
		// set default max size ( 500k )
		$this->max_size = 500*1024;
	}

	public function upload($renameDuplicates = true){
		$this->renameDuplicates = $renameDuplicates;
		$uploaded = current($_FILES);
		
		if( is_array($uploaded['name']) ){
			// multiple file upload
			foreach ($uploaded['name'] as $key => $value) {
				$currentFile['name'] = $uploaded['name'][$key];
				$currentFile['type'] = $uploaded['type'][$key];
				$currentFile['tmp_name'] = $uploaded['tmp_name'][$key];
				$currentFile['error'] = $uploaded['error'][$key];
				$currentFile['size'] = $uploaded['size'][$key];
				if( $this->check_file( $currentFile )){
					$this->move_file($currentFile);
				}
			}
		} else {
			// single file upload
			if( $this->check_file($uploaded) ){
				$this->move_file($uploaded);
			}
		}
	}

	public function set_max_size($bytes){
		
		$server_max_size = self::convert_to_bytes( ini_get('upload_max_filesize'));
		if( $bytes > $server_max_size){
			throw new \Exception("Max size cannot exceed server limit for individual files: " . self::convert_from_bytes($server_max_size), 1);
		}
		if(is_numeric($bytes) && $bytes > 0) {
			$this->max_size = $bytes;
		}
		return true;
	}

	public function get_messages(){
		//
		return $this->messages;
	}

	public function getName() {
		//
		return $this->newName;
	}

	protected function check_file($file){
		if( $file['error' ] != 0){
			$this->get_error_message($file);
			return false;
		}
		if( ! $this->check_size($file) ){
			return false;
		}
		if( $this->typeCheckingOn ) {
			if( ! $this->check_type($file) ){
				return false;
			}
		}
		$this->checkName($file);
		return true;
	}

	protected function get_error_message($file){
		switch ($file['error']) {
			case 1:
				$this->messages[] = $file['name'] . ' The uploaded file exceeds the upload_max_filesize.';
				break;
			case 2:
				$this->messages[] = $file['name'] . ' ; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
				break;
			case 3:
				$this->messages[] = $file['name'] . ' was partialy uploaded.';
			case 4:
				$this->messages[] = 'No file submitted.';
			default:
				$this->messages[] = 'Sorry, there was a problem uploading ' . $file['name'];
				break;
		}
	}

	protected function move_file($file){
		
		$filename = isset($this->newName) ? $this->newName : $file['name'];
		$success = move_uploaded_file($file['tmp_name'] , $this->destination . $filename);
		
		if ( $success ) {
			$result = $file['name'] . ' was uploaded successfully';
			if( !is_null($this->newName)) {
				$result .= ', and was renamed to ' . $this->newName;
			}
			$result .= '.';
			$this->messages[] = $result;
		} else {
			$this->messages[] = 'Could not upload ' . $file['name'];
		}
	}

	protected function check_size($file){
		if( $file['size'] == 0 ) {
			$this->messages[] = $file['name'] . ' is empty.';
			return false;
		}
		if( $file['size'] > $this->max_size ) {
			$this->messages[] = $file['name'] . ' exceeds maximum size for a file.';
			return false;
		}
		return true;
	}

	protected function check_type($file) {
		if( in_array($file['type'], $this->permitted_types) ) {
			return true;
		} else {
			$this->messages[] = $file['name'] . ' is not permitted type of file';
			return false;
		}
	}
	
	protected function checkName($file) {
		$this->newName = null;
		$nospaces = str_replace(' ', '_', $file['name']);
		// if($nospaces != $file['name']) {
		// 	$this->newName = $nospaces;
		// }
		$this->newName = $nospaces;
		
		// add suffix to suspisious file types
		$nameparts = pathinfo($nospaces);
		$extension = isset($nameparts['extension']) ? $nameparts['extension'] : '';
		if( !$this->typeCheckingOn and !empty($this->suffix)) {
			if( in_array($extension, $this->notTrusted) || empty($extension)) {
				$this->newName = $nospaces . $this->suffix;
			}
		}
		
		// add number to duplicates ( 'filename_1.jpg' ) 
		if( $this->renameDuplicates ) {
			$name = isset($this->newName) ? $this->newName : $file['name'];
			$existing = scandir($this->destination);
			if( in_array($name, $existing) ){
				$i = 1;
				do {
					$this->newName = $nameparts['filename'] . '_' . $i++;
					if ( !empty($extension)) {
						$this->newName .= ".$extension";
					}
					if ( in_array($extension, $this->notTrusted) || empty($extension)  ) {
						$this->newName .= $this->suffix;
					}
				} while ( in_array($this->newName, $existing) );
			}
		}
	}
	
	public static function convert_to_bytes( $val ) {
		$val = trim($val);
		$last = strtolower(substr($val, -1));
		if ( in_array($last, ['g','m','k'])){
			switch ($last) {
				case 'g':
					$val *= 1024*1024*1024;
					break;
				case 'm':
					$val *= 1024*1024;
					break;
				case 'k':
					$val *= 1024;
			}
		}
		return $val;
	}
	
	public function allowAllTyepes($suffix = null) {
		$this->typeCheckingOn = false;
		if( !is_null($suffix)) {
			if( strpos($suffix, '.') === 0 or $suffix == '') {
				$this->suffix = $suffix;
			} else {
				$this->suffix = ".$suffix";
			}
		}
	}
	
	public static function convert_from_bytes($bytes) {
		$bytes /= 1024;
		if ( $bytes > 1024) {
			return number_format($bytes/1024, 1) . ' MB';
		} else {
			return number_format($bytes, 1) . ' KB';
		}
	}

}


?>