<?php
//Include classes

$classes = scandir("../code/classes");

foreach($classes as $filename) {
	if($filename != "." && $filename != "..") {
		include("../code/classes/" . $filename);	
	}
}
?>