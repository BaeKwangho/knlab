<?
include "../../_h.php";
//error_reporting(E_ALL);	ini_set("display_errors", 1);

$type = array_keys($_GET)[0];

$fq = array(
	'custom' => array(
		'query' => '*:*',
	),
);

$select = array(
  'query'         => "*:*",
  'start'         => 0,
  'rows'          => 5,
  'fields'        => array('*'),
  'sort'          => array('creationdate' => 'desc'),
  'filterquery' => $fq,
);
$result = $Mem->gps->select($select);

function getColorByNum($num){
  for($i=0;$i<10;$i++){
    if($num/pow(10,$i)>=1 && $num/pow(10,$i)<10){return $i+2;
    }else{
      continue;
    }
  }
}

$data=array();
$groups = $Mem->gps->groupSelect();
foreach ($groups as $groupKey => $fieldGroup) {
  foreach ($fieldGroup as $valueGroup) {
    $small = array();
    if(null===$valueGroup->getValue()){
      $value = 'none';
    }else{
      $value = $valueGroup->getValue();
    }
    $small['id']=$value;
    $small['color']=getColorByNum($valueGroup->getNumFound());
    $small['value']=$valueGroup->getNumFound();
    array_push($data,$small);
  }
}
echo "<input type='hidden' id='data' value='".json_encode($data)."'>";

#####################################
##### 2번째, 일별 수집 현황 가져오기###
#####################################

#$curtime=time()- 60 * 60 * 24 * 430;
$curtime = 1583498158;
$curday = conv_solr_time($curtime);

$collect = array();
for($i=1;$i<=7;$i++){
  $temp = array();
  $beftime=$curtime-60 * 60 * 24;
  $befday= conv_solr_time($beftime);
  $fq = array(
    'custom' => array(
      'query' => 'created_at:['.$befday.' TO '.$curday.']',
    ),
  );
  $select = array(
    'query'         => "*:*",
    'start'         => 0,
    'rows'          => 5,
    'fields'        => array('*'),
    'sort'          => array('creationdate' => 'desc'),
    'filterquery' => $fq,
  );
  $result = $Mem->gps->select($select);
  $temp['date']=$befday;
  $temp['value']=$result->getNumFound();

  array_push($collect,$temp);
  
  $curday = $befday;
  $curtime= $beftime;
}
echo "<input type='hidden' id='collect' value='".json_encode($collect)."'>";

?>