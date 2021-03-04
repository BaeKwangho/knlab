<?
include "_head.php";
?>
<script>
//_head의 body부분에 걸려있는 이상한 왼쪽 메뉴바 background 제거
$("body").css("background","white");
</script>
<?
/*
if($_GET["WORD"]){
    mvs("Content_view.php?WORD=".$_GET["WORD"]);
}*/

?>
<style>
#chartdiv {
  width: 98%;
  height: 500px;
}

</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

/* Create map instance */
var chart = am4core.create("chartdiv", am4maps.MapChart);

/* Set map definition */
chart.geodata = am4geodata_worldLow;

/* Set projection */
chart.projection = new am4maps.projections.Miller();

/* Create map polygon series */
var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

/* Make map load polygon (like country names) data from GeoJSON */
polygonSeries.useGeodata = true;

/* Configure series */
var polygonTemplate = polygonSeries.mapPolygons.template;
polygonTemplate.applyOnClones = true;
polygonTemplate.togglable = true;
polygonTemplate.tooltipText = "{name}";
polygonTemplate.nonScalingStroke = true;
polygonTemplate.strokeOpacity = 0.5;
polygonTemplate.fill = chart.colors.getIndex(0);
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
ss.properties.fill = chart.colors.getIndex(2);

var hs = polygonTemplate.states.create("hover");
hs.properties.fill = chart.colors.getIndex(4);

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

}); // end am4core.ready()
</script>
<!-- HTML -->

<div class="c5 comp_out" style="height:600px">
    <div class="row f14 m_bot_10">
        <text>< 금일 수집 현황 ></text>
        <div class="c3" id="collect data">
            <text>ㅇㅎㅇㅎㅇ</text>
            
        </div>
    </div>
    <div style="height:520px;" id="chartdiv">
    </div>
</div>
    <div class="c4 div_center" id="table frame">
        <table class="table_info" cellpadding="0" cellspacing="0" border="0"  id="list_table">
        <colgroup>
            <col  style="width:80px;" />
            <col  style="width:90px;" />
            <col  style="width:70px;" />
            <col  style="width:80px;" />
            <col  style="width:150px;" />
            <col   style="width:350px;" />
            <col   />
            <col  style="width:70px;" />
            <col  style="width:70px;" />
            <col  style="width:60px;" />
            <col  style="width:80px;" />
            <col  style="width:70px;" />
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



