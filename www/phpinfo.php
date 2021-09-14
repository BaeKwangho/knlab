
<?
error_reporting(E_ALL);	ini_set("display_errors", 1);

include "_h.php";


/*
$connection = ssh2_connect($host,8322);
ssh2_auth_password($connection,$sshuser,$sshpass);
$stream = ssh2_exec($connection, 'mysql -u root -p company!23 politica');
$result = ssh2_exec($connection, 'update collected_item set submit_status=1 where collect_id=1');
*/

$sshuser="knlab";
$sshpass="Knlab1!8*3#";
$host="1.214.203.131";

shell_exec("ssh -p 8322 -o StrictHostKeyChecking=no -fNg -L 3307:127.0.0.1:3306 ".$sshuser."@".$host.";".$sshpass);

$DB=new PDO("mysql:host=127.0.0.1;port=3307;dbname=politica","root","Company!23");
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);//추가

print_r($Mem->poli->qr('select * from collected_item'));

$item_id = '207';

$dir = $Mem->proc->thumbnail('item_id:'.$item_id);

if (is_dir($dir)){
    if ($dh = opendir($dir)){                     
        while (($file = readdir($dh)) !== false){   
          echo "filename:" . $file . "<br>";        
        }                                           
        closedir($dh);                              
      }                  
}
$ftpServer ="1.214.203.131";  //host
$ftpId  ="crawl_pdf_gps";  //user
$ftpPass ="crawlpdf!23"; //pass
$ftpConn = ftp_connect($ftpServer,22121); 
$ftpLogin = @ftp_login($ftpConn, $ftpId, $ftpPass); 
ftp_set_option($ftpConn, FTP_USEPASVADDRESS, false);
ftp_pasv($ftpConn, true) or die("Unable switch to passive mode");
// 여기까지 로그인

ftp_chdir($ftpConn, '/GPS/pdf/files/thumbnail/2021/9/www.cbinsights.com/jobsohio');
$contents = ftp_nlist($ftpConn,"/GPS/pdf/files/thumbnail/2021/9/www.cbinsights.com/jobsohio/*.png");
foreach ($contents as $value) {
    
    $img = base64_encode(file_get_contents('ftp://crawl_pdf_gps:crawlpdf!23@1.214.203.131:22121'.$value));
    echo "<img width=500 heigth=auto src='data:image/jpeg;Base64,".$img."'/>";
  }

ftp_close($ftpConn);



?>
