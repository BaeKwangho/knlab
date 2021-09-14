<?//error_reporting(E_ALL);	ini_set("display_errors", 1);
require_once "../../_h.php";
if($_POST['host_id']){
    $host = $Mem->poli->qs('select host from crawler_host where host_id='.$_POST['host_id']);
    $result = $Mem->poli->q('select * from crawler_log where host="'.$host.'"');

?><td colspan="6" style="width:inherit">
<div class="row" style=" flex-wrap: nowrap;
  overflow-x: auto;width:100em"><?
foreach($result as $r){?>
<div class="card border-left-primary h-100 py-2" style="flex: 0 0 auto; margin: 10px 0 10px 10px">
<div class="card-body">
<div class="row no-gutters align-items-center">
    <div class="col">
        <div class="h6">[log_id] <?=$r['log_id']?></div>
        <div class="h6">[시작일] <?=$r['start_time']?></div>
        <div class="h6">[종료일] <?=$r['end_time']?></div>
        <div class="h6">[url] <?=$r['url']?></div>
        <div class="h6">[html] <?=$r['html']?></div>
        <div class="h6">[pdf] <?=$r['pdf']?></div>
        <div class="h6">[word] <?=$r['word']?></div>
        <div class="h6">[excel] <?=$r['excel']?></div>
        <div class="h6">[ppt] <?=$r['ppt']?></div>
</div>
</div>
</div>
</div>

<?}?>
</div>
</td><?
}else{
    $values = $Mem->poli->q('select * from crawler_log');
    $url=$html=$pdf=$word=$excel=$ppt=$etc = 0;
    foreach($values as $r){
        $url+=$r['url'];
        $html+=$r['html'];
        $pdf+=$r['pdf'];
        $word+=$r['word'];
        $excel+=$r['excel'];
        $ppt+=$r['ppt'];
        $etc+=$r['etc'];
    }
    $log_result=array(
        'url'=>$url,
        'html'=>$html,
        'pdf'=>$pdf,
        'word'=>$word,
        'excel'=>$excel,
        'ppt'=>$ppt,
        'etc'=>$etc,
    );
    $colors=array(
        'border-left-primary',
        'border-left-success',
        'border-left-info',
        'border-left-warning',
        'border-left-danger',
        'border-left-secondary',
        'border-left-dark',
    );
    $log_keys=array_keys($log_result);
?>
    <div class="row">
<?for($i=0;$i<count($log_result);$i++){?>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card <?=$colors[$i]?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <?=$log_keys[$i]?></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$log_result[$log_keys[$i]]?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?}?>
</div>
<?
}

?>