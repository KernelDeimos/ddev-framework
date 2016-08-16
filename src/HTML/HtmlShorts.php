<?php

namespace DDev\HTML;

class HtmlShorts {
	static function drawTab($text,$from,$to) {
		global $default_page;
		print('<div class="tab"><div class="tabbox" onClick="ajaxLoad(\''.$from.'\',\''.$to.'\')">'.$text.'</div></div>');
		if ($default_page==$text) {
			$default_page=$from;
		}
	}
	static function drawFullBackground($src) {
		print("<img src=\"$src\" style=\"position:fixed;top:0;left:0;width:100%;height:100%;z-index:-20\" />");
	}
	static function includeCSS($src) {
		print('<link rel="stylesheet" type="text/css" href="'.$src.'?'.time().'">');
	}
	static function includeJS($src) {
		print('<script src="'.$src.'?'.time().'"></script>');
	}
	static function includeJQ() {
		print('<script src="http://code.jquery.com/jquery-latest.js"></script>');
	}
	static function includeJSPackage($directory) {
		echo '<script>console.log("'.$directory.'")</script>';
		// Generate path of expected meta file
		$expectedName = "jspackage.json";
		$filename = $directory .'/'. $expectedName;

		// Get data from file and parse
		$raw_data = file_get_contents(SITE_PATH.$filename);
		$json = json_decode($raw_data, true);

		if (
			$json === null
			|| ! array_key_exists('scripts', $json)
			) {
			echo '<script type="text/javascript">console.log("Failed to load JavaScript Package!")</script>';
			echo '<script type="text/javascript">console.log("'.SITE_PATH.$filename.'")</script>';
			return;
		}

		// Include all external files listed
		if (array_key_exists('external', $json)) {
			foreach ($json['external'] as $url) {
				HtmlShortcuts::includeJS($url);
			}
		}

		// Include all JavaScript files listed
		foreach ($json['scripts'] as $filen) {
			HtmlShortcuts::includeJS(WEB_PATH . $directory .'/'. $filen);
		}
	}
	static function metaCharset($set) {
		print('<meta http-equiv="Content-Type" content="text/html" charset="'.$set.'" />');
	}

	static function pre($text) {
		print("<pre>\n");
		print($text."\n");
		print("</pre>\n");
	}
	static function ln() {
		print("\n");
	}
	static function expCSSProp($property, $value) {
		$str="";
		$prefixes = array('-webkit-','-moz-','-o-','-ms-','');
		foreach ($prefixes as $prefix) {
			$str .= $prefix . $property . ":" . $value . ";";
		}
		return $str;
	}
}
?>
