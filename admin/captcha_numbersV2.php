<?
/**
* CaptchaV2 File
* Generates CAPTCHA Numbers and Chars Image
* @author Albert Demeter <borex8@hotmail.com>
* @version 2.1
* GNU General Public License (Version 2.1, March 7th, 2006)
*
* based on Hadar Porat's Captcha Numbers class v.1.5 <hpman28@gmail.com>
*
* This program is free software; you can redistribute
* it and/or modify it under the terms of the GNU
* General Public License as published by the Free
* Software Foundation; either version 2 of the License,
* or (at your option) any later version.
*
* This program is distributed in the hope that it will
* be useful, but WITHOUT ANY WARRANTY; without even the
* implied warranty of MERCHANTABILITY or FITNESS FOR A
* PARTICULAR PURPOSE. See the GNU General Public License
* for more details.
*/

/**
* CaptchaNumbersV2 Class
* @access public
* @author Albert Demeter <borex8@hotmail.com>
* @version 2.0
*/

class CaptchaNumbersV2 {
	var $length = 6;
	var $font = './arial.ttf';
	var $size = 15;
	var $angle = 10;
	var $type = 'png';
	var $height = 40;
	var $width = 80;
	var $grid = 7;
	var $string = '';
	var $captchaType = 'mixed';
	
	// automatically calculated based on the percent in the constructor
	var $dotsCount = 0;
	
	// background colors RED, GREEN and BLUE values (provide it as a number between 0-255)
	var $bgColorRed = 240;
	var $bgColorGreen = 240;
	var $bgColorBlue = 240;
	
	// if true then grid is drawn
	var $drawGrid = false;
	
	// if true then every grid line will have a different color
	var $randomGridColor = true;

	// if true then every letter will have a different color
	var $randomLetterColor = true;

	// fonts
	// there is feature of this script that it picks random fonts for each character displayed
	// you should define a fonts folder
	// a folder named fonts within this scripts directory is tested and working.	
	var $fonts_folder = 'fonts/';
	// the range of font size of each letter displayed as pixels. if you choose them same, fonts will be at that exact size.
	var $font_size_min = 14;
	var $font_size_max = 16;
	// font angles are for placing the fonts at a random angle. high values of angles might result unexpected.
	var $font_angle_min = 0;
	var $font_angle_max = 15;

	/**
	* @return void
	* @param int $length string length
	* @param int $size font size
	* @param String $type image type
	* @param String $captchaType text contain digits, chars or mixed
	* @param int $dotsPercent random pixels generated in image
	* @desc generate the main image
	*/	
	function CaptchaNumbersV2($length = '', $size = '', $type = '', $captchaType = '', $dotsPercent = 10) {

		if ($length!='') $this -> length = $length;
		if ($size!='') $this -> size = $size;
		if ($type!='') $this -> type = $type;
		if ($captchaType!='') $this -> captchaType = $captchaType;

		$this -> width = $this -> length * $this -> size + $this -> grid + $this -> grid;
		$this -> height = $this -> size + (2 * $this -> grid);
		
		// dots count equals #% of all points in the image
		$this -> dotsCount = round(($this -> width * $this -> height) * $dotsPercent / 100);
		
		$this -> generateString();
	}

	/**
	* @return void
	* @desc display captcha image
	*/		
	function display() {
		$this -> sendHeader();
		$image = $this -> generate();

		switch ($this-> type) {
			case 'jpeg': imagejpeg($image); break;
			case 'png':  imagepng($image);  break;
			case 'gif':  imagegif($image);  break;
			default:     imagepng($image);  break;
		}
	}

// following function is gathered from php.net, author is "fankounter@libero.it"
	// it is used for gathering the fonts from the spesicfied folder. I have modifeid it slightly
	function ls($__dir="./",$__pattern="*.*")
	{
		settype($__dir, "string");
		settype($__pattern, "string");
		$__ls = array();
		$__regexp = preg_quote($__pattern,"/");
		$__regexp = preg_replace("/[\\x5C][\x2A]/",".*",$__regexp);
		$__regexp = preg_replace("/[\\x5C][\x3F]/",".", $__regexp);
		if(is_dir($__dir))
		 if(($__dir_h=@opendir($__dir))!==FALSE)
		 {
		  while(($__file=readdir($__dir_h))!==FALSE)
		   if(preg_match("/^".$__regexp."$/",$__file))
			array_push($__ls,$__file);
		  closedir($__dir_h);
		  sort($__ls,SORT_STRING);
		 }
		return $__ls;
	}
	
	// function picks a random font for each letter each time
	function random_font() {
	
//		$fonts = $this -> ls($this -> fonts_folder, "*.ttf");
//		
//		$rand = mt_rand(0, sizeof($fonts)-1);
//		
//		$this -> font = $this -> fonts_folder.$fonts[$rand];
	}
	
	// the font size would be determined randomly within the limits defined above.
	function random_font_size() {
	
		$this -> size = mt_rand($this -> font_size_min, $this -> font_size_max );
	}
	
	// the angle would be determined randomly within the limits defined above.
	function random_font_angle() {
	
		$this -> angle = mt_rand($this -> font_angle_min, $this -> font_angle_max );
	}
	
	/**
	* @return Image
	* @desc generate the image
	*/		
	function generate() {
		$image = ImageCreate($this -> width, $this -> height) or die("Cannot Initialize new GD image stream");
		
		// colors
		$background_color = ImageColorAllocate($image, $this -> bgColorRed, $this -> bgColorGreen, $this -> bgColorBlue);
		if (!$this -> randomLetterColor)
		{
  		$net_color_1 = ImageColorAllocate($image, 10, 200, 10);
  		$net_color_2 = ImageColorAllocate($image, 200, 10, 10);
		}
		if (!$this -> randomLetterColor)
		{
  		$stringcolor_1 = ImageColorAllocate($image, 150, 150, 150);
  		$stringcolor_2 = ImageColorAllocate($image, 120, 120, 250);
		}

		if ($this -> drawGrid)
  		for ($i = $this -> grid; $i < $this -> height; $i+=$this -> grid)
			{ 
    		if ($this -> randomGridColor)
    		{
      		$net_color_1 = ImageColorAllocate($image, mt_rand(0,255), mt_rand(0,250), mt_rand(0,250));
      		$net_color_2 = ImageColorAllocate($image, mt_rand(0,255), mt_rand(0,250), mt_rand(0,250));
    		}
  		 	if ($i%2) ImageLine($image, 0, $i, $this -> width, $i, $net_color_1);
  				 else ImageLine($image, 0, $i, $this -> width, $i, $net_color_2);
			}

		// make the text
		$str = $this -> getString();
		$x = $this -> grid;
		for($i = 0, $n = strlen($str); $i < $n; $i++)
		{
  		if ($this -> randomLetterColor)
  		{
    		$stringcolor_1 = ImageColorAllocate($image, mt_rand(0,255), mt_rand(0,250), mt_rand(0,250));
    		$stringcolor_2 = ImageColorAllocate($image, mt_rand(0,255), mt_rand(0,250), mt_rand(0,250));
  		}
			$this -> random_font();
			$this -> random_font_size();
			$this -> random_font_angle();
		 	if ($i%2) ImageTTFText($image, $this -> size, $this -> angle, $x, $this -> size + $this -> grid - mt_rand(0, 5), $stringcolor_1, $this -> font, $str{$i} );
				 else ImageTTFText($image, $this -> size, $this -> angle, $x, $this -> size + $this -> grid + mt_rand(0, 5), $stringcolor_2, $this -> font, $str{$i} );
			$x = $x + $this -> size;
		}
		
		// grid
		if ($this -> drawGrid)
  		for ($i = $this -> grid; $i < $this -> width; $i+=$this -> grid) 
			{ 
    		if ($this -> randomGridColor)
    		{
      		$net_color_1 = ImageColorAllocate($image, mt_rand(0,255), mt_rand(0,250), mt_rand(0,250));
      		$net_color_2 = ImageColorAllocate($image, mt_rand(0,255), mt_rand(0,250), mt_rand(0,250));
    		}
  		 	if ($i%2) ImageLine($image, $i, 0, $i, $this -> height, $net_color_1);
  				 else ImageLine($image, $i, 0, $i, $this -> height, $net_color_2);
			}

		for ($i = 0; $i < $this -> dotsCount; $i++)
		{
		 	$x = rand(0, $this -> width - 1);
			$y = rand(0, $this -> height - 1);
			$rgbIndex = imagecolorat($image, $x, $y);
			$rgb = imagecolorsforindex($image, $rgbIndex);
			//print $x . ' x ' . $y . ' : ' . $rgbIndex . '(' . $rgb['red'] . '-' . $rgb['green'] . '-' . $rgb['blue'] . ')<br>'; 

			// if background color create random pixel color
			if ($rgb['red'] == $this -> bgColorRed
				 	&& $rgb['green'] == $this -> bgColorGreen
					&& $rgb['blue'] == $this -> bgColorBlue)
			{
			 	$dotColor = ImageColorAllocate($image, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
			}
			else
			{
			 	// not background color then generate a close shade color
				$rgb['red'] = $rgb['red'] + ((mt_rand(0,100) % 2) == 1 ? 1 : -1) * mt_rand(0, 255 - $rgb['red']);
				if ($rgb['red'] < 0 || $rgb['red'] > 255) $rgb['red'] = mt_rand(0, 255); 

				$rgb['green'] = $rgb['green'] + ((mt_rand(0,100) % 2) == 1 ? 1 : -1) * mt_rand(0, 255 - $rgb['green']);
				if ($rgb['green'] < 0 || $rgb['green'] > 255) $rgb['green'] = mt_rand(0, 255); 

				$rgb['blue'] = $rgb['blue'] + ((mt_rand(0,100) % 2) == 1 ? 1 : -1) * mt_rand(0, 255 - $rgb['blue']);
				if ($rgb['blue'] < 0 || $rgb['blue'] > 255) $rgb['blue'] = mt_rand(0, 255); 

			 	$dotColor = ImageColorAllocate($image, $rgb['red'], $rgb['green'], $rgb['blue']);
			}
			ImageSetPixel($image, $x, $y, $dotColor);
		}
		
		return $image;
	}


	/**
	* @return String
	* @desc generate the string
	*/	
	function generateString() {
		$string = $this->create_random_value($this -> length, $this -> captchaType);
		$this -> string = $string;
		return true;
	}


	/**
	* @return void
	* @desc send image header
	*/
	function sendHeader() {
//		header('Content-type: image/' . $this -> type);
	}
	
	/**
	* @return String
	* @desc return the string
	*/	
	function getString() {
		return $this -> string;
	}
	
	
  function create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $char = mt_rand(0,9);
      } else {
        $char = chr(mt_rand(0,255));
      }
      if ($type == 'mixed') {
        if (eregi('^[a-z0-9]$', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (eregi('^[a-z]$', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (ereg('^[0-9]$', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }	
}