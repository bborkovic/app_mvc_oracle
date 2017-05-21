<?php

namespace Core;


class ResizeImages {
	protected $images = [];
	protected $source, $destination;
	protected $mimeTypes = [ 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];
	protected $webpSupported = true, $useImagesScale = true;
	protected $invalid = [];
	protected $outputSizes = [];
	protected $useLongerDimension;
	protected $jpegQuality = 75, $pngCompression = 0;
	protected $resample = IMP_BILINEAR_FIXED;
	protected $watermark;
	protected $markW, $markH, $markType, $marginR, $marginB;
	protected $generated = [];
	
	
	public function __construct( array $images , $sourceDirectory = null ) {
		if( !is_null($sourceDirectory) && !is_dir($sourceDirectory)) {
			throw new \Exception("$sourceDirectory is not a valid directory.");
		}
	
		$this->images = $images;
		$this->source = $sourceDirectory;

		if ( PHP_VERSION_ID < 50500) {
			array_pop($this->mimeTypes); // take the last element of array
			$this->webpSupported = false;
		}
		if ( PHP_VERSION_ID < 50519 
				or (PHP_VERSION_ID >= 50600 and PHP_VERSION_ID < 50603) 
			) {
			$this->useImagesScale = false;
		}
		$this->checkImages();
	}
	
	protected function checkImages() {
		foreach ($this->images as $i => $image) {
			$this->images[$i] = [];
			if($this->source) {
				$this->images[$i]['file'] = $this->source . DIRECTORY_SEPARATOR . $image;
			} else {
				$this->images[$i]['file'] = $image;
			}
			if(file_exists($this->images[$i]['file']) && is_readable(file_exists($this->images[$i]['file'])) ){
				$size = getimagesize( $this->images[$i]['file'] );
				if( $size === false && $this->webpSupported && mime_content_type($this->images[$i]['file']) == 'image/webp'){
					$this->images[$i] = $this->getWebpDetails( $this->images[$i]['file'] );
				} elseif( $size[0] === 0 || !in_array($size['mime'] , $this->mimeTypes)) {
					$this->ivalid[] = $this->images[$i]['file'];
				} else {
					if($size['mime'] == 'image/jpeg') {
						//
					}
				}
			} else {
				$this->invalid[] = $this->images[$i]['file'];
			}
		}
	}
	
	protected function getWebpDetails($image) {
		$details = [];
		$resource = imagecreatefromwebp($image);
		$details['file'] = $image;
		$details['w'] = imagex($resource);
		$details['h'] = imagey($resource);
		$details['type'] = 'image/webp';
		imagedestroy($resource);
		return $details;
	}
	
}


?>