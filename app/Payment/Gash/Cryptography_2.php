<?php
namespace App\Payment\Gash;
	// ===============================================================================
	/* 
	 * Note:
	 * 加解密
	 * 
	 */
	// ===============================================================================
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

	class Crypt3Des
	{
		private $key = "";
		private $iv = "";
		/**
		* @param string $key
		* @param string $iv
		*/
		function __construct ($key, $iv)
		{
			if (empty($key) || empty($iv)) {
				echo 'key and iv is not valid';
				exit();
			}
			$this->key = $key;
			$this->iv = $iv;
		}
	
		/**
		* @param <type> $value
		* @return <type>
		*/
		public function encrypt ($value)
		{
			$iv = base64_decode($this->iv);
			$key = base64_decode($this->key);
			$value = $this->PaddingPKCS7($value);
			$ret = openssl_encrypt($value, "DES-EDE3-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
			$ret = base64_encode( $ret );
			return $ret;
		}
		
		
		private function PaddingPKCS7 ($data)
		{
			$block_size = 8;
			$padding_char = $block_size - (strlen($data) % $block_size);
			$data .= str_repeat(chr($padding_char), $padding_char);
			return $data;
		}
	}
?>