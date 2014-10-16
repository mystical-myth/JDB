<?php
class Encryption
{
	public static function mEncrypt($string, $key)
	{
		$iv = "4330";
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB, $iv));
	}

	public static function mDecrypt($string, $key)
	{
		$iv = "4330";
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB, $iv));
	}
}
?>
