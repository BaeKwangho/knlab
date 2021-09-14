<?
require_once "/home/knlab/_Class/_Define.php";
require_once "/home/knlab/_Class/_Lib.php";
require '/home/knlab/www/vendor/autoload.php';

//Elastic Database for Searching with Images
//error_reporting(E_ALL);	ini_set("display_errors", 1);

Class Elastic {
	private $hosts,$EL_server,$client;
	function __construct($default=false){
		$this->$hosts=[
			'index.knlab.kr:9200',
		];
		$this->$client = Elasticsearch\ClientBuilder::create()->setHosts($this->$hosts)->build();
		$this->EL_server="http://1.214.203.131:8088";
	}
	
	/*
	하... 따로 정의하지 않고 시작해서 search 로직만 4개나 됨..(다 다른페이지에서 쓰이는거)
	근데 귀찮으니 일단 두겟음. 


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



	

	private function result_process($result , $count=null,$using_hash=False){
		$docs=array();
		if($using_hash){
			$hash = $_SESSION["hash"];
		}
		foreach($result['hits']['hits'] as $doc){
			if($using_hash){
				if(in_array($doc['_source']['title'][0],array_keys($hash))){
					continue;
				}else{
					$hash[$doc['_source']['title'][0]]=1;
				}
			}
			if(isset($doc['_source']['image_path'])){
				$sample = $this->img_path_mod($doc['_source']['image_path']);
				if($sample===""){
					continue;
				}else{
					$doc['_source']['image_path']=$sample;
				}
				array_push($docs,$doc['_source']);
			}
			
		}
		$obj=[
			'doc_num' => $count,
			'images' => $images_path,
			'result' => $docs,
		];
		if(isset($result['_scroll_id'])){
			$obj['scroll_id'] = $result['_scroll_id'];
		}
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
		if($using_hash){
			$_SESSION["hash"] = $hash;
		}
		return $obj;
	}

	public function simple_search($params){
		try{
			$result = $this->$client->search($params);
		}catch(Exception $e){
			echo $e;
		}
		return $result;
	}
	
	public function index($params){
		try{
			$result = $this->$client->index($params);
		}catch(Exception $e){
			echo $e;
		}
		return $result;
	}

	public function document_search($params){
		try{
			$result = $this->$client->search($params);
		}catch(Exception $e){
			echo $e;
		}
		
		$obj = $this->nomal_proc($result);
		return $obj;
	}

	public function search($params){
		try{
			$result = $this->$client->search($params);
		}catch(Exception $e){
			echo $e;
		}
		$doc_num = $this->$client->count($params)['count'];
		//$images_path = $this->image_processing($result,$doc_num);
		$obj = $this->result_process($result,$doc_num);
		return $obj;
	}

	public function img_search($params){
		try{
			$result = $this->$client->search($params);
		}catch(Exception $e){
			echo $e;
		}
		$doc_num = $result['hits']['total']['value'];
		$obj = array();
		$image_route_list = array();
		$hash=array();
		//print_r($result['hits']['hits'][0]);
		for($i=0;$i<$doc_num;$i++){
			
			// if(isset($hash[$result['hits']['hits'][$i]['_source']['item_id']])){
			// 	continue;
			// }else{
			// 	$hash[$result['hits']['hits'][$i]['_source']['item_id']]=1;
			// }
			$doc = $result['hits']['hits'][$i]['_source'];
			if(in_array($doc['title'][0],array_keys($hash))){
				continue;
			}else{
				$hash[$doc['title'][0]]=1;
			}
			$sample = $this->img_path_mod($doc['image_path']);
			if($sample===""){
				continue;
			}else{
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
		$obj["doc_num"]=$doc_num;
		$obj["images"]=$image_route_list;
		if(isset($result["_scroll_id"])){
			$obj["scroll_id"]=$result["_scroll_id"];
		}
		return $obj;
	}

	public function page_test($scroll_id=null,$init_param=null){
		if(isset($init_param)){
			return $this->$client->search($init_param);
		}else{
			$result = $this->$client->scroll([
				'body' => [
					'scroll_id' => $scroll_id,  //...using our previously obtained _scroll_id
					'scroll'    => '30s'        // and the same timeout window
				]
			]);
			return $this->result_process($result,0,true);
		}
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

	public function nomal_proc($result){
		$docs = array();
		$doc_num = $result['hits']['total']['value'];
		foreach($result['hits']['hits'] as $doc){
			if(isset($doc['_source'])){
				$doc['_source']['_id']=$doc['_id'];
				array_push($docs,$doc['_source']);
			}
		}
		$obj=[
			'doc_num' => $count,
			'result' => $docs,
		];
		return $obj;
	}

	public function delete($params){
		try{
			$result = $this->$client->delete($params);
		}catch(Exception $e){
			echo $e;
		}
		return $result;
	}
	public function get($params){
		try{
			$result = $this->$client->get($params);
		}catch(Exception $e){
			echo $e;
		}
		$obj = $result['_source'];
		$obj['_id']=$result['_id'];
		return $obj;
	}

	public function update($params){
		try{
			$result = $this->$client->update($params);
		}catch(Exception $e){
			echo $e;
		}
		return $result;
	}

}
?>

