<?php

/**
 * Input filtering and validation class
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, Version 3
 * @author Richard Pijnenburg <richard@ispavailability.com>
 * @version 1.0
 */

class Input {

	private $string;

	/*
	 * __construct function standard filter some unallowed stuff in _GET, _POST, _SESSION, _SERVER, _ENV and _COOKIE variables.
	 * @access public
	 */
	public function __construct() {
		
		$vars = array("_GET","_POST","_SESSION","_SERVER","_ENV","_COOKIE");
		for($i=0; $i < count($vars); $i++) {
			$var = $vars[$i];
			if(isset($$var) && !empty($$var) && is_array($$var)) {
				$$var = MainFrame::Init("input__filter")->process($$var);
			}
		}
		
	}
	
	public function __destruct() { }

	/*
	 * Check if a string is an ipv4 or ipv6 address
	 * @access private
	 * @param string $string String to check
	 */
	private function is_ipnum($string) {
		// IPV6 pattern
	        $pattern1 = '([A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}';
        	$pattern2 = '[A-Fa-f0-9]{1,4}::([A-Fa-f0-9]{1,4}:){0,5}[A-Fa-f0-9]{1,4}';
	        $pattern3 = '([A-Fa-f0-9]{1,4}:){2}:([A-Fa-f0-9]{1,4}:){0,4}[A-Fa-f0-9]{1,4}';
        	$pattern4 = '([A-Fa-f0-9]{1,4}:){3}:([A-Fa-f0-9]{1,4}:){0,3}[A-Fa-f0-9]{1,4}';
	        $pattern5 = '([A-Fa-f0-9]{1,4}:){4}:([A-Fa-f0-9]{1,4}:){0,2}[A-Fa-f0-9]{1,4}';
        	$pattern6 = '([A-Fa-f0-9]{1,4}:){5}:([A-Fa-f0-9]{1,4}:){0,1}[A-Fa-f0-9]{1,4}';
	        $pattern7 = '([A-Fa-f0-9]{1,4}:){6}:[A-Fa-f0-9]{1,4}';

        	if(preg_match("{^\b((25[0-5]|2[0-4]\d|[01]\d\d|\d?\d)\.){3}(25[0-5]|2[0-4]\d|[01]\d\d|\d?\d)\b$}",$string)) {
	                return(true); // It's a IPV4 address //
        	} else if (preg_match("/^($pattern1)$|^($pattern2)$|^($pattern3)$|^($pattern4)$|^($pattern5)$|^($pattern6)$|^($pattern7)$/",$string)) {
	                return(true); // It's a IPV6 address //
        	} else {
	                return(false);
        	}
	        return(false);
	}

	/*
	 * Check if a string is a date string
	 * @access private
	 * @param string $string String to check
	 */
	private function is_date($string) {

	        $pattern1 = '([0-9]{1,2}-[0-1]{0,1}[1-9]{1}-[0-9]{2,4})'; // dd-mm-yy(yy)
        	$pattern2 = '([0-9]{1,2}\/[0-1]{0,1}[1-9]{1}\/[0-9]{2,4})'; // dd/mm/yy(yy)
	        $pattern3 = '([0-1]{0,1}[1-9]{1}-[0-9]{1,2}-[0-9]{2,4})'; // mm-dd-yy(yy)
        	$pattern4 = '([0-1]{0,1}[1-9]{1}\/[0-9]{1,2}\/[0-9]{2,4})'; // mm/dd/yy(yy)
	
        	if(preg_match("/^($pattern1)$|^($pattern2)$|^($pattern3)$|^($pattern4)$/",$string)) {
	                return(true);
        	} else {
	                return(false);
        	}
	        return(false);
	}

	/*
	 * Check if a string is a number ( including . and , characters )
	 * @access private
	 * @param string $string String to check
	 */
	private function is_num($string) {

	        $american = '(-){0,1}([0-9]+)(,[0-9][0-9][0-9])*([.][0-9]){0,1}([0-9]*)';
        	$world = '(-){0,1}([0-9]+)(.[0-9][0-9][0-9])*([,][0-9]){0,1}([0-9]*)';

	        if(preg_match("/^($american)$|^($world)$/",$string)) {
        	        return(true);
	        } else {
        	        return(false);
	        }
        	return(false);
	}

	/*
	 * Check if the string is a valid email address
	 * @access private
	 * @param string $string String to check
	 */
	private function is_email($string) {

 	       	if(preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])([-a-z0-9_])+([a-z0-9])*(\.([a-z0-9])([a-z0-9])+)*$/i',$string)) {
			return(true);
		} else {
                	return(false);
	        }
        	return(false);
	}

	/*
	 * Check if the string is a valid mac address
	 * @access private
	 * @param string $string String to check
	 */
	private function is_mac($string) {
 
		if(preg_match("/^([0-9a-fA-F]{2}[-:]{1}){5}([0-9a-fA-F]){2}$/",$string)) {
                	return(true);
	        } else {
        	        return(false);
	        }
		return(false);
	}

	/* Check if the string is a valid linux/unix mount point
	 * @access private
	 * @param string $string String to check
	 */
	private function is_mountpoint($string) {

 		if(preg_match("/^(\/[a-z]{0,20}){1,5}$/",$string)) {
                	return(true);
	        } else {
        	        return(false);
	        }
		return(false);
	}

	/* Check if a string only has a-z, A-Z, 0-9. php's function is_string allowes more characters.
	 * @access private
	 * @param string $string String to check
	 */
	private function is_normstring($string) {

	        if(preg_match("/^([a-zA-Z0-9]+)$/",$string)) {
        	        return(true);
	        } else {
        	        return(false);
	        }
		return(false);
	}

	/*
	 * Clean the string of invalid stuff
	 * @access private
	 * @param string $string String to clean
	 * @return string
	 */
	private function Clean($string) {
		$string = MainFrame::Init("input__filter")->process($string);
		return($string);
	}

	/* Validation functions */

	/*
	 * Clean the string of any invalid SQL injection
	 * @access public
	 * @param object $conn Optional SQL object
	 * @return object
	 */
	public function CleanSQL($conn=FALSE) {
                $this->string = mainframe::Init("input__filter")->safeSQL($this->string, $conn);
                return($this);
	}

	/*
	 * Check if a string is empty or not
	 * @access public
	 * @param bool $allow Allow a string to be empty or not
	 * @return object
	 */
	public function AllowEmpty($allow=FALSE) {
		
		if($this->string != FALSE) {
			if(empty($this->string) && $allow == FALSE) {
				$this->string = FALSE;
			}
		}
		
		return $this;
	}

	/*
	 * Check if the string is smaller then the minimal length
	 * @access public
	 * @param int $lenght Minimal length of the string
	 * @return object
	 */
	public function MinLength($length) {
	
		if($this->string != FALSE) {
			if(strlen($this->string) < $length) {
				$this->string = FALSE;
			}
		}
		
		return $this;
	}

	/*
	 * Check if the string is longer then the maximal length
	 * @access public
	 * @param int $length Maximum length of the string
	 * @return object
	 */
	public function MaxLength($length) {
		
		if($this->string != FALSE) {
			if(strlen($this->string) > $length) {
				$this->string = FALSE;
			}
		}
		
		return $this;
	}

	/*
	 * Main Validation function
	 * @access public
	 * @param string $string String to check
	 * @param string $what What to check for
	 * @return object
	 */
	public function Validate($string, $what) {

		$this->string = $string;
		$this->string = $this->Clean($this->string);
		
		$name = "is_".$what;
		if(is_callable(array($this,$name))) {
			if($this->$name($this->string)==FALSE) {
				$this->string = FALSE;
			}
		} else if(function_exists($name)) {
			if($name($this->string)==FALSE) {
				$this->string = FALSE;
			}
		} else {
			$this->string = FALSE;
		}
		return $this;
	}

	/*
	 * Return the processed string
	 * @access public
	 * @return string
	 */
	public function Process() {
		return($this->string);
	}
}

?>
