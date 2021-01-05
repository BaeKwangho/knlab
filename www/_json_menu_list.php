<?php include "_h.php";
$data=array();


if($Mem->qs("select count(*)  from nt_categorys_auth_list where UID=? and TYPE=1 ",$Mem->uid)){


	$Q=$Mem->q("SELECT * FROM nt_categorys a, nt_categorys_auth_list b WHERE a.TYPE=1 AND LENGTH(a.CODE)=2 AND a.STAT < 9 and b.CID=a.IDX  and b.UID=? order by a.PR asc , CT_NM asc ",$Mem->uid );
	for($i=0; $i < $Q->rowCount();$i++){



			$r=$Q->fetch();
			$data2=array();
			$Q2=$Mem->q("SELECT * FROM nt_categorys a, nt_categorys_auth_list b WHERE a.TYPE=1 AND LENGTH(a.CODE)=4 AND a.STAT < 9  and a.CODE like ? and b.CID=a.IDX  and b.UID=? order by PR asc , CT_NM asc ",array($r["CODE"]."%",$Mem->uid));
			for($j=0; $j < $Q2->rowCount();$j++){
				$r2=$Q2->fetch(); 
				$link=array("href"=>$r2["CODE"]);
				array_push($data2,array("text"=>$r2["CT_NM"],"children"=>"","a_attr"=>$link,"icon"=>"http://dev.com/images/icon_folder2.png"));  
			}
	 
			$link=array("href"=>$r["CODE"]); 
			array_push($data,array("text"=>$r["CT_NM"],"children"=>$data2,"a_attr"=>$link,"icon"=>"http://dev.com/images/icon_folder2.png")); 

	}



}else{

	$Q=$Mem->q("SELECT * FROM nt_categorys WHERE TYPE=1 AND LENGTH(CODE)=2 AND STAT < 9 order by PR asc , CT_NM asc ");
	for($i=0; $i < $Q->rowCount();$i++){



			$r=$Q->fetch();
			$data2=array();
			$Q2=$Mem->q("SELECT * FROM nt_categorys WHERE TYPE=1 AND LENGTH(CODE)=4 AND STAT < 9  and CODE like ? order by PR asc , CT_NM asc ",$r["CODE"]."%");
			for($j=0; $j < $Q2->rowCount();$j++){
				$r2=$Q2->fetch(); 
				$link=array("href"=>$r2["CODE"]);
				array_push($data2,array("text"=>$r2["CT_NM"],"children"=>"","a_attr"=>$link,"icon"=>"http://dev.com/images/icon_folder2.png"));  
			}
	 
			$link=array("href"=>$r["CODE"]); 
			array_push($data,array("text"=>$r["CT_NM"],"children"=>$data2,"a_attr"=>$link,"icon"=>"http://dev.com/images/icon_folder2.png"));


	}

}

echo json_encode($data,true);



?>