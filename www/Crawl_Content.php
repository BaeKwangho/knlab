<?

error_reporting(E_ALL);	ini_set("display_errors", 1);
include "Axis_Header.php";

$select = array(
    'query'         => '*:*',
    'start'         => 0,
    'rows'          => 5,
    'fields'        => array('title'),
    'sort'          => array('job_id' => 'asc'),
    'filterquery' => array(
        'custom' => array(
            'query' => 'job_id:[* TO 100]',
        ),
    ),
);

$paging = solr_paging($Mem->gps,$select,20,10);
foreach($paging[0] as $doc){
    print_r($doc);
}

/*
$select = array(
    'query'         => '*:*',
    'start'         => 0,
    'rows'          => 3,
    'fields'        => array('*'),
    'sort'          => array('DC_DT_COLLECT' => 'asc'),
    'filterquery' => array(
        'custom' => array(
            'query' => 'DC_CODE:2411* OR DC_CODE:24',
            
        ),
    ),
);
$result = $Mem->docs->select($select);
print($result->getNumFound());
//print("<pre>".print_r($result,true)."</pre>");
foreach ($result as $doc) {
    print_r($doc);
}

*/
/*
$delete = '*:*';
print($Mem->docs->delete($delete));
*/

?>
<div style="padding-top:30px;"><?=$paging[2]?></div>