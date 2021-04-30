<?
include "_head.php";
error_reporting(E_ALL);	ini_set("display_errors", 1);

$fq = array(
	'custom' => array(
		'query' => '*:*',
	),
);
$get = "?keyword=".$_GET['keyword'];

if($_GET['lang']){
	if($_GET['lang']=='none'){
		//skip
	}else{
		$fq['custom']['query'].=' AND language:'.$_GET['lang'];
		$get.="&lang=".$_GET['lang'];
	}
}else{

}

$select = array(
  'query'         => "keywords:*".$_GET['keyword'].
          "* OR title:*".$_GET['keyword'].
          "* OR contents:*".$_GET['keyword']."*"
          //." AND item_id:664941 "
          ,
  'start'         => 0,
  'rows'          => 5,
  'fields'        => array('*'),
  'sort'          => array('creationdate' => 'desc'),
  'filterquery' => $fq,
);
$result = $Mem->gps->select($select);
print_r($result->getNumFound());

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
    array_push($data,$small);
  }
}
echo "<input type='hidden' id='data' value='".json_encode($data)."'>";
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


<!-- Chart code -->
<!-- HTML -->
<div id="collect_comp" style="margin-top:20px;"> 
</div>
<div class="c5"  style="background-color:white;">
    <div style="height:520px;" id="chartdiv">
    </div>
</div>
<div style="background-color:#F0F0F0;padding:10px">
  <div id="center_page" style="padding-left:10%;padding-right:10%;">
    <div class="light_shadow" style="margin-top:10px;background-color:white;min-height:100px;">
      금일 수집 통계
    </div>
    <div class="table-responsive light_shadow" id="table frame" style="background-color:white;
    min-height:400px">
        <table class="table table-hover" cellpadding="0" cellspacing="0" border="0"  id="list_table">
        <colgroup>
            <col  style="width:80px;" />
            <col  style="width:90px;" />
            <col  style="width:70px;" />
            <col  style="width:80px;" />
            <col  style="width:150px;" />
            <col   style="width:350px;" />
            <col   style="width:350px;"/>
            <col  style="width:100px;" />
            <col  style="width:100px;" />
            <col  style="width:100px;" />
            <col  style="width:100px;" />
            <col  style="width:100px;" />
        </colgroup>
        <tr>
            <th>순번</th>
            <th>IDX</th>
            <th>국가</th>
            <th>구분</th>
            <th>기관명</th>
            <th>원제목</th>
            <th>한글제목</th>
            <th>페이지수</th>
            <th>첨부파일</th>
            <th>링크</th>
            <th>수집일</th>
            <th>열람수</th>
      </tr>
        <tr id="selected"></tr>
        </table>
    </div>
  </div>
</div>
<button onclick="load_map();">gd</button>

<script>
load_map();

function load_map(){
  am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_dataviz);
// Themes end

/* Create map instance */
var chart = am4core.create("chartdiv", am4maps.MapChart);

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
    
    element.innerHTML = "<h3>" + data.name + " (" + data.id  + ")</h3>";
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

</script>