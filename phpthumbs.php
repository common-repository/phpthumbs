<?php
add_filter('the_content', 'vertical_phpthumbs');

/*
Plugin Name: phpThumbs
Plugin URI: http://maavrik.net/
Version: 0.1
Description: vertically centers thumbnails of class phpthumb
Author: Acc
Author URI: http://maavfork.net/
*/

/**
 * function vertical_phpthumbs() - processes a post and adds
 * a margin-top attribute to each .phpthumb img in order to
 * vertically center them within a phpthumb div.
 * NOTE: only works when called from inside the WordPress loop!
 * @global object $post
*/

function complete_link($in) // make sure the URL is properly formed:
{	// strip beginning & ending spaces
	$in = trim($in);
	if((strpos($in, "http://", 0) === false) )
	{
		if($in[0]=='/') $in = get_option('home') . $in;
		else $in = get_option('home') . $_SERVER['REQUEST_URI'] . $in;
	}
	return $in;
}

function vertical_phpthumbs($process) {
/*	global $post;
	ob_start();
	the_content($in);
	$process = ob_get_clean();*/
	$counter = 0;
	$find_l = "<img class=\"phpthumb" ;
	$find = "class=\"phpthumb";

	// find thumbnail max-height and max-width.
	$css = file_get_contents(get_bloginfo('stylesheet_url'));
	$srcbegin = strpos($css, ".phpthumb_active {");
	$srcend = strpos($css, "}", $srcbegin);
	$css = substr($css, $srcbegin, $srcend-$srcbegin+1);

	$srcbegin = strpos($css, "pheight:") + strlen("pheight:");
	$srcend = strpos($css, "px", $srcbegin);
	$str = substr($css, $srcbegin, $srcend-$srcbegin);
	$max_height = intval($str);

	$srcbegin = strpos($css, "pwidth:") + strlen("pwidth:");
	$srcend = strpos($css, "px", $srcbegin);
	$str = substr($css, $srcbegin, $srcend-$srcbegin);
	$max_width = intval(trim($str));

	// good to here!
	
	/*TIME TO PROCESS!*/
	
	$pos = 0;
	while( ! ( ($pos = strpos($process, $find_l, $pos)) === false) )
	{	/* this part happens for each occurance of $find in $process */
		// find first instance of '>' starting from $pos
		$end = strpos($process, ">", $pos);
		// put $pos to $end into $var
		$var = substr($process, $pos, $end-$pos+1);
		// extract src="..." into $src to get [img address]
			// $srcbegin is the beginning position of the img src address, past the first quotation mark
			$srcbegin = strpos($var,"\"",strpos($var, "src=")+3)+1;
			// $srcend is the end position of the img src address, before the second quotation mark
			$srcend = strpos($var,"\"",$srcbegin);
		$src = substr($var, $srcbegin, $srcend-$srcbegin);
		// make sure the URL is properly formed:
		$src=complete_link($src);
		// pull dimensions from img at [img address] into vars: height, width
		$size=@getimagesize($src); // get values
		if(!is_array($size)) $size=array(0,0); // if image is broken, pretend it's a 0x0
		$height = $size[1]; 
		$width = $size[0];
		// constrain height, width, rounding down, to max_height, max_width
		if($height>$max_height) // constrain to max_height
		{
			$width = floor($max_height*$width/$height);
			$height = $max_height;
		}
		if($width>$max_width) // constrain to max_width
		{
			$height = floor($max_width*$height/$width);
			$width = $max_width;	
		}
		// calculate the margin, rounding up or down
		$margin_top = floor(($max_height-$height)/2);
		$margin_side = floor(($max_width-$width)/2);
		// in $var, replace: class="phpthumb with: style="margin-top:$margin px" class="phpthumb
		$srcbegin = strpos($var,$find);

		$replace =
			substr($var, 0, $srcbegin) . 
			"style=\"height:" . $height . 
			"px; width:" . $width . 
			"px; margin-top:" . $margin_top . 
			"px; margin-bottom:" . $margin_top . 
			"px; margin-left:" . $margin_side . 
			"px; margin-right:" . $margin_side . 
			"px;\" class=\"phpthumb" . 
			substr($var, $srcbegin+strlen($find));

		// replace this section of $process with the new $var
		$left = substr($process, 0, $pos);
		$right = substr($process, $pos + strlen($var));
		//$process = $left . "<div class=\"phpthumb\">" . $replace . "</div>" . $right;
		$process = $left . $replace . $right;
	}	/* make sure $process changes or else there'll be an infinite loop! */

	$process = str_replace("phpthumb","phpthumb_active", $process); //need a better way to do this...
	$process = str_replace("class=\" ", "class=\"", $process);
	$process = str_replace("class=\"\"", '', $process);

	/*DONE PROCESSING!*/
	
	return $process;
}

?>