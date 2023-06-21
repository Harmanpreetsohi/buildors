<?php



/**
 * Facebook conversation class
 */
class FBChat 
{

	public $token='';
	public $from='';
	public $conversations=[];
	public $id;
	public $name;
	
	function __construct($token)
	{
		$this->token = $token;
		$this->me();
		$this->get_chat();
	}

	function __find_in_array($array,$find,&$out){
        $r=[];
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if(count(explode('|', $find)) > 1 && explode('|', $find)[0] === $key){
                    $find = explode('|', $find);
                    unset($find[0]);
                    $find = implode('|', $find);
                }
                if (is_array($value)) {
                    $r[$key] = $this->__find_in_array($value,$find,$out);
                }else {
                    if ($out === '' && $key === $find) {
                        $out = $value;
                    }else{
                        $r[$key] = $value;
                    }
                }
            }
        }
        return $r;
    }

    function find_in_array($array,$find,&$out){
        $temp=[];
        if (is_array($array)) {
            $temp=$this->__find_in_array($array,$find,$out);
        } else {
            return $array;
        }
        return $temp;
    }

	function conversation_from($data,&$from){
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$this->conversation_from($value,$from);
			} elseif($key == 'name') {
				if ($from == '') {
					$from=$value;
				}
			}
		}
	}

	function get_chat(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/me/conversations?access_token='.$this->token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		if (isset($result['data']) && is_array($result['data'])) {
			foreach ($result['data'] as $key => $value) {
				$value['messages'] = $this->get_msgs($value['id']);
				if (isset($value['messages'][0])) {
					$value['messages'][0]['data'] = $this->get_msg_text($value['messages'][0]['id']);
					$from ='';
					$this->conversation_from($value,$from);
					$value['from'] = $from;
				}
				$this->conversations[]= $value;
			}
		}
	}

	function me(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/me?access_token='.$this->token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		if (isset($result['id']) ) {
			$this->id = $result['id'];
			$this->name = $result['name'];
		} 
	}

	function get_msgs($conversation_id){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/'.$conversation_id.'?fields=messages&access_token='.$this->token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		if (isset($result['messages']) && isset($result['messages']['data'])) {
			return $result['messages']['data'];
		} else {
			return [];
		}
	}


	function get_msg_text($msg_id){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/'.$msg_id.'?fields=id,created_time,from,to,message&access_token='.$this->token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		return $result;
		
	}


	function get_all_msgs($conversation_id){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/'.$conversation_id.'?fields=messages&access_token='.$this->token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		$res=[];
		foreach ($this->conversations as $key => $value) {
			if ($value['id'] == $conversation_id) {
				$this->from = $value['from']; 
			}
		}
		if (isset($result['messages']) && isset($result['messages']['data'])) {
			foreach ($result['messages']['data'] as $key => $value) {
				$res[] = $this->get_msg_text($value['id']);
			}
			return $res;
		} else {
			return [];
		}
	}


	public function receive_msg($data){
		$res=[
			'sender'=>'',
			'recipient'=>'',
			'timestamp'=>'',
			'mid'=>'',
			'text'=>'',
			'from_name'=>'',
			'to_name'=>''
			];

		$this->find_in_array($data,'sender|id'    ,$res['sender']);
		$this->find_in_array($data,'recipient|id'    ,$res['recipient']);
		$this->find_in_array($data,'timestamp'    ,$res['timestamp']);
		$this->find_in_array($data,'mid'    ,$res['mid']);
		$this->find_in_array($data,'text'    ,$res['text']);

		$data = $this->get_msg_text($res['mid']);

		$this->find_in_array($data,'from|name'    ,$res['from_name']);
		$this->find_in_array($data,'to|name'    ,$res['to_name']);

		return $res;

	}


	public function get_msg_detail($mid){
		$res=[
			'from_name'=>'',
			'to_name'=>''
			];

		$data = $this->get_msg_text($mid);

		$this->find_in_array($data,'from|name'    ,$res['from_name']);
		$this->find_in_array($data,'to|name'    ,$res['to_name']);

		return $res;

	}



	function send_msg($psid,$msg){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v16.0/me/messages?recipient=%7B'id'%3A'".$psid."'%7D&messaging_type=RESPONSE&message=%7B'text'%3A'".urlencode($msg)."'%7D&access_token=".$this->token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    // echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}

}


