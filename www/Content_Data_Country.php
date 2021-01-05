<?
include "_h.php";
?>
<form>
    <div>
        <div class="label_view" style="width:600px">
            <div style="display:none;" id="conti"></div>
            <?
            $cont=$Mem->q("select distinct(CONTI_NAME) from nt_continents");
            while($conti=$cont->fetch()){
                $con=$Mem->q("select a.* from nt_countrys a join nt_continents b on a.IDX = b.CTYID where b.CONTI_NAME=? and a.CTY_PRI >1",$conti["CONTI_NAME"]);
            ?>
                <div id="tr">
                    <div id="conti">
                        <p><?=$conti["CONTI_NAME"]?></p>
                    </div>
                    <div id="country">
                        <?while($r=$con->fetch()){?>
                            <label>
                                <input type="checkbox" name="DC_COUNTRY[]" value="<?=$r["CTY_NM"]?>"></input>
                                <?=$r["CTY_NM"]?>
                            </label>
                        <?}?>
                    </div>  
                    <div id="more" style="cursor:pointer;" onclick="get_conti($(this).children('text').children('input'),'<?=$conti['CONTI_NAME']?>');">
                        <text >
                            <input type="hidden" name="CONTI_MORE" value="<?=$conti["CONTI_NAME"]?>">
                            <p id="CONTI_MORE<?=$conti['CONTI_NAME']?>">더보기</p>
                        </text>
                    </div>
                </div>
            <?}?>
        </div>
    </div>
    <div style="display:none;" id="test">
    </div>
    <div style="position:absolute;left:0px;top:730px;width:200px">
        <input type="button" class="buttonb" onclick="DialogHides();" value="창닫기">
        <input type="button" class="buttonb" onclick="ret_country();" value="저장">
    </div>

<script>
    function get_conti($tag,$conti){
        
        $conti_bef=$('#conti').text();
        console.log($tag);
        console.log($conti_bef);
        console.log($conti);
        if($conti==$conti_bef){
            $('#test').hide();
            $('#conti').text('');
            $('#web_dialog').css({
                "width":"600px",
                "margin-left":"-300px"
                });
            $('#CONTI_MORE'+$conti).text('더보기');
        }else{
            $.ajax({
                url:'components/list_country.php',
                type:'get',
                data:$tag,
                success:function(result){
                    $('#test').html(result);
                    $('#web_dialog').css({
                        "width":"800px",
                        "margin-left":"-400px"
                        });
                    $('#conti').text($conti);
                    if($conti_bef==''){
                        $('#test').show();
                        $('#CONTI_MORE'+$conti).text('닫기');
                    }else{
                        $('#CONTI_MORE'+$conti_bef).text('더보기');
                        $('#CONTI_MORE'+$conti).text('닫기');
                    }
                },
                error: function(request,status,error) {
			        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		        }
            })
        }
    }

    function ret_country(){
        $.ajax({
            url:'components/selection.php',
            type:'post',
            data:$('form').serialize(),
            success:function(result){
                $('#country_select_list').html(result);
            },
            error:function(xhr,textStatus,errorThrown){
                alert(xhr);
            }
        })
        DialogHides();
    }
</script>
