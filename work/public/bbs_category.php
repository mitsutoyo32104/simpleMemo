<?php

$json = '[]';

if(isset($_REQUEST['main-category']) == true && $_REQUEST['main-category'] != ''){
	$parameter = $_REQUEST['main-category'];
}
else {
	goto end;
}

if($parameter === "仕事") {
  $json = "[{\"name\":\"★★★\"},{\"name\":\"★★\"},{\"name\":\"★\"}]";
} 
elseif ($parameter === "私用") {
  $json = "[{\"name\":\"★★\"},{\"name\":\"★\"}]";
} 
else {
  $json = "[{\"name\":\"★\"} ]";
}

end:

header('Content-Type:application/json; charset=UTF-8');
echo ($json);