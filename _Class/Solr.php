<?
require_once "/home/knlab/_Class/_Define.php";
require_once "/home/knlab/_Class/_Lib.php";
require '/home/knlab/www/vendor/autoload.php';

//error_reporting(E_ALL);	ini_set("display_errors", 1);

Class Solr{
	private $config,$adapter,$eventDispatcher,$client;
	function __construct($default=false){
		if($default!==false){
			$core = 'DOCS';
		}else{
			$core = 'GPS';
		}
		$config=[
			'endpoint' => [
				'localhost' => [
					'host' => '1.214.203.131',
					'port' => 8983,
					'path' => '/',
					'core' => $core,
					// For Solr Cloud you need to provide a collection instead of core:
					// 'collection' => 'techproducts',
				]
			]
		];
		$adapter = new Solarium\Core\Client\Adapter\Curl(); // or any other adapter implementing AdapterInterface
		$eventDispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
		$this->$client = new Solarium\Client($adapter,$eventDispatcher,$config);
	}

	public function search($string){
		$query = $this->$client->createSelect();
		$query->setQuery($string);
		try{
			$result = $this->$client->select($query);
			$doc_res = array();
			foreach($result as $list){
				$doc = array();
				foreach($list as $key => $value){
					$doc[$key] = $value;
				}
				//can edit one document in result set
				$doc['url']=$this->url_path_mod($doc['url'][0]);

				if(!isset($doc['title'])){$doc['title']="NO TITLE";}
				else{$doc['title']=$doc['title'][0];}

				array_push($doc_res,$doc);
			}
		}catch(Exception $e){
			return $e;
		}
		$obj = [
			'query' => $query,
			'result' => $doc_res,
		];
		/*
		doc_res = [
			array(
				item_id,
				job_id,
				...,
				host = array(),
				url = array(),
				...
			)
		]
		
		*/
		return $obj;
	}

	private function url_path_mod($url){
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    	$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
		return str_replace($entities, $replacements,$url);
	}

	public function select($query){
		$query = $this->$client->createSelect($query);
		$resultset = $this->$client->select($query);

		return $resultset;
	}

	public function delete($query){
		$update = $this->$client->createUpdate();
		$update->addDeleteQuery($query);
		$update->addCommit();

		$result= $this->$client->update($update);

		return $result->getStatus();
	}


	public function update($data){
		$update = $this->$client->createUpdate();
		$doc = $update->createDocument();

		if($data['DC_TITLE_OR']){$doc->DC_TITLE_OR = $data['DC_TITLE_OR'];}
		if($data['DC_TITLE_KR']){$doc->DC_TITLE_KR = $data['DC_TITLE_KR'];}
		if($data['DC_CODE']){
			foreach($data['DC_CODE'] as $code){$code = strval($code);}
			$doc->DC_CODE = $data['DC_CODE'];
		}
		if($data['DC_COUNTRY']){$doc->DC_COUNTRY = $data['DC_COUNTRY'];}
		if($data['DC_CONTENT']){$doc->DC_CONTENT = $data['DC_CONTENT'];}
		if($data['DC_DT_COLLECT']){$doc->DC_DT_COLLECT = $data['DC_DT_COLLECT'];}
		if($data['DC_DT_WRITE']){$doc->DC_DT_WRITE = $data['DC_DT_WRITE'];}
		if($data['DC_URL_LOC']){$doc->DC_URL_LOC = $data['DC_URL_LOC'];}
		if($data['DC_AGENCY']){$doc->DC_AGENCY = $data['DC_AGENCY'];}
		if($data['DC_PAGE']){$doc->DC_PAGE = $data['DC_PAGE'];}
		if($data['DC_TYPE']){$doc->DC_TYPE = $data['DC_TYPE'];}
		if($data['DC_KEYWORD']){$doc->DC_KEYWORD = $data['DC_KEYWORD'];}
		if($data['STAT']){$doc->STAT = $data['STAT'];}
		if($data['DC_CAT']){$doc->DC_CAT = $data['DC_CAT'];}

		$update->addDocument($doc);
		$update->addCommit();

		$result = $this->$client->update($update);

		return $result;
	}
}
?>

