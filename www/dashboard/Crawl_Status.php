<?

//error_reporting(E_ALL);	ini_set("display_errors", 1);
include "components/header.php";
include "backend/crawler_host.php";

?>
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">
        
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800" style="margin-top:20px">Crawler Status</h1>
            <div id="abstract"></div> 
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">작업 목록</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>job_id</th>
                                        <th>host</th>
                                        <th>worked_count</th>
                                        <th>worked_at</th>
                                        <th>schedule_at</th>
                                        <th>created_at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?foreach($result as $r){?>
                                        <tr style="cursor:pointer;" onClick="get_log($(this),'<?=$r['host_id']?>')">
                                            <input type="hidden" name="host_id" id="host_id" value="<?=$r['host_id']?>"/>
                                            <td><?=$r['job_id']?></td>
                                            <td style="overflow: hidden;text-overflow: ellipsis; white-space: nowrap;max-width:500px">
                                                <?=$r['host']?>
                                            </td>
                                            <td><?=$r['worked_count']?></td>
                                            <td><?=$r['worked_at']?></td>
                                            <td><?=$r['schedule_at']?></td>
                                            <td><?=$r['created_at']?></td>
                                        </tr>
                                        <tr id="<?=$r['host_id']?>">
                                        </tr>
                                    <?}?>
                                </tbody>
                            </table>
                        </div>
                <!-- /.container-fluid -->

<script>
    $.ajax({
        url:'backend/crawler_log.php',
        type: 'POST',
        success: function(result, textStatus, xhr){
            console.log(result);
            $('#abstract').html(result);
        },error: function(xhr, textStatus, errorThrown) {
            console.log(xhr,textStatus,errorThrown); 
        }
    }); 
    function get_log(component,host_id){
        if(component.hasClass('show')){
            component.removeClass('show');
            $('#'+host_id).html('');
        }else{
            component.addClass('show');
            $.ajax({
                url:'backend/crawler_log.php',
                type: 'POST',
                data:{
                    'host_id':host_id
                },
                success: function(result, textStatus, xhr){
                    console.log(result);
                    $('#'+host_id).html(result);
                },error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr,textStatus,errorThrown); 
                }
            }); 
        }
    }
</script>