<?
include "components/header.php";
error_reporting(E_ALL);	ini_set("display_errors", 1);

// 0:수집 1:정제 2:배포

########################################
############ 금일 전체 통계 #############
########################################
if($_GET['recall']){
  $type = $_GET['type'];
  include "../_h.php";
}else{
  include "../_h.php";
  $type = 0;

  $today = date("Y-m-d");
  $befday = conv_solr_time(strtotime($today.'-1 day'));
  $curday = conv_solr_time(strtotime($today));
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
    $collect_num_result = $result->getNumFound();
  
    $fq = array(
      'custom' => array(
        'query' => 'DC_DT_WRITE:['.strtotime($today.'-1 day').' TO '.strtotime($today).']',
      ),
    );
    $select = array(
      'query'         => "*:*",
      'start'         => 0,
      'rows'          => 5,
      'fields'        => array('*'),
      'filterquery' => $fq,
    );
  
    $result = $Mem->gps->select($select);
    $edit_num_result = $result->getNumFound();

$params=[
  'track_total_hits'=> true,
  'index'=>'full_text',
	'client' => [
        'timeout' => 10,
        'connect_timeout' => 10
	],
	'body' => [
		'query' => [
      'bool'=>[
        'filter'=>[
          'range'=>[
            'timestamp'=>[
              'gte'=>$befday,
              'lte'=>$curday
            ]
          ]
        ]
      ]
		]
	]
];
  $result=$Mem->es->simple_search($params);
  $proc_num_result = $result['hits']['total']['value'];

  #####################################
  ######## 국가별 수집 가져오기 ########
  #####################################
  
  // gps solr에만 한정적으로 움직임.

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
}

######### 수집 현황 날짜 정의  ########
$curtime=time()- 60 * 60 * 24 * 430;
#$curtime=time();
$today = date("Y-m-d");
$curday = conv_solr_time(strtotime($today));
$collect = array();


// 수집 화면
if($type==0){ 
  #####################################
  ####### 일별 수집 현황 가져오기 #######
  #####################################
 
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


  if(isset($_GET['recall'])){
    echo "<input type='hidden' id='collect' value='".json_encode($collect)."'/>";  
    exit;
  }else{
    echo "<div id='target'><input type='hidden' id='collect' value='".json_encode($collect)."'/></div>";  
  }



// 정제 화면
}elseif($type==1){
  for($i=1;$i<=7;$i++){
    $temp = array();
    $beftime=$curtime-60 * 60 * 24;
    $befday= conv_solr_time($beftime);
  $res_num = 0;
    $params=[
      'track_total_hits'=> true,
      'index'=>'full_text',
      'client' => [
            'timeout' => 10,       
            'connect_timeout' => 10
      ],
      'body' => [
        'query' => [
          'bool'=>[
            'filter'=>[
              'range'=>[
                'timestamp'=>[
                  'gte'=>$befday,
                  'lte'=>$curday
                ]
              ]
            ]
          ]
        ]
      ]
    ];

    $result=$Mem->es->simple_search($params);


    $temp['date']=$befday;
    $temp['value']=$result['hits']['total']['value'];

    array_push($collect,$temp);

    $curday = $befday;
    $curtime= $beftime;
  }

  echo "<input type='hidden' id='collect' value='".json_encode($collect)."'/>";  

  if(isset($_GET['recall'])){
    exit;
  }
  
  
  
// 배포 화면
}elseif($type==2){ 
  
  #####################################
  ####### 일별 수집 현황 가져오기 #######
  #####################################
  
  $curtime=time()- 60 * 60 * 24 * 430;
  #$curtime=time();
  $collect = array();
  for($i=1;$i<=7;$i++){
    $temp = array();
    $beftime=$curtime-60 * 60 * 24;
    $fq = array(
      'custom' => array(
        'query' => 'dc_dt_write:['.$beftime.' TO '.$curtime.']',
      ),
    );
    $select = array(
      'query'         => "*:*",
      'start'         => 0,
      'rows'          => 5,
      'fields'        => array('*'),
      'sort'          => array('DC_DT_COLLECT' => 'desc'),
      'filterquery' => $fq,
    );
    $result = $Mem->gps->select($select);
    $temp['date']=conv_solr_time($beftime);
    $temp['value']=$result->getNumFound();
  
    array_push($collect,$temp);
    
    $curtime= $beftime;
  }

  echo "<input type='hidden' id='collect' value='".json_encode($collect)."'/>";  

  if(isset($_GET['recall'])){
    exit;
  }
}
echo "<input type='hidden' id='data' value='".json_encode($data)."'/>";

?>
<script>
//_head의 body부분에 걸려있는 이상한 왼쪽 메뉴바 background 제거
$("body").css("background","#F0F0F0");

</script>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/worldHigh.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/dataviz.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>


<!-- Chart code -->
<!-- HTML -->
<div class="c5"  style="background-color:white;">
    <div style="height:520px;" id="worldmap">
    </div>
</div>
<div style="background-color:#F0F0F0;padding:10px">
  <div id="center_page" style="padding:2% 10% 10%">
    <div class="row">
      <div class=" col-md-6" >
        <div class=" card light_shadow">
        <h5>금일 전체 통계</h5>
        <div class="row" style="margin-top:20px;">
          <div class="col-md-4" style="border-right:1px solid lightgray">
            <h4 style="margin-top:10px; margin-bottom:0px;color:#888">수집</h4>
            <div class="row" style="padding:0px 10px">
              <h1 id="day_count1" style="font-size:40px;color:#19CE60;"> </h1>
              <h1 style="font-size:20px;color:#19CE60;margin-top:10px">건</h1>
              <!--font-size:max(1.5vw,20px)-->
            </div>
          </div>
          <div class="col-md-4" style="border-right:1px solid lightgray">
            <h4 style="margin-top:10px; margin-bottom:0px;color:#888">정제</h4>
            <div class="row" style="padding:0px 10px">
              <h1 id="day_count2" style="font-size:40px;color:#19CE60;"> </h1>
              <h1 style="font-size:20px;color:#19CE60;margin-top:10px">건</h1>
            </div>
          </div>
          <div class="col-md-4">
           <h4 style="margin-top:10px; margin-bottom:0px;color:#888">배포</h4>
            <div class="row" style="padding:0px 10px">
              <h1 id="day_count3" style="font-size:40px;color:#19CE60;"> </h1>
              <h1 style="font-size:20px;color:#19CE60;margin-top:10px">건</h1>
            </div>
          </div>
        </div>
        </div>
      </div>
      <div class=" col-md-6">
        <div class="card light_shadow">
          <h5>국가별 통계</h5>
          <p id="selected"></p>
          <div id="piechart" style="min-height:200px"></div>
        </div>
      </div>      
      <div class=" col-md-12">
        <div class="card light_shadow">
          <div class="btn-group btn-group-justified btn-group-toggle pl-5 ml-5" data-toggle="buttons">
            <label class="btn-lg btn btn-outline-primary" >
              <input type="radio" name="subscribed" id="option1" value="0"
              <?=$_GET['subscribed']==0?'checked':''?> onclick="exec_ajax($(this).val())"> 수집
            </label>
            <label class="btn-lg btn btn-outline-success">
              <input type="radio" name="subscribed" id="option2" value="1"
              <?=$_GET['subscribed']==1?'checked':''?> onclick="exec_ajax($(this).val())"> 정제
            </label>
            <label class="btn-lg btn btn-outline-danger">
              <input type="radio" name="subscribed" id="option3" value="2"
              <?=$_GET['subscribed']==2?'checked':''?> onclick="exec_ajax($(this).val())"> 배포
            </label>
          </div>
          <div id="chartdiv" style="min-height:400px"></div>
        </div>
      </div>
      <div class=" col-md-12">
        <div class="card light_shadow">
          <h5>테이블 보기</h5>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              <th>1</th>
              </thead>
              <tbody>
              <tr>
                <td>2</td>
                <td>2</td>
                <td>2</td>
                <td>2</td>
              </tr>
              <tr>
                <td>2</td>
                <td>2</td>
                <td>2</td>
                <td>2</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>   
    </div>
  </div>
</div>

<script>
//new numberCounter("day_count1",$('#solr_num_result').val());
new numberCounter("day_count1",<?=$collect_num_result?>);
new numberCounter("day_count2",<?=$proc_num_result?>);
new numberCounter("day_count3",<?=$edit_num_result?>);

load_pie();
load_map();
load_chart(0);

function load_pie(){
  var chart = am4core.create("piechart", am4charts.PieChart);

// Add data
var items = JSON.parse($('#data').val());
for (let i=0; i<items.length; i++) {
  items[i]['id'] = items[i]['id'].toUpperCase();
  items[i]['color']=chart.colors.getIndex(items[i]['color']);
}

chart.data=items;
// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "value";
pieSeries.dataFields.category = "id";
}

function load_map(){
  am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_dataviz);
// Themes end

/* Create map instance */
var chart = am4core.create("worldmap", am4maps.MapChart);

/* Set map definition */
chart.geodata = am4geodata_worldHigh;

/* Set projection */
chart.projection = new am4maps.projections.Miller();

/* Create map polygon series */
var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

/* Make map load polygon (like country names) data from GeoJSON */
var items = JSON.parse($('#data').val());
for (let i=0; i<items.length; i++) {
  items[i]['id'] = items[i]['id'].toUpperCase();
  items[i]['color']=chart.colors.getIndex(items[i]['color']);
}
polygonSeries.useGeodata = true;
polygonSeries.data = items;

var series1 = chart.series.push(new am4maps.MapPolygonSeries());
series1.name = "EN";
series1.useGeodata = true;
series1.include = ["US", "CA", "UK"];
series1.mapPolygons.template.tooltipText = "{name}";
series1.mapPolygons.template.fill = am4core.color("#96BDC6");
series1.fill = am4core.color("#96BDC6");

/* Configure series */
var polygonTemplate = polygonSeries.mapPolygons.template;
polygonTemplate.applyOnClones = true;
polygonTemplate.togglable = true;
polygonTemplate.tooltipText = "{name}";
polygonTemplate.nonScalingStroke = true;
polygonTemplate.strokeOpacity = 0.5;
polygonTemplate.propertyFields.fill = "color";
var lastSelected;
polygonTemplate.events.on("hit", function(ev) {
  if (lastSelected) {
    // This line serves multiple purposes:
    // 1. Clicking a country twice actually de-activates, the line below
    //    de-activates it in advance, so the toggle then re-activates, making it
    //    appear as if it was never de-activated to begin with.
    // 2. Previously activated countries should be de-activated.
    lastSelected.isActive = false;
  }
  ev.target.series.chart.zoomToMapObject(ev.target);
  if (lastSelected !== ev.target) {
    
    var data = ev.target.dataItem.dataContext;
    var element = document.getElementById('selected');
    
    element.innerHTML = "<h3>" + data.name + " (" + data.id  + ") "+data.value +" 건</h3>";
    /*
    if (data.description) {
        info.innerHTML = data.description;
    }
    else {
        info.innerHTML += "<i>No description provided.</i>"
    }*/
    lastSelected = ev.target;
  }
})


/* Create selected and hover states and set alternative fill color */
var ss = polygonTemplate.states.create("active");
ss.properties.fill = chart.colors.getIndex(0);

var hs = polygonTemplate.states.create("hover");
hs.properties.fill = chart.colors.getIndex(1);

// Hide Antarctica
polygonSeries.exclude = ["AQ"];

// Small map
chart.smallMap = new am4maps.SmallMap();
// Re-position to top right (it defaults to bottom left)
chart.smallMap.align = "right";
chart.smallMap.valign = "top";
chart.smallMap.series.push(polygonSeries);

// Zoom control
chart.zoomControl = new am4maps.ZoomControl();

var homeButton = new am4core.Button();
homeButton.events.on("hit", function(){
  chart.goHome();
});

homeButton.icon = new am4core.Sprite();
homeButton.padding(7, 5, 7, 5);
homeButton.width = 30;
homeButton.icon.path = "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z M16,8";
homeButton.marginBottom = 10;
homeButton.parent = chart.zoomControl;
homeButton.insertBefore(chart.zoomControl.plusButton);


chart.legend = new am4maps.Legend();

// Legend styles
chart.legend.paddingLeft = 27;
chart.legend.paddingRight = 27;
chart.legend.marginBottom = 15;
chart.legend.width = am4core.percent(90);
chart.legend.valign = "bottom";
chart.legend.contentAlign = "left";
chart.legend.itemContainers.template.interactionsEnabled = false;

}); // end am4core.ready()

}


function load_chart(val){
  
am4core.useTheme(am4themes_dataviz);
// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

chart.marginRight = 400;

// Add data
var items = JSON.parse($('#collect').val());

var newitems = new Array(items.length);
for (let i=0; i<items.length; i++) {
  items[i]['date'] = items[i]['date'].slice(2,10);
  items[i]['value']=items[i]['value'];
  newitems[items.length-1-i]=items[i];
}

chart.data = newitems;
//console.log('chart', chart);

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "date";
categoryAxis.title.text = "일별 수집 통계";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;


var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "수집량";

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());

if(val==0){
  series.columns.template.fill = am4core.color("#007bff");
  series.columns.template.stroke = am4core.color("#007bff");
}else if(val==1){
    series.columns.template.fill = am4core.color("#28a745");
  series.columns.template.stroke = am4core.color("#28a745");
}else{
    series.columns.template.fill = am4core.color("#dc3545");
  series.columns.template.stroke = am4core.color("#dc3545");
}
series.columns.template.events.on("hit", function(ev) {
alert(ev.target.dataItem.dataContext['date']);
});
series.dataFields.valueY = "value";
series.dataFields.categoryX = "date";
series.name = "수집량";
series.tooltipText = "{name}: [bold]{valueY}[/]";
series.stacked = true;

// Add cursor
chart.cursor = new am4charts.XYCursor();
}

//클릭 시 stat_table에서 parsing한 정보를 토대로 차트와 테이블을 재구성.
function exec_ajax(val){
  $.ajax({
    url:"<?=SELF?>",
    type:"GET",
    data:'type='+val+'&recall=1',
    success:function(result){
      $('#target').html(result);
      console.log(val);

  load_chart(val);
    },
    error:function(test){
      console.log(test);
    }
  });
}

function tar_load_table(){

}

function tar_load_chart(){

}


</script>