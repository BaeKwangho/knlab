<?
require_once "/home/knlab/_Class/_Define.php";
require_once "/home/knlab/_Class/_Lib.php";
require '/home/knlab/www/vendor/autoload.php';

//Elastic Database for Searching with Images
//error_reporting(E_ALL);	ini_set("display_errors", 1);

Class Elastic {
	public $hosts,$EL_server,$client;
	function __construct($default=false){
		$this->$hosts=[
			'index.knlab.kr:9200',
		];
		$this->$client = Elasticsearch\ClientBuilder::create()->setHosts($this->$hosts)->build();
		$this->EL_server="http://1.214.203.131:8088";
	}
	
	/*
	=====================================
	TODO : function parse_params(){  }
	=====================================
	<parameter shapes>
	
	$params=[
		'body' => [
			'query' => [
				'match' => [
					'caption' => 'policy'
				]
			]
		]
	];
	
	============================================================
	TODO : Decide how to branch all actions from search result. 
	e.g) image_processing, display_captions
	============================================================
	
	*/

	public function search($params){
		try{
			$result = $this->$client->search($params);
		}catch(Exception $e){
			echo $e;
		}
		$doc_num = $this->$client->count($params)['count'];
		//$images_path = $this->image_processing($result,$doc_num);

		$docs=array();
		foreach($result['hits']['hits'] as $doc){
			if(isset($doc['_source']['image_path'])){
				array_push($docs,$doc['_source']);
			}
		}
		$obj=[
			'doc_num' => $doc_num,
			'images' => $images_path,
			'result' => $docs,
		];
		/*
			result=[
				list of doc obj =[
					item_id,
					file_name,
					image_path,
					caption,
				]
			]
		*/
		return $obj;
	}

	public function img_search($params){
		try{
			$result = $this->$client->search($params);
		}catch(Exception $e){
			echo $e;
		}
		$doc_num = count($result['hits']['hits']);
		$obj = array();
		$image_route_list = array();
		$cnt_num=0;
		$hash=array();
		//print_r($result['hits']['hits'][0]);
		for($i=0;$i<$doc_num;$i++){
			// if(isset($hash[$result['hits']['hits'][$i]['_source']['item_id']])){
			// 	continue;
			// }else{
			// 	$hash[$result['hits']['hits'][$i]['_source']['item_id']]=1;
			// }
			$doc = $result['hits']['hits'][$i]['_source'];
			$sample = $this->img_path_mod($doc['image_path']);
			if($sample===""){
				continue;
			}else{
				$cnt_num++;
				$doc['image_path']=$sample;
				array_push($image_route_list,$doc);
			}
		}
		/*
			obj=[
				doc_num=img_doc_cnt,
				images=[
					image=[
						item_id,
						file_name,
						image_path,
						caption,
					]
				] 
			]
		*/
		$obj["doc_num"]=$cnt_num;
		$obj["images"]=$image_route_list;
		return $obj;
	}

	private function img_path_mod($sample){
		$route_list = explode("/",$sample);
		$route = "";
		for($j=0;$j<count($route_list);$j++){
			if($route_list[$j]=="mnt"||$route_list[$j]=="storage_1"){
				continue;
			}
			if($j==count($route_list)-1){
				$route=$route.$route_list[$j];
			}else{
				$route=$route.$route_list[$j]."/";
			}
		}
		if($route===""){
			return "";
		}else{
			$cnt_num++;
			$sample = strval($this->EL_server).$route;
		}
		return $sample;
	}
}
?>

