<?

error_reporting(E_ALL);	ini_set("display_errors", 1);

include "../Axis_Header.php";
/*
require '/home/knlab/www/vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$hosts=[
	'https://index.knlab.kr:9200',
];
$client = ClientBuilder::create()->setHosts($hosts)->build();

*/
$params=[
	'title' => 'test',
	'code' => 123,
];

$result = $Mem->docs->test($params);
// Now we loop until the scroll "cursors" are exhausted
print_r($result);

?>

<div id="article" style="width:500px;">
</div>
