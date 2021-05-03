/*
function DT_show(date){
	if(date==0){
		return "-";
	}else{
		Date.prototype.ymd=function(){
			var mm = this.getMonth() + 1; // getMonth() is zero-based
  			var dd = this.getDate();

  			return [this.getFullYear(),
          				(mm>9 ? '' : '0') + mm,
          				(dd>9 ? '' : '0') + dd
         			].join('');
		};
		var now = new Date(date*1000);
		nowString = now.ymd();
		if((nowString.substring(4,6)=='01')&&(nowString.substring(6,8)=='01')){
			return nowString.substring(0,4);
		}
		return nowString;
	}
}
*/
function numberCounter(target_frame, target_number) {
  this.count = 0;
  this.diff = 0;
  this.target_count = parseInt(target_number);
  this.target_frame = document.getElementById(target_frame);
  this.timer = null;
  this.counter();
}
numberCounter.prototype.counter = function () {
  var self = this;
  this.diff = this.target_count - this.count;

  if (this.diff > 0) {
    self.count += Math.ceil(this.diff / 5);
  }

  this.target_frame.innerHTML = this.count
    .toString()
    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");

  if (this.count < this.target_count) {
    this.timer = setTimeout(function () {
      self.counter();
    }, 20);
  } else {
    clearTimeout(this.timer);
  }
};

function getup(str_url, item, stat = 0) {
  //		$('#'+item).html('<div><img src=/images/loading.gif ></div>');
  //		$('#'+item).html('<div style="text-align:center;position:absolute; left:10px; bottom:10px; top:40%; right:10px; "><img src=/images/loading01.gif ><br> <span style="font-family:arial;font-size:12px;color:#AAA;">...LOADING...</span></div>');
  $.ajax({
    url: str_url, // 목적지
    type: "GET", // GET형식으로 폼 전송
    timeout: 20000,
    dataType: "text",
    data: null,
    processData: false,
    cache: false,
    contentType: false,
    success: function (data, textStatus, jqXHR) {
      if (stat == 1) {
        alert(data);
      }
      $("#" + item).html(data);
    },
    error: function (xhr, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function postup(str_url, target, item, stat = 0) {
  $.ajax({
    url: str_url,
    type: "POST",
    timeout: 20000,
    dataType: "text",
    data: item,
    cache: false,
    success: function (result, textStatus, jqXHR) {
      if (stat == 1) {
        alert(result);
      }
      $("#" + target).html(result);
    },
    error: function (request, status, error) {
      alert(
        "code:" +
          request.status +
          "\n" +
          "message:" +
          request.responseText +
          "\n" +
          "error:" +
          error
      );
    },
  });
}

function getup_append(str_url, item) {
  //	$('#'+item).html('<div style="text-align:center;position:absolute; left:10px; bottom:10px; top:40%; right:10px; "><img src=/images/loading01.gif ><br> <span style="font-family:arial;font-size:12px;color:#AAA;">...LOADING...</span></div>');
  $.ajax({
    url: str_url, // 목적지
    type: "GET", // POST형식으로 폼 전송
    timeout: 20000,
    dataType: "text",
    data: null,
    processData: false,
    cache: false,
    contentType: false,
    success: function (data, textStatus, jqXHR) {
      $("#" + item).append(data);
    },
    error: function (xhr, textStatus, errorThrown) {
      alert("전송에 실패했습니다.");
    },
  });
}

function setup(str_url) {
  //	alert(item);

  $.ajax({
    url: str_url, // 목적지
    type: "GET", // POST형식으로 폼 전송
    timeout: 20000,
    dataType: "text",
    data: null,
    processData: false,
    cache: false,
    contentType: false,
    success: function (data, textStatus, jqXHR) {
      return true;
    },
    error: function (xhr, textStatus, errorThrown) {
      alert("전송에 실패했습니다.");
    },
  });
}

function setups(str_url, fn) {
  //	alert(item);

  $.ajax({
    url: str_url, // 목적지
    type: "GET", // POST형식으로 폼 전송
    timeout: 20000,
    dataType: "text",
    data: null,
    processData: false,
    cache: false,
    contentType: false,
    success: function (data, textStatus, jqXHR) {
      var obj = jQuery.parseJSON(data);
      if (obj.status == "true") {
        fn();
      }
    },
    error: function (xhr, textStatus, errorThrown) {
      alert("전송에 실패했습니다.");
    },
  });
}

function urlen(str) {
  return encodeURIComponent(str);
}

function urlde(str) {
  return decodeURIComponent(str);
}

function checks(id) {
  if (document.getElementById(id).checked) {
    document.getElementById(id).checked = false;
  } else {
    document.getElementById(id).checked = true;
  }
}

function go(url) {
  location.href = url;
}
function gos(urls, parameter) {
  getup(url, "main_content");
  var datas = new FormData();
  datas.append("cid", $("#cid").val());

  $.ajax({
    url: urls, // 목적지
    type: "POST", // POST형식으로 폼 전송
    timeout: 20000,
    dataType: "text",
    data: datas,
    processData: false,
    cache: false,
    contentType: false,
    success: function (data, textStatus, jqXHR) {
      getup(urls, "main_content");
    },
    error: function (xhr, textStatus, errorThrown) {
      alert("페이지 연결에 실패했습니다.");
    },
  });
}

var form_data = new FormData();

function send_post(target_url, func) {
  $.ajax({
    url: target_url, // 목적지
    type: "POST", // POST형식으로 폼 전송
    timeout: 10000,
    dataType: "text",
    data: form_data,
    processData: false,
    contentType: false,
    success: function (data, textStatus, jqXHR) {
      func();
    },
    error: function (xhr, textStatus, errorThrown) {
      alert("전송에 실패했습니다.");
    },
  });
}

function getId(id) {
  return document.getElementById(id);
}

function getCode() {
  var tempPassword = "";

  for (var i = 0; i < 10; i++) {
    var rndVal = parseInt(Number(Math.random() * 62));
    if (rndVal < 10) {
      tempPassword += rndVal;
    } else if (rndVal > 35) {
      tempPassword += String.fromCharCode(rndVal + 61);
    } else {
      tempPassword += String.fromCharCode(rndVal + 55);
    }
  }

  return tempPassword.toUpperCase();
}

function msg(msg) {
  alert(msg);
}

function Dialog(url, width, height) {
  getup(url, "web_dialog");

  $("#web_dialog").css({
    width: width + "px",
    height: height + "px",
    "margin-left": "-" + width / 2 + "px",
    "margin-top": "-" + height / 2 + "px",
  });

  $("#overlay").show();
  $("#web_dialog").show();
  // 	$("#web_dialog").fadeIn(300);
  $("#overlay").unbind("click");
}

function Dialog2(url, width, height) {
  getup(url, "web_dialog1");

  $("#web_dialog1").css({
    width: width + "px",
    height: height + "px",
    "margin-left": "-" + width / 2 + "px",
    "margin-top": "-" + height / 2 + "px",
  });

  $("#overlay1").show();
  $("#web_dialog1").show();
  // 	$("#web_dialog").fadeIn(300);
  $("#overlay").unbind("click");
}

function DialogShowCustom(url, width, height) {
  getup(url, "web_dialog");

  $("#web_dialog").css({
    width: width + "px",
    height: height + "px",
    "margin-left": "-" + width / 2 + "px",
    "margin-top": "-" + height / 2 + "px",
  });

  $("#overlay").show();
  //	$("#web_dialog").fadeIn(300);
  $("#overlay").unbind("click");
}

function DialogShow(url) {
  getup(url, "web_dialog");

  $("#overlay").show();
  $("#web_dialog").fadeIn(300);
  $("#overlay").unbind("click");
}

function DialogShow2(url) {
  getup(url, "web_dialog2");
  $("#overlay").show();
  $("#web_dialog2").fadeIn(300);
  $("#overlay").unbind("click");
}

function DialogShow3(url) {
  getup(url, "web_dialog3");
  $("#overlay").show();
  $("#web_dialog3").fadeIn(300);
  $("#overlay").unbind("click");
}

function DialogShow4(url) {
  getup(url, "web_dialog4");
  $("#overlay").show();
  $("#web_dialog4").fadeIn(300);
  $("#overlay").unbind("click");
}

function DialogShow5(url) {
  getup(url, "web_dialog5");
  $("#overlay").show();
  $("#web_dialog5").fadeIn(300);
  $("#overlay").unbind("click");
}

function DialogHide(id) {
  $("#overlay").fadeOut();
  $("#" + id).hide();
}

function DialogHide2(id) {
  $("#" + id).hide();
}

function DialogX() {
  $("#overlay").fadeOut();
  $("#web_dialog").hide();
  $("#web_dialog2").hide();
  $("#web_dialog3").hide();
  $("#web_dialog4").hide();
  $("#web_dialog5").hide();
}

function DialogHides() {
  $("#overlay").fadeOut();
  $("#web_dialog").hide();
  $("#web_dialog2").hide();
  $("#web_dialog3").hide();
  $("#web_dialog4").hide();
  $("#web_dialog5").hide();
}

function DialogClose2() {
  $("#overlay1").fadeOut();
  $("#web_dialog1").hide();
}

/********* Category ***********/
