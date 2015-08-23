<?php
abstract class AESAlgorithm
{
	protected $algorithmCipher = MCRYPT_RIJNDAEL_128;
	protected $algorithmMode   = MCRYPT_MODE_ECB;
	protected $fillingMethod   = NULL;
	protected $secretKey       = NULL;
	protected $IV              = NULL;
	
	abstract protected function encryptFunction($str);
	abstract protected function decryptFunction($str);
 
	public function setAlgorithmCipher($algorithmCipher)
	{
		$this->algorithmCipher = $algorithmCipher; 
	}

	public function setAlgorithmMode($algorithmMode)
	{
		$this->algorithmMode = $algorithmMode;
	}

	public function setIV($IV)
	{
		$this->IV = $IV;
	}

	public function setSecretKey($secretKey)
	{
		$this->secretKey = $secretKey;
	}

	public function setFillingMethod($methodName)
	{
		$this->fillingMethod = $methodName;
	}
	
	public function encrypt($str)
	{
		return $this->encryptFunction($str);
	}

	public function decrypt($str)
	{
		return $this->decryptFunction($str);
	}
}

class AES extends AESAlgorithm
{
    public function __construct($secretKey)
	{
	    $this->setSecretKey($secretKey);
		$this->setFillingMethod('pkcs5');
	}
	
	protected function fillingOrUNfilling($str, $ext)
	{
		if (is_null($this->fillingMethod))
		{
			return $str;
		}
		else 
		{
			$className = __CLASS__;
			$func_name = "{$className}::{$this->fillingMethod}{$ext}Filling";
			if (is_callable($func_name))
			{
				$size = mcrypt_get_block_size($this->algorithmCipher, $this->algorithmMode);
				return call_user_func($func_name, $str, $size);
			}
		}
		return $str; 
	}

	protected function filling($str)
	{
		return $this->fillingOrUNfilling($str, ""); 
	}

	protected function unfilling($str)
	{
		return $this->fillingOrUNfilling($str, "un"); 
	}

	protected function encryptFunction($str)
	{
		$str = $this->filling($str);
		$td = mcrypt_module_open($this->algorithmCipher, "", $this->algorithmMode, "");

		if (empty($this->IV))
		{
			$IV = @mcrypt_create_IV(mcrypt_enc_get_IV_size($td), MCRYPT_RAND);
		}
		else
		{
			$IV = $this->IV;
		}
		mcrypt_generic_init($td, $this->secretKey, $IV);
		$cyper_text = mcrypt_generic($td, $str);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return $cyper_text;
	}

	protected function decryptFunction($str)
	{
		$td = mcrypt_module_open($this->algorithmCipher, "", $this->algorithmMode, "");

		if (empty($this->IV))
		{
			$IV = @mcrypt_create_IV(mcrypt_enc_get_IV_size($td), MCRYPT_RAND);
		}
		else
		{
			$IV = $this->IV;
		}
		mcrypt_generic_init($td, $this->secretKey, $IV);
		$decrypted_text = mdecrypt_generic($td, $str);
		$rt = $decrypted_text;
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return $this->unfilling($rt);
	}

	protected static function Hex2Bin($hexdata)
	{
		$bindata = "";
		$length = strlen($hexdata); 
		for ($i=0; $i < $length; $i += 2)
		{
			$bindata .= chr(hexdec(substr($hexdata, $i, 2)));
		}
		return $bindata;
	}

	protected static function pkcs5Filling($text, $blocksize)
	{
		$filling = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($filling), $filling);
	}

	protected static function pkcs5UNfilling($text)
	{
		$filling = ord($text{strlen($text) - 1});
		if ($filling > strlen($text)) return false;
		if (strspn($text, chr($filling), strlen($text) - $filling) != $filling) return false;
		return substr($text, 0, -1 * $filling);
	}
}
?>