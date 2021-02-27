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

 <!-- Styles -->
<style>
#chartdiv {
  width: 98%;
  height: 500px;
  overflow: hidden;
  position:absolute;
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

// Create map instance
var chart = am4core.create("chartdiv", am4maps.MapChart);

// Set map definition
chart.geodata = am4geodata_worldLow;

// Set projection
chart.projection = new am4maps.projections.Miller();

// Create map polygon series
var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

// Exclude Antartica
polygonSeries.exclude = ["AQ"];

// Make map load polygon (like country names) data from GeoJSON
polygonSeries.useGeodata = true;

// Configure series
var polygonTemplate = polygonSeries.mapPolygons.template;
polygonTemplate.tooltipText = "{name}";
polygonTemplate.polygon.fillOpacity = 0.6;


// Create hover state and set alternative fill color
var hs = polygonTemplate.states.create("hover");
hs.properties.fill = chart.colors.getIndex(0);

// Add image series
var imageSeries = chart.series.push(new am4maps.MapImageSeries());
imageSeries.mapImages.template.propertyFields.longitude = "longitude";
imageSeries.mapImages.template.propertyFields.latitude = "latitude";
imageSeries.mapImages.template.tooltipText = "{title}";
imageSeries.mapImages.template.propertyFields.url = "url";

var circle = imageSeries.mapImages.template.createChild(am4core.Circle);
circle.radius = 3;
circle.propertyFields.fill = "color";

var circle2 = imageSeries.mapImages.template.createChild(am4core.Circle);
circle2.radius = 3;
circle2.propertyFields.fill = "color";


circle2.events.on("inited", function(event){
  animateBullet(event.target);
})


function animateBullet(circle) {
    var animation = circle.animate([{ property: "scale", from: 1, to: 5 }, { property: "opacity", from: 1, to: 0 }], 1000, am4core.ease.circleOut);
    animation.events.on("animationended", function(event){
      animateBullet(event.target.object);
    })
}

var colorSet = new am4core.ColorSet();

imageSeries.data = [ {
  "title": "London",
  "latitude": 51.5002,
  "longitude": -0.1262,
  "url": "http://www.google.co.uk",
  "color":colorSet.next()
}];



}); // end am4core.ready()
</script>

<!-- HTML -->

<div class="c5 comp_out">
    <div class="row f14 m_bot_10">
        <text>< 금일 수집 현황 ></text>
        <div class="c3">
            <text>ㅇㅎㅇㅎㅇ</text>
            
        </div>
    </div>
    <div id="image frame">
        <div style="height:500px;" id="chartdiv">
        </div>
    </div>
    <div style="inline-block" id="table frame">
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
        </table>
    </div>
</div>


