<?

error_reporting(E_ALL);	ini_set("display_errors", 1);

include "_head.php";
/*
require '/home/knlab/www/vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$hosts=[
	'https://index.knlab.kr:9200',
];
$client = ClientBuilder::create()->setHosts($hosts)->build();

*/
$params=[
	'scroll'=>'30s',
	'size' => 20,
	'client' => [
        'timeout' => 10,       
        'connect_timeout' => 10
	],
	'body' => [
		'query' => [
			'match' => [
				'caption' => 'to'
			]
		]
	]
];

$response = $Mem->es->$client->search($params);

// Now we loop until the scroll "cursors" are exhausted
while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {

    // **
    // Do your work here, on the $response['hits']['hits'] array
    // **
	print_r(json_encode($response['hits']['hits'], JSON_PRETTY_PRINT));
	echo "<br><br>";


    // When done, get the new scroll_id
    // You must always refresh your _scroll_id!  It can change sometimes
    $scroll_id = $response['_scroll_id'];

    // Execute a Scroll request and repeat
    $response = $Mem->es->$client->scroll([
        'body' => [
            'scroll_id' => $scroll_id,  //...using our previously obtained _scroll_id
            'scroll'    => '30s'        // and the same timeout window
        ]
    ]);
}
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
