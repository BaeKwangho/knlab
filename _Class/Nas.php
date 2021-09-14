<?
require_once "/home/knlab/_Class/_Define.php";
require_once "/home/knlab/_Class/_Lib.php";
require '/home/knlab/www/vendor/autoload.php';
//error_reporting(E_ALL);	ini_set("display_errors", 1);

Class Nas{
    private $ftpServer,$ftpId,$ftpPass,$ftpConn,$ftpPort,$pdf_route,$thumb_route,$solr_route;
    function __construct(){
		$this->ftpServer="1.214.203.131";
		$this->ftpId="crawl_pdf_gps";
		$this->ftpPass="crawlpdf!23";

		$this->pdf_route="/GPS/pdf/files/archived/";
		$this->thumb_route="/GPS/pdf/files/thumbnail/";
		//$this->solr_route="/mnt/data_nas/pdf/files/thumbnail/";

		$this->ftpPort=22121;
        $this->ftpConn = ftp_connect($this->ftpServer,$this->ftpPort); 
        @ftp_login($this->ftpConn, $this->ftpId, $this->ftpPass);

        ftp_set_option($this->ftpConn, FTP_USEPASVADDRESS, false);
        ftp_pasv($this->ftpConn, true) or die("Unable switch to passive mode");
	}

    public function get_image_from_folder($folder){
        $images = array();
        $route = $this->thumb_route.$folder."/*.png";
        $contents = ftp_nlist($this->ftpConn,$route);
        foreach ($contents as $value) {
            try{
                $img = base64_encode(file_get_contents('ftp://'.$this->ftpId.':'.$this->ftpPass.'@'.$this->ftpServer.':'.$this->ftpPort.$value));
                array_push($images,array("<img width=auto height=300 src='data:image/jpeg;Base64,".$img."'/>",$value));
            }catch(Exception $e){
                continue;
            }
        }
        return $images;
    }

    public function show_image($url){
        $img = base64_encode(file_get_contents('ftp://'.$this->ftpId.':'.$this->ftpPass.'@'.$this->ftpServer.':'.$this->ftpPort.$url));
        return "<img width=auto height=300 src='data:image/jpeg;Base64,".$img."'/>";
    }
}




?>