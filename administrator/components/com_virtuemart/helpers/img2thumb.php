<?php

defined ('_JEXEC') or die();

/**
 * class Image2Thumbnail
 * Thumbnail creation with PHP4 and GDLib (recommended, but not mandatory: 2.0.1 !)
 *
 * @author      Andreas Martens <heyn@plautdietsch.de>
 * @author      Patrick Teague <webdude@veslach.com>
 * @author      Soeren Eberhardt <soeren|at|virtuemart.net>
 * @author	    Max Milbers
 *@version	1.0b
 *@date       modified 11/22/2004
 *@modifications
 *   - added support for GDLib < 2.0.1
 *	- added support for reading gif images
 *	- makes jpg thumbnails
 *	- changed several groups of 'if' statements to single 'switch' statements
 *   - commented out original code so modification could be identified.
 * @copyright 2004? The Copyright maybe got lost. So I set now our latest known date (by svn)
 * @copyright 2011 - 2018 The VirtueMart Team
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */

class Img2Thumb	{
// New modification
/**
*	private variables - do not use
*
*	@var int $bg_red				0-255 - red color variable for background filler
*	@var int $bg_green				0-255 - green color variable for background filler
*	@var int $bg_blue				0-255 - blue color variable for background filler
*	@var int $maxSize				0-1 - true/false - should thumbnail be filled to max pixels
*/
	var $bg_red;
	var $bg_green;
	var $bg_blue;
	var $maxSize;
	/**
	 * @var string Filename for the thumbnail
	 */
	var $fileout;

/**
*   Constructor - requires following vars:
*
*	@param string $filename			image path
*
*	These are additional vars:
*
*	@param int $newxsize			new maximum image width
*	@param int $newysize			new maximum image height
*	@param string $fileout			output image path
*	@param int $thumbMaxSize		whether thumbnail should have background fill to make it exactly $newxsize x $newysize
*	@param int $bgred				0-255 - red color variable for background filler
*	@param int $bggreen				0-255 - green color variable for background filler
*	@param int $bgblue				0-255 - blue color variable for background filler
*
*/
	function __construct($filename, $newxsize=60, $newysize=60, $fileout='',
		$thumbMaxSize=0, $bgred=0, $bggreen=0, $bgblue=0)
	{

		//Some big pictures need that
		VmConfig::ensureMemoryLimit(128);

		//	New modification - checks color int to be sure within range
		if($thumbMaxSize)
		{
			$this->maxSize = true;
		}
		else
		{
			$this->maxSize = false;
		}
		if($bgred>=0 || $bgred<=255)
		{
			$this->bg_red = $bgred;
		}
		else
		{
			$this->bg_red = 0;
		}
		if($bggreen>=0 || $bggreen<=255)
		{
			$this->bg_green = $bggreen;
		}
		else
		{
			$this->bg_green = 0;
		}
		if($bgblue>=0 || $bgblue<=255)
		{
			$this->bg_blue = $bgblue;
		}
		else
		{
			$this->bg_blue = 0;
		}

		$this->NewImgCreate($filename,$newxsize,$newysize,$fileout);
	}

/**
*
*	private function - do not call
*
*/
	private function NewImgCreate($filename,$newxsize,$newysize,$fileout)
	{

		if(function_exists('imagecreatefromstring')){

			if( substr( $filename, 0, 2) == "//" ) {
				try {
					$resObj = VmConnector::getHttp(array(), array('curl', 'stream'))->get($filename);
					//vmdebug('Object per URL',$resObj);
					if($resObj->code!=200){
						vmdebug('URL does not exists',$filename,$resObj);
						vmError(vmText::sprintf('COM_VIRTUEMART_FILE_NOT_FOUND',$filename));
					} else {
						$content = $resObj->body;
					}
				} catch (RuntimeException $e) {
					vmError(vmText::sprintf('COM_VIRTUEMART_FILE_NOT_FOUND',$filename));
				}
			} else {
				$content = file_get_contents($filename);
			}

			if($content){
				$gd = @imagecreatefromstring($content);
				if ($gd === false) {
					vmError('Img2Thumb NewImgCreate with imagecreatefromstring failed '.$filename.' ');
				} else {
					$pathinfo = pathinfo( $fileout );
					$type = empty($type)? $pathinfo['extension']:$type;
					$this->fileout = $fileout;

					$orig_size = 0;
					if( substr( $filename, 0, 2) == "//" ) {
						if (!empty($fileout))
						{
							$this-> NewImgSave($gd,$fileout,$type);
							$orig_size = getimagesize($fileout);
						}
					}

					$new_img =$this->NewImgResize($gd,$newxsize,$newysize,$filename,$orig_size);
					if (!empty($fileout))
					{
						$this-> NewImgSave($new_img,$fileout,$type);
					}
					else
					{
						$this->NewImgShow($new_img,$type);
					}

					if($new_img) ImageDestroy($new_img);
					ImageDestroy($gd);
				}
			}

		} else {
			$type = $this->GetImgType($filename);

			$pathinfo = pathinfo( $fileout );

			$type = empty($type)? $pathinfo['extension']:$type;

			if( empty( $pathinfo['extension'])) {
				$fileout .= '.'.$type;
			}
			$this->fileout = $fileout;

			switch($type){

				case "gif":
					// unfortunately this function does not work on windows
					// via the precompiled php installation :(
					// it should work on all other systems however.
					if( function_exists("imagecreatefromgif") ) {

						$orig_img = imagecreatefromgif($filename);
					} else {
						$app = JFactory::getApplication();
						$app->enqueueMessage('This server does NOT suppport auto generating Thumbnails by gif');
						return false;
					}
					break;
				case "jpg":
					if( function_exists("imagecreatefromjpeg") ) {
						if($this->check_jpeg($filename,true)){
							$orig_img = imagecreatefromjpeg($filename);
						} else {
							vmWarn('Img2Thumb NewImgCreate $orig_img empty, type was not in switch for file '.$filename.' this happens due missing exif data or broken origin file');
							return false;
						}

					} else {
						$app = JFactory::getApplication();
						$app->enqueueMessage('This server does NOT suppport auto generating Thumbnails by jpg');
						return false;
					}
					break;
				case "png":
					if( function_exists("imagecreatefrompng") ) {
						$orig_img = imagecreatefrompng($filename);
					} else {
						$app = JFactory::getApplication();
						$app->enqueueMessage('This server does NOT suppport auto generating Thumbnails by png');
						return false;
					}
					break;
				case "webp":
					if( function_exists("imagecreatefromwebp") ) {
						$orig_img = imagecreatefromwebp($filename);
					} else {
						$app = JFactory::getApplication();
						$app->enqueueMessage('This server does NOT suppport auto generating Thumbnails by webp');
						return false;
					}
					break;
			}

			if(empty($orig_img)){
				vmWarn('Img2Thumb NewImgCreate $orig_img empty, type was not in switch for file '.$filename.' this happens due missing exif data or broken origin file');
				return false;
			} else {
				$new_img =$this->NewImgResize($orig_img,$newxsize,$newysize,$filename);
				if (!empty($fileout))
				{
					$this-> NewImgSave($new_img,$fileout,$type);
				}
				else
				{
					$this->NewImgShow($new_img,$type);
				}

				ImageDestroy($new_img);
				ImageDestroy($orig_img);
			}
		}
	}

	/**
	 * check for jpeg file header and footer - also try to fix it
	 * @author willertan1980 at yahoo dot com http://www.php.net/manual/de/function.imagecreatefromjpeg.php
	 * @param $f
	 * @param bool $fix
	 * @return bool
	 */
	function check_jpeg($f, $fix=false ){

		if ( false !== (@$fd = fopen($f, 'r+b' )) ){
			if ( fread($fd,2)==chr(255).chr(216) ){
				fseek ( $fd, -2, SEEK_END );
				if ( fread($fd,2)==chr(255).chr(217) ){
					fclose($fd);
					return true;
				}else{
					if ( $fix && fwrite($fd,chr(255).chr(217)) ){vmdebug('corrected jpg '.$f);return true;}
					fclose($fd);
					vmInfo('broken jpg, cannot create thumb '.$f);
					return false;
				}
			}else{fclose($fd); return false;}
		}else{
			vmWarn('check_jpeg could not open file '.$f);
			return false;
		}
	}

	/**

	/**
*	Maybe adding sharpening with
*            $sharpenMatrix = array
            (
                array(-1.2, -1, -1.2),
                array(-1, 20, -1),
                array(-1.2, -1, -1.2)
            );

            // calculate the sharpen divisor
            $divisor = array_sum(array_map('array_sum', $sharpenMatrix));

            $offset = 0;

            // apply the matrix
            imageconvolution($img, $sharpenMatrix, $divisor, $offset);
*
*	private function - do not call
*	includes function ImageCreateTrueColor and ImageCopyResampled which are available only under GD 2.0.1 or higher !
*/
	private function NewImgResize($orig_img,$newxsize,$newysize,$filename, $orig_size = 0)
	{
		//getimagesize returns array
		// [0] = width in pixels
		// [1] = height in pixels
		// [2] = type
		// [3] = img tag "width=xx height=xx" values
		//vmdebug('NewImgResize INPUT $newxsize,$newysize',$newxsize,$newysize);
		if(empty($orig_size))$orig_size = getimagesize($filename);

		$newxsizeF = floatval($newxsize);
		$newysizeF = floatval($newysize);
		if(empty($newxsize) and empty($newysize)){
			vmWarn('NewImgResize failed x,y = 0','NewImgResize failed x,y = 0');
			return false;
		} else {
			if(empty($newxsize)){
				//Recalculate newxsize
				$newxsizeF = $newysizeF/$orig_size[1] * $orig_size[0];
				$newxsize = intval($newxsizeF);
			} else if(empty($newysize)){
				$newysizeF = $newxsizeF/$orig_size[0] * $orig_size[1];
				$newysize = intval($newysizeF);
			}
		}

		$maxX = ($newxsize);
		$maxY = ($newysize);
		vmdebug('NewImgResize $newxsize,$newysize 1',$newxsizeF,$newysizeF);
		if ($orig_size[0]<$orig_size[1])
		{
			$newxsizeF = $newysizeF * ((float)$orig_size[0]/(float)$orig_size[1]);
			$newxsize = intval($newxsizeF);
			$adjustX = intval(((float)$maxX - $newxsizeF)/2);
			$adjustY = 0;
		}
		else
		{
			$newysizeF = $newxsizeF / ((float)$orig_size[0]/(float)$orig_size[1]);
			$newysize = intval($newysizeF);
			$adjustX = 0;
			$adjustY = intval(((float)$maxY - $newysizeF)/2);
		}
		vmdebug('NewImgResize $newxsize,$newysize 2',$newxsize,$newysize,$orig_size);

		/* Original code removed to allow for maxSize thumbnails
		$im_out = ImageCreateTrueColor($newxsize,$newysize);
		ImageCopyResampled($im_out, $orig_img, 0, 0, 0, 0,
			$newxsize, $newysize,$orig_size[0], $orig_size[1]);
		*/

		//	New modification - creates new image at maxSize
		if( $this->maxSize )
		{
			if( function_exists("imagecreatetruecolor") )
			  $im_out = imagecreatetruecolor($maxX,$maxY);
			else
			  $im_out = imagecreate($maxX,$maxY);

			// Need to image fill just in case image is transparent, don't always want black background
			$bgfill = imagecolorallocate( $im_out, $this->bg_red, $this->bg_green, $this->bg_blue );

			if( function_exists( "imageAntiAlias" )) {
				imageAntiAlias($im_out,true);
			}
 		    imagealphablending($im_out, false);
		    if( function_exists( "imagesavealpha")) {
		    	imagesavealpha($im_out,true);
		    }
		    if( function_exists( "imagecolorallocatealpha")) {
		    	$transparent = imagecolorallocatealpha($im_out, 255, 255, 255, 127);
		    }

			//imagefill( $im_out, 0,0, $bgfill );
			if( function_exists("imagecopyresampled") ){
				ImageCopyResampled($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
			}
			else {
				ImageCopyResized($im_out, $orig_img, $adjustX, $adjustY, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
			}

		}
		else
		{
			if( function_exists("imagecreatetruecolor") )
			  $im_out = ImageCreateTrueColor($newxsize,$newysize);
			else
			  $im_out = imagecreate($newxsize,$newysize);

			if( function_exists( "imageAntiAlias" ))
			  imageAntiAlias($im_out,true);
 		    imagealphablending($im_out, false);
		    if( function_exists( "imagesavealpha"))
			  imagesavealpha($im_out,true);
		    if( function_exists( "imagecolorallocatealpha"))
			  $transparent = imagecolorallocatealpha($im_out, 255, 255, 255, 127);

			if( function_exists("imagecopyresampled") )
			  ImageCopyResampled($im_out, $orig_img, 0, 0, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
			else
			  ImageCopyResized($im_out, $orig_img, 0, 0, 0, 0, $newxsize, $newysize,$orig_size[0], $orig_size[1]);
		}

		return $im_out;
	}

	/**
*
*	private function - do not call
*
*/
	private function NewImgSave($new_img,$fileout,$type)
	{
		if( !@is_dir( dirname($fileout))) {
			@mkdir( dirname($fileout) );
		}
		switch($type)
		{
			case "gif":
				if( !function_exists("imagegif") )
				{
					if (strtolower(substr($fileout,strlen($fileout)-4,4))!=".gif") {
						$fileout .= ".png";
					}
					return imagepng($new_img,$fileout);

				}
				else {
					if (strtolower(substr($fileout,strlen($fileout)-4,4))!=".gif") {
						$fileout .= '.gif';
					}
					return imagegif( $new_img, $fileout );

				}
				break;
			case "jpg":
				if (strtolower(substr($fileout,strlen($fileout)-4,4))!=".jpg")
					$fileout .= ".jpg";
				$quality = VmConfig::get('img_quality', 89);
				return imagejpeg($new_img, $fileout, $quality);
				break;
			case "png":
				if (strtolower(substr($fileout,strlen($fileout)-4,4))!=".png")
					$fileout .= ".png";
				return imagepng($new_img,$fileout);
				break;
			case "webp":
				if (strtolower(substr($fileout,strlen($fileout)-5,5))!=".webp")
					$fileout .= ".webp";
				return imagewebp($new_img,$fileout);
				break;
		}
	}

	/**
*
*	private function - do not call
*
*/
	private function NewImgShow($new_img,$type)
	{
		/* Original code removed in favor of 'switch' statement
		if ($type=="png")
		{
			header ("Content-type: image/png");
			 return imagepng($new_img);
		}
		if ($type=="jpg")
		{
			header ("Content-type: image/jpeg");
			 return imagejpeg($new_img);
		}
		*/
		switch($type)
		{
			case "gif":
				if( function_exists("imagegif") )
				{
					header ("Content-type: image/gif");
					return imagegif($new_img);
					break;
				}
				//either there is missing a break or the else $this->NewImgShow is unecessary
				else
					$this->NewImgShow( $new_img, "jpg" );

			case "jpg":
				header ("Content-type: image/jpeg");
				return imagejpeg($new_img);
				break;
			case "png":
				header ("Content-type: image/png");
				return imagepng($new_img);
				break;
			case "webp":
				header ("Content-type: image/webp");
				return imagewebp($new_img);
				break;
		}
	}

	/**
*
*	private function - do not call
*
*/
	private function GetImgType($filename) {

		$imageExtensionsArray = array(
			IMAGETYPE_GIF => 'gif',
			IMAGETYPE_JPEG => 'jpg',
			IMAGETYPE_PNG => 'png',
			IMAGETYPE_SWF => 'swf',
			IMAGETYPE_PSD => 'psd',
			IMAGETYPE_BMP => 'bmp',
			IMAGETYPE_TIFF_II => 'tiff',
			IMAGETYPE_TIFF_MM => 'tiff',
			IMAGETYPE_JPC => 'jpc',
			IMAGETYPE_JP2 => 'jp2',
			IMAGETYPE_JPX => 'jpx',
			IMAGETYPE_JB2 => 'jb2',
			IMAGETYPE_SWC => 'swc',
			IMAGETYPE_IFF => 'iff',
			IMAGETYPE_WBMP => 'wbmp',
			IMAGETYPE_XBM => 'xbm',
			IMAGETYPE_ICO => 'ico',
			IMAGETYPE_WEBP => 'webp',
			IMAGETYPE_AVIF => 'avif'
		);

		$info = getimagesize($filename);
		if (isset($imageExtensionsArray[$info[2]])){
			return $imageExtensionsArray[$info[2]];
		}
		else return false;
	}

}