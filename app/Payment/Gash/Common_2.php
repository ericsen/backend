<?php
	namespace App\Payment\Gash;
    use App\Payment\Gash\Crypt3Des;
    use DOMDocument;
	// ===============================================================================
	/* 
	 * Note:
	 * 解析回傳資料
	 * 
	 */
	// ===============================================================================
	
	include( "Cryptography_2.php" );
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

	// // 商家密碼
	// $p = "E8yHPLkMJWD";
	// // 商家密鑰 I
	// $k = "tUjNA9FK9ApUM2rIyEmZV8XxQ6yqkVT/";
	// // 商家密鑰 II
	// $v = "2y3hadbaggk=";
	

	
	// 交易物件
	class Trans
	{
		private $key = ""; // key for content provider 
		private $iv = ""; // iv for content provider 
		private $odata = ""; // Recv From CP Module 
		private $data = ""; // XML String
		private $xmlDoc = null; // XML Object
		private $bolIsParsed = false; // parse flag
		public $msg = ""; 
		public $recvDesc = ""; 
		public $nodes = null; 
		public $base64_encrypt_data = ""; 
		public $encrypt_data = ""; 

		/**
		* 建構式
		*
		* @param string $odata
		*/
		function __construct ($odata)
		{
			if (empty($odata)) {			
				$this->xmlDoc = new DOMDocument();
				$this->bolIsParsed = $this->xmlDoc->loadXML( "<TRANS />" );
							
				$nodes = array();				
			}else{			
				$this->odata = $odata;
				$this->data = base64_decode( $this->odata );
				
				$this->xmlDoc = new DOMDocument();
				$this->bolIsParsed = $this->xmlDoc->loadXML( $this->data );
				
				if ( !$this->bolIsParsed ) {
					$this->msg = "trans data format is not valid";
					exit();
				}
				
				$nodes = array();
				$this->GetNodes();
			}
		}
		
		// 解析 XML 資料
		private function GetNodes()
		{
			$node = $this->xmlDoc->documentElement;
			if ($node->hasChildNodes()) 
			{ 
				foreach ($node->childNodes as $childNode) 
				{ 
					if ($childNode->nodeType != XML_TEXT_NODE) {
						if ( $childNode->nodeName == "RCODE" ) {
						
							switch( $childNode->textContent ){
								/// 訊息處理成功
								case "00": $this->recvDesc = "Successful_Approval_Completion"; break;
								/// 不合法的Merchant ID or Branch ID or Content ID
								case "03": $this->recvDesc = "Invalid_Merchant_or_Branch_or_Content"; break;
								/// 不合法的Content ID
								case "04": $this->recvDesc = "Invalid_Content"; break;
								/// 交易不合法
								case "12": $this->recvDesc = "Invalid_Transaction"; break;
								/// 金額不合法
								case "13": $this->recvDesc = "Invalid_Amount"; break;
								/// Payment Agency不存在或不合法
								case "15": $this->recvDesc = "Invalid_Payment_Agency"; break;
								/// Payment Agency不提供月租服務
								case "16": $this->recvDesc = "Invalid_Payment_Agency_Not_Suppot_Rent_Service"; break;
								/// 交易重複
								case "19": $this->recvDesc = "Re_Enter_Transaction"; break;
								/// 訊息格式檢察錯誤
								case "30": $this->recvDesc = "Format_Check_Error"; break;
								/// 訊息格式檢察錯誤 (PA幣別錯誤)
								case "35": $this->recvDesc = "Format_Check_Error_PA_Currency"; break;
								/// 訊息格式檢查錯誤 (XML無法解析)
								case "36": $this->recvDesc = "Format_Check_Error_XML_Can_Not_Parse"; break;
								/// 額度不足
								case "51": $this->recvDesc = "Insufficient_Funds"; break;
								/// 超過金額上限
								case "61": $this->recvDesc = "Exceed_the_Upper_Amount"; break;
								/// 驗證碼錯誤
								case "63": $this->recvDesc = "Security_Check_Error"; break;
								/// 無法找到原始交易, 例如退訂找不到原始訂單編號
								case "76": $this->recvDesc = "Unable_to_Locate_Previous_Message"; break;
								/// 找到原始交易, 但是交易內容比對不一致
								case "77": $this->recvDesc = "Data_are_Inconsistent_with_Original_Message"; break;
								/// PA交易無法完成
								case "91": $this->recvDesc = "PA_Transaction_Can_Not_Finish"; break;
								/// 系統發生異常
								case "96": default: $this->recvDesc = "System_Malfunction"; break;
							}
							
						}						
						$this->nodes[ $childNode->nodeName ] = $childNode->textContent;
					}
				} 
			} 
		}
		
		// 產生 XML 物件
		public function GenerateXmlDoc()
		{
			if ( count( $this->nodes ) > 0 ){
				foreach ( $this->nodes as $key => $value ) 
				{ 
					$fnode = $this->xmlDoc->createElement( $key, $value );
					//$fnode->textContent = $value;
					$this->xmlDoc->documentElement->appendChild($fnode);
				} 
			}
			
			return $this->xmlDoc->saveXML();
		}
		
		// 取得 XML 字串
		public function GetXMLString()
		{
			return $this->data;
		}
		
		// 建構送出之交易、查詢、請款資料
		public function GetSendData()
		{
			return base64_encode( $this->GenerateXmlDoc() );
		}

		/**
		* 產生商家交易驗證壓碼
		* 
		* @param string $pwd
		* @param string $key
		* @param string $iv
		*/
		public function GetERQC( $pwd = "xxx", $key = "xxx", $iv = "xxx" ) {
			if ( !$this->bolIsParsed ) {					
				$this->msg = "trans data format is not valid";
				return false;
			} else if (empty($key) || empty($iv)) {					
				$this->msg = "key and iv is not valid";
				return false;
			}					
			$this->key = $key;
			$this->iv = $iv;					
			$cid = "";
			$coid = "";
			$cuid = "";
			$amt = "";
			// vdata = cid + coid + cuid + amt(12,2) + pwd
			
			// Get Content ID
			$cid = $this->nodes["CID"];

			// Get Content Ordere ID
			$coid = $this->nodes["COID"];

			// Get Trans Currency ID
			$cuid = $this->nodes["CUID"];

			// Get Trans Amount need parse to fix format
			$amt = $this->nodes["AMOUNT"];

			return $this->_GetERQC($cid, $coid, $cuid, $amt, $pwd);
		}

		/**
		* 產生商家交易驗證壓碼
		* 
		* @param string $cid
		* @param string $coid
		* @param string $cuid
		* @param string $amt
		* @param string $pwd 
		*/
        private function _GetERQC($cid, $coid, $cuid, $amt, $pwd)
        {
			$erqc = "";
            $encrypt_data = "%s%s%s%s%s";
			
            // 驗證用的 AMOUNT 需整理成 14 碼
            if (strpos($amt, ".") !== false)
            {
                $amt = substr($amt, 0, strpos($amt, ".")) . ((strlen($amt) - strpos($amt, ".")) > 3 ? substr($amt, strpos($amt, ".") + 1, 2) : str_pad(substr($amt, (strpos($amt, ".") + 1)), 2, "0"));
                $amt = str_pad($amt, 14, "0", STR_PAD_LEFT);
            }
            else
            {
                $amt = str_pad($amt, 12, "0", STR_PAD_LEFT) . "00"; //.PadLeft(14, '0');
            }

			//$amt = "00000000005000";
            $this->encrypt_data = sprintf($encrypt_data, $cid, $coid, $cuid, $amt, $pwd);

			$des = new Crypt3Des($this->key,$this->iv);
			$this->base64_encrypt_data = $des->encrypt( $this->encrypt_data );
			$erqc = base64_encode( sha1( $this->base64_encrypt_data, true ) );
			
            return $erqc;
        }
		
		// 檢核商家交易驗證壓碼
        public function VerifyERQC($pwd = "xxx", $key = "xxx", $iv = "xxx")
        {
			if ( $pwd == "xxx" || $key == "xxx" || $iv == "xxx" ) return false;
			
            $cp_data = $this->GetERQC($pwd, $key, $iv);
			$gps_data = $this->nodes["ERQC"];
			
            return ($gps_data != "" && $cp_data != "" && $gps_data == $cp_data);
        }
		
		/**
		* 產生GPS交易驗證壓碼
		* 
		* @param string $key
		* @param string $iv
		*/
        public function GetERPC( $key = "xxx", $iv = "xxx" )
        {
			if ( !$this->bolIsParsed ) {
			
				$this->msg = "trans data format is not valid";
				return false;

			}else if (empty($key) || empty($iv)) {
			
				$this->msg = "key and iv is not valid";
				return false;
			}
			
			$this->key = $key;
			$this->iv = $iv;
			
            $cid = "";
            $coid = "";
            $rrn = "";
            $cuid = "";
            $amt = "";
            $rcode = "";

			// vdata = cid + coid + cuid + amt(12,2) + $rcode
			
			// Get Content ID
			$cid = $this->nodes["CID"];

			// Get Content Ordere ID
			$coid = $this->nodes["COID"];

			// Get GPS Ordere ID
			$rrn = $this->nodes["RRN"];

			// Get Trans Currency ID
			$cuid = $this->nodes["CUID"];

			// Get Trans Amount need parse to fix format
			$amt = $this->nodes["AMOUNT"];

			// Get Trans Amount need parse to fix format
			$rcode = $this->nodes["RCODE"];

            return $this->_GetERPC($cid, $coid, $rrn, $cuid, $amt, $rcode);
        }

		/**
		* 產生GPS交易驗證壓碼
		* 
		* @param string $cid
		* @param string $coid
		* @param string $rrn
		* @param string $cuid
		* @param string $amt
		* @param string $rcode
		*/
        private function _GetERPC($cid, $coid, $rrn, $cuid, $amt, $rcode)
        {
			$erpc = "";
            $encrypt_data = "%s%s%s%s%s%s";
			
            // 驗證用的 AMOUNT 需整理成 14 碼
            if (strpos($amt, ".") !== false)
            {
                $amt = substr($amt, 0, strpos($amt, ".")) . ((strlen($amt) - strpos($amt, ".")) > 3 ? substr($amt, strpos($amt, ".") + 1, 2) : str_pad(substr($amt, (strpos($amt, ".") + 1)), 2, "0"));
                $amt = str_pad($amt, 14, "0", STR_PAD_LEFT);
            }
            else
            {
                $amt = str_pad($amt, 12, "0", STR_PAD_LEFT) . "00"; //.PadLeft(14, '0');
            }

			//$amt = "00000000005000";
            $this->encrypt_data = sprintf($encrypt_data, $cid, $coid, $rrn, $cuid, $amt, $rcode);

			$des = new Crypt3Des($this->key,$this->iv);
			$this->base64_encrypt_data = $des->encrypt( $this->encrypt_data );
			$erpc = base64_encode( sha1( $this->base64_encrypt_data, true ) );

            return $erpc;
        }
		
		// 檢核GPS交易驗證壓碼
        public function VerifyERPC($key = "xxx", $iv = "xxx")
        {
			if ( $key == "xxx" || $iv == "xxx" ) return false;
									
            $cp_data = $this->GetERPC($key, $iv);			
			$gps_data = $this->nodes["ERPC"];		
			
            return ($gps_data != "" && $cp_data != "" && $gps_data == $cp_data);
        }		
	}
	
	// 建構交易處理代碼下拉選單
	function BuildDdlPCODE($_pcode = "")
	{
		$options = "<select name='PCODE' style='width: 395px;'>";
		
		if ( $_pcode != "" ) 
		{
			$_pcode = "PCODE$_pcode"; 
			$$_pcode = "selected";
		}
		
		$options .= "<option value='300000' $PCODE300000>[ 300000 ] 訂單</option>";
		$options .= "<option value='303000' $PCODE303000>[ 303000 ] 月租訂單</option>";
		$options .= "<option value='310000' $PCODE310000>[ 310000 ] 退訂</option>";
		$options .= "<option value='330000' $PCODE330000>[ 330000 ] 退租</option>";
		$options .= "<option value='200000' $PCODE200000>[ 200000 ] 查單</option>";
		
		$options .= "</select>";
		return $options;
	}	
	
	// 建構付款代收業者代碼 下拉選單
	function BuildDdlPAID($_paid = "")
	{
		$options = "<select name='PAID' style='width: 395px;'>";
		
		if ( $_paid != "" ) $$_paid = "selected";
		
		$options .= "<option value='NULL'>不指定 PA</option>";
		$options .= "<option value='TELTCC01' $TELTCC01>[ TELTCC01 ] 台灣大哥大</option>";
		$options .= "<option value='TELTCC02' $TELTCC02>[ TELTCC02 ] 台灣大哥大月租型</option>";
		$options .= "<option value='TELFET01' $TELFET01>[ TELFET01 ] 遠傳電信</option>";
		$options .= "<option value='TELFET02' $TELFET02>[ TELFET02 ] 遠傳電信月租型</option>";
		$options .= "<option value='TELCHT01' $TELCHT01>[ TELCHT01 ] 中華電信市內電話</option>";
		$options .= "<option value='TELCHT02' $TELCHT02>[ TELCHT02 ] 中華電信Hinet</option>";
		$options .= "<option value='TELCHT03' $TELCHT03>[ TELCHT03 ] 中華電信839行動電話</option>";
		$options .= "<option value='TELCHT04' $TELCHT04>[ TELCHT04 ] 中華電信839行動電話月租型</option>";
		$options .= "<option value='BNK82201' $BNK82201>[ BNK82201 ] 中國信託信用卡</option>";
		$options .= "<option value='BNK82202' $BNK82202>[ BNK82202 ] 中國信託信用卡-紅利</option>";
		$options .= "<option value='BNK80801' $BNK80801>[ BNK80801 ] 玉山WebATM</option>";
		$options .= "<option value='BNK80802' $BNK80802>[ BNK80802 ] 玉山消費性付款</option>";
		$options .= "<option value='COPALI01' $COPALI01>[ COPALI01 ] 藍新-支付寶</option>";
		$options .= "<option value='COPPEZ01' $COPPEZ01>[ COPPEZ01 ] PayEasy</option>";
		$options .= "<option value='COPPAL01' $COPPAL01>[ COPPAL01 ] PayPal</option>";
		$options .= "<option value='COPWEC01' $COPWEC01>[ COPWEC01 ] WebCash</option>";
		$options .= "<option value='TELDANAL01' $TELDANAL01>[ TELDANAL01 ] TELEPAY</option>";
		$options .= "<option value='COPGAM01' $COPGAM01>[ COPGAM01 ] GASHPCP</option>";
		$options .= "<option value='COPGAM03' $COPGAM03>[ COPGAM03 ] GASHPCP_HK</option>";
		$options .= "<option value='COPGAM02' $COPGAM02>[ COPGAM02 ] PINHALL</option>";
		
		$options .= "</select>";
		return $options;
	}
	
	// 建構測試環境下拉選單
	function BuildRdoDescServer($_server = "")
	{
		if ( $_server != "" ) $$_server = "checked";
		else $local = "checked";
		
		$options  = "";
		$options .= "<input type='radio' name='desc_server' value='local' $local>[ local ] 本機</option>";
		$options .= "<input type='radio' name='desc_server' value='remote' $remote>[ remote ] 測試機</option>";
		
		return $options;
	}
	
?>