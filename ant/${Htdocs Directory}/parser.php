<?php
	include("includes.php");

	switch ($_GET["query"]) {
	
		case 1 : {
 			echo json_encode(getDamageDone());
			break;
		}
		
		case 2 : { 
			echo json_encode(getDamageTaken()); 
			break;
		}
		
		case 3 : {
			echo json_encode(getHealingDone());
			break;
		}
		
		case 4 : {
			echo json_encode(getHealingReceived());
			break;
		}
		
		case 5 : {
			echo json_encode(getDamageType());
			break;
		}
		
		case 6 : {
			echo json_encode(getAttackType());
			break;
		}
		
		case 7 : {
			echo json_encode(getAvoidance());
			break;
		}
		
		case 8 : {
			echo " ";
			break;
		}
	}
	
	function getUserName($logFile) {
			
		$log_data = getLogData($logFile);
		$user_name = explode("@", $log_data[0]);
		$user_name = explode("]", $user_name[1]);
		return $user_name[0];
	}
	
	function getLogData($logFile) {
			
		$log_data = file($GLOBALS["UPLOAD_PATH"] . $logFile . $_GET["file"]);
		return $log_data;
	}
	
	function getDamageDone() {
		
		$log_data = getLogData();
		$user_name = getUserName(); 
		$user_abilities = array ();
		
		foreach($log_data as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
				
			if ( preg_match("/". $user_name ."/", $log_entry_token[1]) == 1 &&
			     preg_match("/: Damage {/", $log_entry) == 1 &&
			     preg_match("/-miss|-dodge|-parry/", $log_entry) == 0
			)
			{
				$ability_name = explode(" {", $log_entry_token[3]);
				$ability_name = $ability_name[0];
		
				$ability_dmg = explode("(", $log_entry_token[4]);
				$ability_dmg = intval($ability_dmg[1]);
		
				$user_abilities[$ability_name] += $ability_dmg;
			}
		}
		asort($user_abilities);
		return $user_abilities;
	}
	
	function getDamageType() {
	
		$log_data = getLogData();
		$user_name = getUserName();
		$user_dmg = array (); 
	
		foreach($log_data as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/". $user_name ."/", $log_entry_token[1]) == 1 &&
				 preg_match("/: Damage {/", $log_entry) == 1 &&
				preg_match("/-miss|-dodge|-parry/", $log_entry) == 0)
			{		
				if (preg_match("/ internal {/", $log_entry) == 1)
				{
					$ability_dmg = explode("(", $log_entry_token[4]);
					$ability_dmg = intval($ability_dmg[1]);
					$user_dmg_types["Internal"] += $ability_dmg;
				}
				else if (preg_match("/ energy {/", $log_entry) == 1)
				{
					$ability_dmg = explode("(", $log_entry_token[4]);
					$ability_dmg = intval($ability_dmg[1]);
					$user_dmg_types["Energy"] += $ability_dmg;
				}
				else if (preg_match("/ kinetic {/", $log_entry) == 1)
				{
					$ability_dmg = explode("(", $log_entry_token[4]);
					$ability_dmg = intval($ability_dmg[1]);
					$user_dmg_types["Kinetic"] += $ability_dmg;
				}
				else if(preg_match("/ elemental {/", $log_entry) == 1)
				{
					$ability_dmg = explode("(", $log_entry_token[4]);
					$ability_dmg = intval($ability_dmg[1]);
					$user_dmg_types["Elemental"] += $ability_dmg;
				}					
			}
		}
		asort($user_dmg_types);
		return $user_dmg_types;
	}
	
	// TODO Shielding / Immunity
	function getAvoidance() {
	
		$log_data = getLogData();
		$user_name = getUserName();
		$user_att_type = array ();
	
		foreach($log_data as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/". $user_name ."/", $log_entry_token[2]) == 1 &&
				 preg_match("/". $user_name ."/", $log_entry_token[1]) == 0 &&
				 preg_match("/: Damage {/", $log_entry) == 1 )
			{
				if (preg_match("/\* /", $log_entry) == 1)
				{
					$user_att_type["Critical Hit"] += 1;
					
					if (preg_match("/ absorbed {/", $log_entry) == 1)
						$user_att_type["Absorb"] += 1;
				}
				else
				{
					if (preg_match("/-miss/", $log_entry) == 1)
						$user_att_type["Miss"] += 1;
					else if (preg_match("/-dodge/", $log_entry) == 1)
						$user_att_type["Dodge"] += 1;
					else if (preg_match("/-parry/", $log_entry) == 1)
						$user_att_type["Parry"] += 1;
					else 
						$user_att_type["Hit"] += 1;
				}
			}
		}
		asort($user_att_type);
		return $user_att_type;
	}
	
	function getAttackType() {
	
		$log_data = getLogData();
		$user_name = getUserName();
		$user_att_type = array ();
	
		foreach($log_data as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/". $user_name ."/", $log_entry_token[1]) == 1 &&
					preg_match("/: Damage {/", $log_entry) == 1 )
			{
				if (preg_match("/\* /", $log_entry) == 1)
				{
					$user_att_type["Critical Hit"] += 1;
				}
				else
				{
					if (preg_match("/-miss/", $log_entry) == 1)
						$user_att_type["Miss"] += 1;
					else if (preg_match("/-dodge/", $log_entry) == 1)
						$user_att_type["Dodge"] += 1;
					else if (preg_match("/-parry/", $log_entry) == 1)
						$user_att_type["Parry"] += 1;
					else
						$user_att_type["Hit"] += 1;
				}
			}
		}
		asort($user_att_type);
		return $user_att_type;
	}
	
	function getDamageTaken() {
	
		$log_data = getLogData();
		$user_name = getUserName();
		$user_attacker = array ();
	
		foreach($log_data as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
				
			if ( preg_match("/". $user_name ."/", $log_entry_token[2]) == 1 &&
				 preg_match("/: Damage {/", $log_entry) == 1 )
			{	
				$attacker_name = $log_entry_token[1];
				$attacker_name = explode("{", $attacker_name);
				$attacker_name = substr($attacker_name[0], 0, $attacker_name[0].length - 1);
				
				if($attacker_name == "")
					$attacker_name = "Unknown";
				
				$ability_dmg = explode("(", $log_entry_token[4]);
				$ability_dmg = intval($ability_dmg[1]);
				
				$user_attacker[$attacker_name] += $ability_dmg;
			}
		}
		asort($user_attacker);
		return $user_attacker;
	}
	
	function getHealingDone() {
	
		$log_data = getLogData();
		$user_name = getUserName(); 
		$user_abilities = array ();
	
		foreach($log_data as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
				
			if ( preg_match("/". $user_name ."/", $log_entry_token[1]) == 1 &&
			     preg_match("/: Heal {/", $log_entry) == 1 )
			{
				$ability_name = explode(" {", $log_entry_token[3]);
				$ability_name = $ability_name[0];
				
				$ability_dmg = explode("(", $log_entry_token[4]);
				$ability_dmg = intval($ability_dmg[1]);
				
				$user_abilities[$ability_name] += $ability_dmg;
			}
		}
		asort($user_abilities);
		return $user_abilities;
	}
	
	function getHealingReceived() {
	
		$log_data = getLogData();
		$user_name = getUserName();
		$user_healer = array ();
	
		foreach($log_data as $log_entry)
		{
			$arr = explode("] [", $log_entry);
				
			if ( preg_match("/". $user_name ."/", $arr[2]) == 1 &&
			     preg_match("/: Heal {/", $log_entry) == 1
			)
			{
				$log_entry_token = explode("] [", $log_entry);
				$healer_name = substr($log_entry_token[1], 1, $log_entry_token[1].length -1);
				
				$ability_heal = explode("(", $log_entry_token[4]);
				$ability_heal = intval($ability_heal[1]);
				$user_healer[$healer_name] += $ability_heal;
			}
		}
		asort($user_healer);
		return $user_healer;
	}
	
?> 