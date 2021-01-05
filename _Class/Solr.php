<?
require_once "/home/knlab/_Class/_Define.php";
require_once "/home/knlab/_Class/_Lib.php";
require '/home/knlab/www/vendor/autoload.php';

//error_reporting(E_ALL);	ini_set("display_errors", 1);

Class Solr{
	private $config,$adapter,$eventDispatcher,$client;
	function __construct($default=false){
		$config=[
			'endpoint' => [
				'localhost' => [
					'host' => '1.214.203.131',
					'port' => 8983,
					'path' => '/',
					'core' => 'GPS',
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
}
?>

