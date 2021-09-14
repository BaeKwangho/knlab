<?
require_once "../_h.php";

$workers_info = $Mem->q('select idx,user_id,user_company,user_position,user_name from nt_user_list where user_class=8');
$worker = $Mem->q('select a.idx from nt_user_list a left join nt_worker_processed b on a.idx=b.uid where a.idx in (select idx from nt_user_list where user_class=8)');
$processed = array();
foreach($worker as $work){
    if(isset($processed[$work['idx']])){
        $processed[$work['idx']]+=1;
    }else{
        $processed[$work['idx']]=0;
    }
}

?>