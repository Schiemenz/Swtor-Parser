<?php

/**
 * Parser Functions for Swtor Parser v2
 * @author Frank Schiemenz
 * 
 * pets count toward the player
 * dps not implemented
 */
////////////////////////////////////////// THE INCLUDES //////////////////////////////////////////

	include("includes.php");
	
////////////////////////////////////////// MAIN SWITCH //////////////////////////////////////////
	
	switch ($_GET["query"]) {
	
		case 1 : {
 			echo json_encode(getHealingDoneByAbility());
			break;
		}
		case 2 : { 
			echo json_encode(getHealingDoneToTarget());
			break;
		}
		case 3 : {
			echo json_encode(getHealingReceivedByAbility());
			break;
		}
		case 4 : {
			echo json_encode(getHealingReceivedBySource());
			break;
		}
		case 5 : {
			echo json_encode(getDamageDoneByAbility());
			break;
		}
		case 6 : {
			echo json_encode(getDamageDoneToTarget());
			break;
		}
		case 7 : {
			echo json_encode(getDamageDoneByType());
			break;
		}
		case 8 : {
			echo json_encode(getAttackTypes());
			break;
		}
		case 9 : {
			echo json_encode(getDamagePerSecond());
			break;
		}
		case 10 : {
			echo json_encode(getAvoidance());
			break;
		}
		case 11 : {
			echo json_encode(getThread());
			break;
		}
		case 12 : {
			echo json_encode(getDamageTaken());
			break;
		}
	}

////////////////////////////////////////// PARSING FUNCTIONS //////////////////////////////////////////
	
	function getDamageDoneByAbility() {
		
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
				
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[1]) &&
			     preg_match("/: Damage {/", $log_entry_token[4]) &&
			    !preg_match("/-miss|-parry/", $log_entry_token[5]) )
			{

				$category_name = explode(" {", $log_entry_token[3]);
				$category_name = $category_name[0];
					
				$ability_dmg = explode("(", $log_entry_token[4]);
				$ability_dmg = intval($ability_dmg[1]);
		
				$user_abilities[$category_name] += $ability_dmg;
			}
		}
		
		asort($user_abilities);
		return $user_abilities;
	}
	
	function getDamageDoneToTarget() {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[1]) &&
					preg_match("/: Damage {/", $log_entry_token[4]) &&
					!preg_match("/-miss|-parry/", $log_entry_token[5]) )
			{
				$category_name = explode(" {", $log_entry_token[2]);
				$category_name = $category_name[0];
					
				$ability_dmg = explode("(", $log_entry_token[4]);
				$ability_dmg = intval($ability_dmg[1]);
	
				$user_abilities[$category_name] += $ability_dmg;
			}
		}
		
		asort($user_abilities);
		return $user_abilities;
	}
	
	function getThread() {
		
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
				
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[1]) &&
			     preg_match("/: Damage {/", $log_entry_token[4]) )
			{
				$ability_name = explode(" {", $log_entry_token[3]);
				$ability_name = $ability_name[0];
		
				$ability_dmg = explode("<", $log_entry_token[4]);
				$ability_dmg = intval($ability_dmg[1]);
		
				$user_abilities[$ability_name] += $ability_dmg;
			}
		}
		
		asort($user_abilities);
		return $user_abilities;
	}
	
	function getDamageDoneByType() {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[1]) &&
				 preg_match("/: Damage {/", $log_entry_token[4]) &&
				!preg_match("/-miss|-parry/", $log_entry_token[5]) )
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
	
	function getAvoidance() {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/" . $GLOBALS["USER_NAME"] . "/", $log_entry_token[2]) &&
				!preg_match("/" . $GLOBALS["USER_NAME"] . "/", $log_entry_token[1]) &&
				 preg_match("/: Damage {/", $log_entry_token[4]) )
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
	
	function getAttackTypes() {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[1]) &&
				 preg_match("/: Damage {/", $log_entry_token[4]) )
			{
				if (preg_match("/\* /", $log_entry) == 1)
				{
					$user_att_type["Critical Hit"] += 1;
				}
				else
				{
					if (preg_match("/-miss/", $log_entry) == 1)
						$user_att_type["Miss"] += 1;
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
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
				
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[2]) &&
				 preg_match("/: Damage {/", $log_entry_token[4]))
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
	
	function getHealingDoneByAbility() {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
				
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[1]) &&
			     preg_match("/: Heal {/", $log_entry_token[4]) )
			{
				$category_name = explode(" {", $log_entry_token[3]);
				$category_name = $category_name[0];

				$ability_dmg = explode("(", $log_entry_token[4]);
				$ability_dmg = intval($ability_dmg[1]);
				
				$user_abilities[$category_name] += $ability_dmg;
			}
		}
		
		asort($user_abilities);
		return $user_abilities;
	}
	
	function getHealingDoneToTarget() {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[1]) &&
					preg_match("/: Heal {/", $log_entry_token[4]) )
			{
				$category_name = $log_entry_token[2];
				
				if(preg_match("/@/", $category_name))
				{
					$category_name = substr($category_name, 1);
				}
				else
				{
					$category_name = explode(" {", $category_name);
					$category_name = $category_name[0];
				}
	
				$ability_dmg = explode("(", $log_entry_token[4]);
				$ability_dmg = intval($ability_dmg[1]);
	
				$user_abilities[$category_name] += $ability_dmg;
			}
		}
		
		asort($user_abilities);
		return $user_abilities;
	}
	
	function getHealingReceivedByAbility() {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
				
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[2]) &&
			     preg_match("/: Heal {/", $log_entry_token[4]) )
			{	
				$ability_name = $log_entry_token[3];
				$ability_name = explode("{", $ability_name);
				$ability_name = $ability_name[0];
				
				$ability_heal = explode("(", $log_entry_token[4]);
				$ability_heal = intval($ability_heal[1]);
				
				$user_healing_received[$ability_name] += $ability_heal;
			}
		}
		
		asort($user_healing_received);
		return $user_healing_received;
	}
	
	function getHealingReceivedBySource() {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
	
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[2]) &&
					preg_match("/: Heal {/", $log_entry_token[4]) )
			{
				$ability_name = substr($log_entry_token[1], 1);
				
				$ability_heal = explode("(", $log_entry_token[4]);
				$ability_heal = intval($ability_heal[1]);
				
				$user_healing_received[$ability_name] += $ability_heal;
			}
		}
		
		asort($user_healing_received);
		return $user_healing_received;
	}
	
	function getDamagePerSecond($sliding_window_size) {
	
		foreach($GLOBALS["LOG_DATA"] as $log_entry)
		{
			$log_entry_token = explode("] [", $log_entry);
			
			if ( preg_match("/". $GLOBALS["USER_NAME"] ."/", $log_entry_token[1]) &&
			     preg_match("/: Damage {/", $log_entry_token[4]) )
			{
				// does nothing yet
			}
		}
		
		return $user_dps;
	}
	
////////////////////////////////////////// HELPER FUNCTIONS //////////////////////////////////////////
	
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
?> 