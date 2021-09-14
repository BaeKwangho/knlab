<?
include "components/header.php";
include "backend/worker_processed.php";
error_reporting(E_ALL);	ini_set("display_errors", 1);

?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">
        
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800" style="margin-top:20px">Worker Status</h1>
            <div id="abstract"></div> 
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">일자별 검색</h6>
                </div>
                <div class="card-body">
                </div>
            </div>
                    <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">작업량 확인</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" cellspacing="0">
                        <thead>
                            <tr>
                                <th>순번</th>
                                <th>ID</th>
                                <th>소속</th>
                                <th>직급</th>
                                <th>닉네임</th>
                                <th>작업량</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?$i=1;foreach($workers_info as $r){?>
                                <tr>
                                    <td><?=$i?></td>
                                    <td style="overflow: hidden;text-overflow: ellipsis; white-space: nowrap;max-width:500px">
                                        <?=$r['user_id']?>
                                    </td>
                                    <td><?=$r['user_company']?></td>
                                    <td><?=$r['user_position']?></td>
                                    <td><?=$r['user_name']?></td>
                                    <td><?=$processed[$r['idx']]?></td>
                                </tr>
                            <?$i++;}?>
                        </tbody>
                    </table>
                </div>
            </div>
        <!-- /.container-fluid -->
