<? include "../_Class/Member.php"; $Mem=new Member();  
 $Mem->q("update nt_user_list set WORKING = 0 where IDX =? ",$Mem->user["uid"]);
session_destroy();
mvs("/Member_Login.php");
?>