<?php

	class user{
		public $groupId;
		public $users;
		public $parentId;
		public $active;
		
		public function __construct(){
			$this->users = array();
		}
	}

	class usergroup {

		


		public function test(){
			echo "Hello World!!!";
		}
		public function addUserGroup(){

			$usergroup=new user();
			$post=json_decode(Flight::request()->getBody());
			DuoWorldCommon::mapToObject($post,$usergroup);
			$client = ObjectStoreClient::WithNamespace(DuoWorldCommon::GetHost(),"UserGroup","123");
			$usergroup->active=true;
			$respond=$client->store()->byKeyField("groupId")->andStore($usergroup);
			echo json_encode($respond);

		}
		public function addUserToGroup(){
			$post=json_decode(Flight::request()->getBody());
			$client = ObjectStoreClient::WithNamespace(DuoWorldCommon::GetHost(),"UserGroup","123");
			$respond=$client->get()->byFiltering($post->groupId);
				foreach ($post->users as $user) {
					array_push($respond[0]['users'],$user);
			}
			$Inrespond=$client->store()->byKeyField("groupId")->andStore($respond);
			echo json_encode($Inrespond);
		}

		public function getUserFromGroup($groupId){
			$client = ObjectStoreClient::WithNamespace(DuoWorldCommon::GetHost(),"UserGroup","123");
			$respond=$client->get()->byKey($groupId);
			echo json_encode($respond->users);
			
			
		}

		public function getGroupsFromUser(){
			$post=json_decode(Flight::request()->getBody());
			$client = ObjectStoreClient::WithNamespace(DuoWorldCommon::GetHost(),"UserGroup","123");
			$respond=$client->get()->byFiltering($post->users);
			echo json_encode($respond);
			
		}

		public function removeUserFromGroup(){
			$post=json_decode(Flight::request()->getBody());
			$client = ObjectStoreClient::WithNamespace(DuoWorldCommon::GetHost(),"UserGroup","123");
			$respond=$client->get()->byKey($post->groupId);
    		if(($key = array_search($post->users,$respond->users)) !== false) {
    			unset($respond->users[$key]);
    			$Inrespond=$client->store()->byKeyField("groupId")->andStore($respond);
    			echo json_encode($Inrespond);
			}			
			else{
				echo json_encode("user not  found...");
			}

		}
		public function removeUserGroup($groupId){
			$client = ObjectStoreClient::WithNamespace(DuoWorldCommon::GetHost(),"UserGroup","123");
			$respond=$client->get()->byKey($groupId);
			$respond->active=false;
			$Inrespond=$client->store()->byKeyField("groupId")->andStore($respond);
			echo json_encode($Inrespond);
		
		}
		public function getAllGroups(){
			$client = ObjectStoreClient::WithNamespace(DuoWorldCommon::GetHost(),"UserGroup","123");
			$respond=$client->get()->all();
			echo json_encode($respond);

		}


		function __construct(){
			Flight::route("GET /test", function (){$this->test();});
			Flight::route("POST /addUserGroup", function (){$this->addUserGroup();});
			Flight::route("POST /addUserToGroup", function (){$this->addUserToGroup();});
			Flight::route("GET /getUserFromGroup/@groupId", function ($groupId){$this->getUserFromGroup($groupId);});
			Flight::route("POST /getGroupsFromUser", function (){$this->getGroupsFromUser();});
			Flight::route("POST /removeUserFromGroup", function (){$this->removeUserFromGroup();});
			Flight::route("GET /removeUserGroup/@groupId", function ($groupId){$this->removeUserGroup($groupId);});
			Flight::route("GET /getAllGroups/", function (){$this->getAllGroups();});

		}
	}
?>
