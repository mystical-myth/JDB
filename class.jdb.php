<?php
	if(!file_exists("class.encryption.php"))
		die();
	require_once "class.encryption.php";
	class JDB
	{
		public $encrypted = false;
		public $encryptKey = "";
		
		private $dbname = "";
		private $instanceName = "";
		
		public $currArr = array();
		private $exceptions = false;
		
		public function __construct($dbname, $encrypted = false, $encryptKey = "")
		{
			$this->dbname = $dbname;
			$this->encrypted = $encrypted;
			$this->encryptKey = $encryptKey;
			$this->init();
		}
		
		public function init()
		{
			$instanceName = "jdb_" . $this->dbname . ".json";
			$this->instanceName = $instanceName;
			
			if(!file_exists($instanceName))
				file_put_contents($instanceName, "");
			else
				$this->load();
		}
		
		public function load()
		{
			if(file_exists($this->instanceName))
			{
				$content = "";
				if($this->encrypted)
					$content = Encryption::mDecrypt(file_get_contents($this->instanceName), $this->encryptKey);
				else
					$content = file_get_contents($this->instanceName);
				
				$this->currArr = json_decode($content, true);
			}
		}
		
		public function push($value)
		{
			array_push($this->currArr, $value);
			$this->invalidate();
		}
		
		public function insert($key, $value)
		{
			$this->currArr[$key] = $value;
			$this->invalidate();
		}
		
		public function createGroup($name)
		{
			$this->currArr[$name] = array();
			$this->invalidate();
		}
		
		public function insertInto($groupName, $key, $value)
		{
			$this->currArr[$groupName][$key] = $value;
			$this->invalidate();
		}
		
		public function deleteInto($groupName, $key)
		{
			if(array_key_exists($groupName, $this->currArr) && array_key_exists($key, $this->currArr[$groupName]))
				unset($this->currArr[$groupName][$key]);
			else
				if($this->exceptions) throw new Exception("Cannot find element '{$key}' in group '{$groupName}'");
		}
		
		public function delete($key)
		{
			if(array_key_exists($key, $this->currArr))
				unset($this->currArr[$key]);
			else
				if($this->exceptions) throw new Exception("Cannot find element '{$key}'");
				
			$this->invalidate();
		}
		
		public function selectAll()
		{
			return $this->currArr;
		}
		
		public function select($name)
		{
			if(array_key_exists($name, $this->currArr))
				return $this->currArr[$name];
			else
				if($this->exceptions) throw new Exception("Cannot find key '{$name}'");
		}
		
		public function selectInto($group, $name)
		{
			if(array_key_exists($group, $this->currArr))
				if(array_key_exists($name, $this->currArr[$group]))
					return $this->currArr[$group][$name];
				else
					if($this->exceptions) throw new Exception("Cannot find key '{$name}' in group '{$group}'");
			else
				if($this->exceptions) throw new Exception("Cannot find group '{$group}'");
		}
		
		public function selectDiv($div)
		{
			$str = "";
			
			foreach($this->currArr as $k => $v)
				$str .= "{$v}{$div}";
			
			return $str;
		}
		
		public function invalidate()
		{
			if($this->encrypted)
				$this->encryptInvalidate();
			else
				file_put_contents($this->instanceName, json_encode($this->currArr));
		}
		
		public function encryptInvalidate()
		{
			$finalString = Encryption::mEncrypt(json_encode($this->currArr), $this->encryptKey);
			file_put_contents($this->instanceName, $finalString);
		}
	}
?>