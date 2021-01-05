<?

error_reporting(E_ALL);	ini_set("display_errors", 1);

include "_head.php";
require '/home/knlab/www/vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$hosts=[
	'https://index.knlab.kr:9200',
];
$client = ClientBuilder::create()->setHosts($hosts)->build();
$params=[
	'client' => [
        'timeout' => 10,        // ten second timeout
        'connect_timeout' => 10
	],
	'body' => [
		'query' => [
			'match' => [
				'caption' => 'policy'
			]
		]
	]
];
$result= $client->search($params);
print_r($result);
$headers = apache_request_headers();

foreach ($headers as $header => $value) {
	echo "$header: $value <br />";
}

/*
foreach($Mem->es->search($params) as $list){*/
?>
	<img src="<?=$list?>" height="100" width="auto">
<p>http://1.214.203.131:8088</p>
