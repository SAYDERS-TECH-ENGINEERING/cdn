<?php
	if (!isset($_SESSION)) session_start();
	header('Access-Control-Allow-Origin: *');
	date_default_timezone_set("Asia/Jakarta");
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$ssloption=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),);
	$SERVER = "91.108.104.225";
	$DBUSER = "saydie";
	$DBPASS = "elSaydie666";
	$DBNAME = "seba";
	$HOST = $_SERVER['HTTP_HOST'];
	if (stripos($HOST,"192") !== false || stripos($HOST,"172") !== false || stripos($HOST,"local") !== false) {
		$SERVER = "localhost";
		$DBUSER = "root";
		$DBPASS = "";
	}
	$TAG = "";
	$USQL = "";
	$DATA = "";
	$YEAR = date("Y");
	if (isset($_GET['tag'])) {
		$TAG = $_GET['tag'];
	} else if (isset($_POST['tag'])) {
		$TAG = $_POST['tag'];
	} else if (isset($_SERVER['QUERY_STRING'])) {
		$TAG = explode("=",$_SERVER['QUERY_STRING'])[0];
	}
	if (isset($_GET['usql'])) {
		$USQL = $_GET['usql'];
	} else if (isset($_POST['usql'])) {
		$USQL = $_POST['usql'];
	}
	if ($TAG == "tes") {
		
	} else if ($TAG == "saran") {
		$VAL = $_POST['d1'];
		$DIR = "data/saran/";
		if (!is_dir($DIR)) {mkdir($DIR, 0777, true);}
		$FL = date('Y-m-d~h-i-s').".txt";
		if (file_put_contents($DIR.$FL,$VAL)) {
			echo "SUKSES";
		} else {
			echo 0;
		}
	} else if ($TAG == "getdata") {
		$DIR = "data/";
		$K = $_GET['k1'];
		if ($K != "saran") {
			$FL = $DIR.$K.".txt";
			if (file_exists($FL)) {
				$V = file_get_contents($FL,false,stream_context_create($ssloption));
				echo $V;
			} else {
				echo 0;
			}	
		} else {
			$DATA = "";
			$DIR = "data/saran/";
			if (is_dir($DIR)) {
				$FLS = scandir($DIR);
				foreach ($FLS as $F) {
					if ($F != "." && $F != "..") {
						if (stripos($F,".txt") !== false) {
							$FL = $DIR.$F;
							$V = file_get_contents($FL,false,stream_context_create($ssloption));
							$IT = '{"tms":"'.$F.'","val":"'.$V.'"}';
							if ($DATA != "") {$DATA .= ",";}
							$DATA .= $IT;
						}
					}
				}
			}
			echo '['.$DATA.']';
		}
	} else if ($TAG == "getrefs") {
		$DIR = "data/country/indonesia/";
		$K = $_GET['k1'];
		if ($K != "provinsi") {
			$I = $_GET['k2'];
			if ($K == "daerah") {
				$K = "/kabupaten/".$I;
			} else {
				$K .= "/".$I;
			}
		}
		$FL = $DIR.$K.".json";
		if (is_file($FL)) {
			$V = file_get_contents($FL,false,stream_context_create($ssloption));
			echo $V;
		} else {
			echo 0;
		}
	} else if ($TAG == "units") {
		$VAL = $_POST['d1'];
		$DIR = "data/";
		if (!is_dir($DIR)) {mkdir($DIR, 0777, true);}
		$FL = "units.txt";
		if (file_put_contents($DIR.$FL,$VAL)) {
			echo "SUKSES";
		} else {
			echo 0;
		}
	} else if ($TAG == "statsbsn") {
		$DATA = "";
		$SKL = json_decode(file_get_contents("data/units.txt",false,stream_context_create($ssloption)),true);
		$DIR = "data/sbsn/";
		$THN = date('Y');
		$YER = '["'.$THN.'","'.($THN-1).'","'.($THN-2).'"]';
		$THS = array($THN,($THN-1),($THN-2));
		foreach ($THS as $Y) {
			$TDR = $DIR.$Y."/";
			if (is_dir($TDR)) {
				$DSKL = "";
				$ACAP = 0;
				$ATGT = 0;
				foreach($SKL as $S) {
					$TGT = 0;
					$CAP = 0;
					$SIS = 0;
					$PRS = 0;
					$PRE = "";
					$UNT = 0;
					$TRX = 0;
					$SKI = 0;
					$SKC = 0;
					$SPR = 0;
					$SSS = 0;
					$SID = $S['id'];
					$SKD = $S['skl'];
					$SNM = $S['nam'];
					$SIC = $S['icn'];
					$STG = $S['tag'];
					$SDC = $S['dsc'];
					$TDR = $DIR.$Y."/";
					$FP = $TDR."pagu-".strtolower($STG).".json";
					$FT = $TDR."transaksi-".strtolower($STG).".json";
					$FK = $TDR."kurva-".strtolower($STG).".json";
					$VP = "";
					$VT = "";
					$VK = "";
					if (is_file($FP)) {
						if (is_file($FT)) {$VT = file_get_contents($FT,false,stream_context_create($ssloption));}
						if (is_file($FK)) {$VK = file_get_contents($FK,false,stream_context_create($ssloption));}
						$NP = array();
						$VP = file_get_contents($FP,false,stream_context_create($ssloption));
						$JP = json_decode($VP,true);
						foreach($JP as $P) {
							$WN = $P['WNAM'];
							$DR = str_replace("KAB ","KABUPATEN ",str_replace("KAB.","KABUPATEN",$P['DNAM']));
							$UN = $P['UNAM'];
							$P['CREL'] = 0;
							$P['CSIS'] = 0;
							$P['CPRS'] = "0";
							$P['KREL'] = 0;
							$P['KPAG'] = 0;
							$P['KSIS'] = 0;
							$P['KPRS'] = "0";
							if ($P['CPAG'] != "") {
								$P['CSIS'] = (int)$P['CPAG'];
								$P['KPAG'] = $P['CPAG'];
								$P['KSIS'] = (int)$P['CPAG'];
								$TGT += (int)$P['CPAG'];
								if ($VT != "") {
									$JT = json_decode($VT,true);
									foreach($JT as $T) {
										$ID = str_replace("KAB ","KABUPATEN ",str_replace("KAB.","KABUPATEN",$T['DNAM']));
										if ($T['WNAM'] == $WN && $ID == $DR && $T['UNAM'] == $UN) {
											if ($T['SPMN'] != "") {
												$P['CREL'] += (int)$T['SPMN'];
												$P['CSIS'] -= (int)$T['SPMN'];
												$CAP += (int)$T['SPMN'];
												$TRX++;
												if ($T['SPDN'] != "" && $T['SPDN'] != "0") {
													$P['KREL'] += (int)$T['SPMN'];
													$P['KSIS'] -= (int)$T['SPMN'];
													$SKI++;
													$SKC += (int)$T['SPMN'];
												}
											}
										}
									}
									if ($VK != "") {
										$JK = json_decode($VK,true);
										foreach($JK as $K) {
											if ($K['WILAYAH'] == $WN && $K['DAERAH'] == $DR && $K['UNIT'] == $UN) {
												$P['KRN'] = $K['RENCANA'];
												$P['KPG'] = $K['PROGRES'];
											}
										}
									}
									if ($P['CREL'] > 0) {
										if ($P['CSIS'] > 0) {
											$CPR = number_format((($P['CREL']*100)/$P['CPAG']),1);
											$P['CPRS'] = $CPR;
										} else {
											$P['CPRS'] = "100";
										}
									}
									if ($P['KREL'] > 0) {
										if ($P['KSIS'] > 0) {
											$CPR = number_format((($P['KREL']*100)/$P['CPAG']),1);
											$P['KPRS'] = $CPR;
										} else {
											$P['KPRS'] = "100";
										}
									}
								}
							}
							array_push($NP,$P);
						}
						$UNT = count($JP);
					} else {
						$NP = '[]';
					}
					$PRS = 0;
					$SIS = ($TGT-$CAP);
					$SSS = ($TGT-$SKC);
					if ($TGT > 0 && $CAP > 0) {
						$PRS = number_format((($CAP*100)/$TGT),1);
					}
					if ($TGT > 0 && $SKC > 0) {
						$SPR = number_format((($SKC*100)/$TGT),1);
					}
					$STS = "KURANG";
					if ($PRS > 50) {
						if ($PRS < 65) {
							$STS = "CUKUP";
						} else if ($PRS < 75) {
							$STS = "BAIK";
						} else if ($PRS < 90) {
							$STS = "SANGAT BAIK";
						} else if ($PRS > 90) {
							$STS = "MEMUASKAN";
						}
					}
					$IT = '{"SID":"'.$SID.'","SKD":"'.$SKD.'","TAG":"'.$STG.'","NAM":"'.$SNM.'","SDC":"'.$SDC.'","SIC":"'.$SIC.'","TGT":"'.$TGT.'","CAP":"'.$CAP.'","SIS":"'.$SIS.'","PRS":"'.$PRS.'","PRE":"'.$STS.'","UNT":"'.$UNT.'","TRX":"'.$TRX.'","SKI":"'.$SKI.'","SKC":"'.$SKC.'","SPR":"'.$SPR.'","SSS":"'.$SSS.'","DATA":'.json_encode($NP).'}';
					if ($DSKL != "") {$DSKL .= ",";}
					$DSKL .= $IT;
					$ACAP += $CAP;
					$ATGT += $TGT;
				}
				$APRS = 0;
				$ASTS = "KURANG";
				if ($ACAP > 0 && $ATGT > 0) {
					$APRS = number_format((($ACAP*100)/$ATGT),1);
				}
				if ($APRS > 50) {
					if ($APRS < 65) {
						$STS = "CUKUP";
					} else if ($APRS < 75) {
						$STS = "BAIK";
					} else if ($APRS < 90) {
						$STS = "SANGAT BAIK";
					} else if ($APRS > 90) {
						$STS = "MEMUASKAN";
					}
				}
				$IT = '{"THN":"'.$Y.'","PRS":"'.$APRS.'","PRE":"'.$ASTS.'","DATA":['.$DSKL.']}';
				if ($DATA != "") {$DATA .= ",";}
				$DATA .= $IT;
			}
		}
		echo '['.$DATA.']';
	} else if ($TAG == "getstat") {
		$DATA = "";
		$SKL = ["MADRASAH","HAJI","KUA","PLKI","ASRAMA","PTKIN"];
		$DIR = "data/database/";
		$KATS = scandir($DIR);
		foreach($KATS as $K) {
			if ($K != "." && $K != "..") {
				$CPR = 0;
				$APR = "";
				$DTS = "";
				$LIS = array();
				foreach($SKL as $S) {
					$LIS = array();
					$TPG = 0;
					$TTR = 0;
					$UNT = 0;
					$TRX = 0;
					$SKI = 0;
					$SKT = 0;
					$SKC = 0;
					$SKS = 0;
					$SPR = 0;
					$FP = $DIR.strtolower($K)."/pagu/pagu-".strtolower($S).".json";
					$FT = $DIR.strtolower($K)."/transaksi/transaksi-".strtolower($S).".json";
					$FK = $DIR.strtolower($K)."/kurva/kurva-".strtolower($S).".json";
					$VTR = "";
					$KTR = "";
					if (is_file($FT)) {
						$VTR = file_get_contents($FT,false,stream_context_create($ssloption));
						$JTR = json_decode($VTR,true);
					}
					if (is_file($FK)) {
						$KTR = file_get_contents($FK,false,stream_context_create($ssloption));
						$JKR = json_decode($KTR,true);
					}
					if (is_file($FP)) {
						$VPG = file_get_contents($FP,false,stream_context_create($ssloption));
						$JPG = json_decode($VPG,true);
						foreach ($JPG as $P) {
							if ($P['CPAG'] != "") {
								$TPG += (int)$P['CPAG'];
								$WN = $P['WNAM'];
								$DR = str_replace("KAB.","KABUPATEN",$P['DNAM']);
								$DR = str_replace("KAB ","KABUPATEN ",$DR);
								$UN = $P['UNAM'];
								$P['CREL'] = 0;
								$P['CSIS'] = (int)$P['CPAG'];
								$P['CPRS'] = "0";
								$P['KREL'] = 0;
								$P['KPAG'] = $P['CPAG'];
								$P['KSIS'] = (int)$P['CPAG'];
								$P['KPRS'] = "0";
								if ($VTR != "") {
									foreach($JTR as $T) {
										$ID = str_replace("KAB.","KABUPATEN",$T['DNAM']);
										$ID = str_replace("KAB ","KABUPATEN ",$ID);
										if ($T['WNAM'] == $WN && $ID == $DR && $T['UNAM'] == $UN) {
											if ($T['SPMN'] != "") {
												//$TTR += (int)$T['SPMN'];
												$P['CREL'] += (int)$T['SPMN'];
												$P['CSIS'] -= (int)$T['SPMN'];
												if ($T['SPDN'] != "" && $T['SPDN'] != "0") {
													$P['KREL'] += (int)$T['SPMN'];
													$P['KSIS'] -= (int)$T['SPMN'];
												}
											}
										}
									}
									foreach($JKR as $k) {
										if ($k['WILAYAH'] == $WN && $k['DAERAH'] == $DR && $k['UNIT'] == $UN) {
											$P['KRN'] = $k['RENCANA'];
											$P['KPG'] = $k['PROGRES'];
										}
									}
									if ($P['CREL'] > 0) {
										if ($P['CSIS'] > 0) {
											$CPR = number_format((($P['CREL']*100)/$P['CPAG']),1);
											$P['CPRS'] = $CPR;
										} else {
											$P['CPRS'] = "100";
										}
									}
									if ($P['KREL'] > 0) {
										if ($P['KSIS'] > 0) {
											$CPR = number_format((($P['KREL']*100)/$P['CPAG']),1);
											$P['KPRS'] = $CPR;
										} else {
											$P['KPRS'] = "100";
										}
									}
								} else {
									
								}
								array_push($LIS,$P);
							}
						}
						$UNT = count($JPG);
					}
					$FT = $DIR.strtolower($K)."/transaksi/transaksi-".strtolower($S).".json";
					if (is_file($FT)) {
						$VPR = file_get_contents($FT,false,stream_context_create($ssloption));
						$JTR = json_decode($VPR,true);
						foreach ($JTR as $T) {
							if ($T['SPMN'] != "") {
								$TTR += (int)$T['SPMN'];
								$TRX++;
								if ($T['SPDN'] != "" && $T['SPDN'] != "0") {
									$SKC += (int)$T['SPMN'];
									$SKI++;
								}
							}
						}	
					}
					//echo $S."=".number_format($TPG)." / ".number_format($TTR)."<br>";
					$PRS = 0;
					$SIS = ($TPG-$TTR);
					$SKS = ($TPG-$SKC);
					if ($TPG > 0 && $TTR > 0) {
						$PRS = number_format((($TTR*100)/$TPG),1);
					}
					if ($TPG > 0 && $SKC > 0) {
						$SPR = number_format((($SKC*100)/$TPG),1);
					}
					$STS = "KURANG";
					if ($PRS > 50) {
						if ($PRS < 65) {
							$STS = "CUKUP";
						} else if ($PRS < 75) {
							$STS = "BAIK";
						} else if ($PRS < 90) {
							$STS = "SANGAT BAIK";
						} else if ($PRS > 90) {
							$STS = "MEMUASKAN";
						}
					}
					$IT = '{"TAG":"'.$S.'","TGT":"'.$TPG.'","CAP":"'.$TTR.'","SIS":"'.$SIS.'","PRS":"'.$PRS.'","PRE":"'.$STS.'","UNT":"'.$UNT.'","TRX":"'.$TRX.'","SKI":"'.$SKI.'","SKT":"'.$TPG.'","SKC":"'.$SKC.'","SPR":"'.$SPR.'","DATA":'.json_encode($LIS).'}';
					if ($DTS != "") {$DTS .= ",";}
					$DTS .= $IT;
					if ($APR != "") {$APR .= ",";}
					$APR .= '"'.$PRS.'"';
					$CPR += (int)$PRS;
				}
				$STS = "KURANG";
				$XPR = 0;
				$TPR = (count($SKL)*100);
				if ($CPR > 0) {
					$XPR = number_format((($CPR*100)/$TPR),1);
					if ($XPR > 50) {
						if ($XPR < 65) {
							$STS = "CUKUP";
						} else if ($XPR < 75) {
							$STS = "BAIK";
						} else if ($XPR < 90) {
							$STS = "SANGAT BAIK";
						} else if ($XPR > 90) {
							$STS = "MEMUASKAN";
						}
					}
				}
				$IT = '{"TAG":"'.strtoupper($K).'","DATA":['.$DTS.'],"PRS":['.$APR.'],"STS":"'.$STS.'","XPR":"'.$XPR.'"}';
				if ($DATA != "") {$DATA .= ",";}
				$DATA .= $IT;
			}
		}
		echo "[".$DATA."]";
	} else {
		echo "NO TAG";
	}
?>