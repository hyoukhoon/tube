<?php
	$now=time();
	$now1=date("mdHis");
	$now2=date("Y-m-d");
	$now3=date("YmdHis");
	$now4=date("Y-m-d H:i:s");
	$now5=date("Ymd");
	$now_time=date("YmdHi");
	$y_1=date("Y")+1;
	$one_year=$y_1.date("m").date("d");

	$d7=date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-7, date("Y")));
	$d14=date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-14, date("Y")));
	$d30=date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-30, date("Y")));

	$rander=rand();

//	$domain="http://www.ccumim.com";

	$admin_mail="help@autocoin.kr";

	$company="AutoCoin";




function conv_eu($text){
	return mb_convert_encoding("$text","EUC-KR","UTF-8");
}

function conv_ue($text){
	return mb_convert_encoding("$text","UTF-8","EUC-KR");
}


function auth_is($n){

		switch($n) {
			
			case -1:$rs="탈퇴회원";
			break;
			case 0:$rs="미인증회원";
			break;
			case 1:$rs="일반회원";
			break;
			case 100:$rs="관리자";
			break;

		}

		return $rs;

	}

function amount_is($gubun,$uid){
	global $mysqli;

	$que3="select sum(amount), sum(susu) from transaction_list where category='receive' and gubun='$gubun' and uid='$uid' and confirmed>=3";
	$result3 = $mysqli->query($que3) or die("2:".$mysqli->error);
	$rs3 = $result3->fetch_array();
	$amt1=$rs3[0]+$rs3[1];

	$que3="select sum(amount), sum(susu) from transaction_list where category='send' and gubun='$gubun' and uid='$uid'";
	$result3 = $mysqli->query($que3) or die("2:".$mysqli->error);
	$rs3 = $result3->fetch_array();
	$amt2=$rs3[0]+$rs3[1];


	return $amt1+$amt2;
}

function susuis($coin){


	global $mysqli;
	$que="select * 
	from susu";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();

	switch($coin) {
			
			case "bitcoin":$rs=$rs->bitcoin;
			break;
			case "eth":$rs=$rs->eth;
			break;
			case "dash":$rs=$rs->dash;
			break;
			case "litecoin":$rs=$rs->litecoin;
			break;
			case "zcash":$rs=$rs->zcash;
			break;
			case "bch":$rs=$rs->bch;
			break;
			case "etc":$rs=$rs->etc;
			break;

		}

	return $rs;

	}

function coin_gubun_is($n){

		switch($n) {
			
			case "bitcoin":$rs="비트코인";
			break;
			case "eth":$rs="이더리움";
			break;
			case "dash":$rs="대쉬";
			break;
			case "litecoin":$rs="라이트코인";
			break;
			case "zcash":$rs="제트캐쉬";
			break;

		}

		return $rs;

	}


function confirm_status_is($n){

		switch($n) {
			
			case 0:$rs="신청완료";
			break;
			case 1:$rs="결제완료";
			break;
			case 2:$rs="등록완료";
			break;
			case 3:$rs="재작업지시";
			break;
			case 4:$rs="고객제시";
			break;
			case 5:$rs="선택완료";
			break;
			case 6:$rs="최종승인";
			break;
			case 7:$rs="배송중";
			break;
			case 8:$rs="배송완료";
			break;

		}

		return $rs;

	}



//샵코드
function shop_code_val($ccode,$shop_code){

	global $mysqli;
	$que="select SHOP_CODE,
	CASE
						 WHEN '$ccode' = 'ko'
						 THEN SHOP_NAME
						 WHEN '$ccode' = 'zh'
						 THEN SHOP_NAME_CH
						 WHEN '$ccode' = 'en'
						 THEN SHOP_NAME_EN
						 ELSE SHOP_NAME
						 END AS SHOP_NAME 
	from shop_code order by SHOP_CODE";
	$result = $mysqli->query($que) or die($mysqli->error);
	while($rs = $result->fetch_object()){
		$sel="";
		if($shop_code==$rs->SHOP_CODE)$sel=" selected";
		$val.="<option ".$sel." value='".$rs->SHOP_CODE."'>".$rs->SHOP_NAME."</option>";
	}

	return $val;

}

//샵코드
function shop_name_is($ccode,$shop_code){

	global $mysqli;
	$que="select SHOP_CODE,
	CASE
						 WHEN '$ccode' = 'ko'
						 THEN SHOP_NAME
						 WHEN '$ccode' = 'zh'
						 THEN SHOP_NAME_CH
						 WHEN '$ccode' = 'en'
						 THEN SHOP_NAME_EN
						 ELSE SHOP_NAME
						 END AS SHOP_NAME 
	from shop_code where SHOP_CODE='$shop_code'";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->SHOP_NAME;

}

//매장명
function seller_name_is($ccode,$SELLER_CODE){

	global $mysqli;
	$que="select SHOP_CODE,
	CASE
						 WHEN '$ccode' = 'ko'
						 THEN SNAME
						 WHEN '$ccode' = 'zh'
						 THEN SNAME_CH
						 WHEN '$ccode' = 'en'
						 THEN SNAME_EN
						 ELSE SNAME
						 END AS SNAME 
	from seller where SELLER_CODE='$SELLER_CODE'";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->SNAME;

}

//제품별 카테고리
function category_is($PRODUCT_ID){

	global $mysqli;
	$que="select CATEGORY_NAME,(select CATEGORY_NAME as CN from category_info where DEPTH='0' and left(CATEGORY_ID,3)=left(a.CATEGORY_ID,3)) as CN  from category_info a where CATEGORY_ID in (select CATEGORY_ID from category_relation where PRODUCT_ID='".$PRODUCT_ID."') limit 1";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	$cn=array($rs->CN,$rs->CATEGORY_NAME);
	return $cn;

}

//매장속성
function store_property_is($ccode,$sp){

	global $mysqli;
	$que="select STORE_PROPERTY_SEQ,
	CASE
						 WHEN '$ccode' = 'ko'
						 THEN PROPERTY_NAME
						 WHEN '$ccode' = 'zh'
						 THEN PROPERTY_NAME_CH
						 WHEN '$ccode' = 'en'
						 THEN PROPERTY_NAME_EN
						 ELSE PROPERTY_NAME
						 END AS PROPERTY_NAME 
	from store_property where PROPERTY_DEL='0' order by PROPERTY_NAME";
	$result = $mysqli->query($que) or die($mysqli->error);
	while($rs = $result->fetch_object()){
		$sel="";
		if($sp==$rs->STORE_PROPERTY_SEQ)$sel=" selected";
		$val.="<option ".$sel." value='".$rs->STORE_PROPERTY_SEQ."'>".$rs->PROPERTY_NAME."</option>";
	}

	return $val;

}

//매장속성
function store_property_val($ccode,$sp){

	global $mysqli;
	$que="select STORE_PROPERTY_SEQ,
	CASE
						 WHEN '$ccode' = 'ko'
						 THEN PROPERTY_NAME
						 WHEN '$ccode' = 'zh'
						 THEN PROPERTY_NAME_CH
						 WHEN '$ccode' = 'en'
						 THEN PROPERTY_NAME_EN
						 ELSE PROPERTY_NAME
						 END AS PROPERTY_NAME 
	from store_property where STORE_PROPERTY_SEQ='$sp'";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->PROPERTY_NAME;

}

//은행코드
function bank_code_is($bcode){

	global $mysqli;
	$que="select * 
	from bank_code order by BANK_NAME";
	$result = $mysqli->query($que) or die($mysqli->error);
	while($rs = $result->fetch_object()){
		$sel="";
		if($bcode==$rs->BANK_CODE)$sel=" selected";
		$val.="<option ".$sel." value='".$rs->BANK_CODE."'>".$rs->BANK_NAME."</option>";
	}

	return $val;

}

//상품썸네일
function thumb_is($PRODUCT_ID){
	
	global $mysqli;
	$que="select FILEPATH, FILENM_SYS
	from product_file_info  where PRODUCT_ID='".$PRODUCT_ID."' and FILE_ORDER='1' and IMGVOD_FLAG='0'";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->FILEPATH.$rs->FILENM_SYS;
}

//계약서비스상품
function service_is($SERVICE_ID){
	
	global $mysqli;
	$que="select SERVICE_PRODUCT_NAME 
	from service_product  where SERVICE_ID='".$SERVICE_ID."' ";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->SERVICE_PRODUCT_NAME;
}

//계약서비스상품
function service_name($seller_code){
	
	global $mysqli;
	$que="select SERVICE_PRODUCT_NAME, SERVICE_START_DATE, SERVICE_END_DATE, DURATION 
	from contract a, service_product b where a.SERVICE_ID=b.SERVICE_ID and SELLER_CODE='".$seller_code."' order by a.reg_date desc limit 1";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	$var=array($rs->SERVICE_PRODUCT_NAME, $rs->SERVICE_START_DATE, $rs->SERVICE_END_DATE, $rs->DURATION);
	return $var;
}


//셀러등록상품
function product_count($seller_code){
	
	global $mysqli;
	$que="select count(*) as cnt  
	from product where ADMIN='".$seller_code."'";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->cnt;
}

//찜한수 (일반회원 buyer);
function zzim_cnt_is($BUYER_CODE){

	global $mysqli;
	$que="select count(1) as cnt 
	from zzim_product where BUYER_CODE='".$BUYER_CODE."'";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->cnt;

}

//단골샵수 (일반회원 buyer);
function like_shop_cnt_is($BUYER_CODE){

	global $mysqli;
	$que="select count(1) as cnt 
	from like_shop where BUYER_CODE='".$BUYER_CODE."'";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->cnt;

}


function comm_title_is($multi){
	
	global $mysqli;
	$que="select multi_title from cboard_admin where multi='$multi'";
	$result = $mysqli->query($que) or die($mysqli->error);
	$rs = $result->fetch_object();
	return $rs->multi_title;
}


function auto_link($str) 
{ 
if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)) 
{ 
for ($i = 0; $i < count($matches['0']); $i++) 
{ 
$period = ''; 
if (preg_match("|\.$|", $matches['6'][$i])) 
{ 
$period = '.'; 
$matches['6'][$i] = substr($matches['6'][$i], 0, -1); 
} 

$str = str_replace($matches['0'][$i], 
$matches['1'][$i].'<a href="http'. 
$matches['4'][$i].'://'. 
$matches['5'][$i]. 
$matches['6'][$i].'" target="_blank">http'. 
$matches['4'][$i].'://'. 
$matches['5'][$i]. 
$matches['6'][$i].'</a>'. 
$period, $str); 
} 
} 

return $str; 
} 



function content_is($content){
	$content=stripslashes($content);
	$content=str_replace("<TBODY><br />","<TBODY>",$content);
	$content=str_replace("</TR><br />","</TR>",$content);
	$content=str_replace("<TR><br />","<TR>",$content);
	$content=str_replace("</TD><br />","</TD>",$content);
	$content = auto_link($content);
	$content = stripslashes($content);
	return $content;
}

function content_is2($content){
	$content=stripslashes(nl2br($content));
	$content=str_replace("<TBODY><br />","<TBODY>",$content);
	$content=str_replace("</TR><br />","</TR>",$content);
	$content=str_replace("<TR><br />","<TR>",$content);
	$content=str_replace("</TD><br />","</TD>",$content);
	$content=str_replace("<P>","<DIV>",$content);
	$content=str_replace("</P>","</DIV>",$content);
	$content=str_replace("<p>","<DIV>",$content);
	$content=str_replace("</p>","</DIV>",$content);
	$content=str_replace("<br />","",$content);
	$content=str_replace("<BR />","",$content);
	$content = auto_link($content);
	return $content;
}


function null_check($a,$txt){
		if(!trim($a)){
			echo "
				<script language=javascript>
					alert('$txt 넣어주세요.');
					history.back();
				</script>
				";
				exit;
		}
		return $a;
	}

function sns_is($n){

		switch($n) {

			case 1:$power="카카오톡";
			break;
			case 2:$power="위챗";
			break;
			case 3:$power="페이스북";
			break;

		}

		return $power;

	}


function newimg($a){
							if(substr($a,0,10)==date("Y-m-d") ){
								$b="<img src=/img/ico_new_01.gif border=0 align=absmiddle>";
							}
							return $b;
						}

function new_img($a){

						$reg_date=date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
							if(substr($a,0,10)>=$reg_date){
								$b="<img src=\"/img/ico_new_01.gif\" align=absmiddle>";
							}
							return $b;
						}

function new_img_7($a){

						$reg_date=date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-7, date("Y")));
							if(substr($a,0,10)>=$reg_date){
								$b="<img src=\"/home/img/ico_new_01.gif\" align=absmiddle>";
							}
							return $b;
						}

function new_img_14($a){

						$reg_date=date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-14, date("Y")));
							if(substr($a,0,10)>=$reg_date){
								$b="<img src=\"/home/img/ico_new_01.gif\" align=absmiddle style='display:inline;'>";
							}
							return $b;
						}


	function multi_title_is($multi){

		$list=mysql_query("select multi_title from kboard_admin where multi='$multi'") or die("db error");
		$ls=mysql_fetch_array($list);
		mysql_free_result($list);
		return $ls[0];

	}

	


function bank_is($bc){


		switch($bc) {

			case "01":$pm="한국";
			break;
			case "02":$pm="산업";
			break;
			case "03":$pm="기업";
			break;
			case "04":$pm="국민";
			break;
			case "05":$pm="외환";
			break;
			case "06":$pm="국민";
			break;
			case "07":$pm="수협";
			break;
			case "08":$pm="수출입";
			break;
			case "10":$pm="농협";
			break;
			case "11":$pm="농협";
			break;
			case "12":$pm="농협";
			break;
			case "13":$pm="농협";
			break;
			case "14":$pm="농협";
			break;
			case "15":$pm="농협";
			break;
			case "16":$pm="농협";
			break;
			case "17":$pm="농협";
			break;
			case "19":$pm="국민";
			break;
			case "20":$pm="우리";
			break;
			case "21":$pm="신한";
			break;
			case "22":$pm="우리";
			break;
			case "23":$pm="ＳＣ제일";
			break;
			case "24":$pm="우리";
			break;
			case "25":$pm="하나";
			break;
			case "26":$pm="신한";
			break;
			case "27":$pm="한국씨티";
			break;
			case "28":$pm="신한";
			break;
			case "29":$pm="국민";
			break;
			case "31":$pm="대구";
			break;
			case "32":$pm="부산";
			break;
			case "33":$pm="하나";
			break;
			case "34":$pm="광주";
			break;
			case "35":$pm="제주";
			break;
			case "36":$pm="한국씨티";
			break;
			case "37":$pm="전북";
			break;
			case "39":$pm="경남";
			break;
			case "45":$pm="새마을금고";
			break;
			case "46":$pm="새마을금고";
			break;
			case "48":$pm="신협";
			break;
			case "49":$pm="신협";
			break;
			case "50":$pm="상호저축은행";
			break;
			case "51":$pm="외국은행";
			break;
			case "53":$pm="한국씨티";
			break;
			case "54":$pm="HSBC(홍콩상하이은행)";
			break;
			case "55":$pm="도이치은행";
			break;
			case "56":$pm="에이비엔암로은행";
			break;
			case "58":$pm="미즈호코퍼레이트은행";
			break;
			case "59":$pm="미쓰비시도쿄UFJ은행";
			break;
			case "60":$pm="B. O. A";
			break;
			case "71":$pm="우체국";
			break;
			case "72":$pm="우체국";
			break;
			case "73":$pm="우체국";
			break;
			case "74":$pm="우체국";
			break;
			case "75":$pm="우체국";
			break;
			case "76":$pm="신용보증기금";
			break;
			case "77":$pm="기술신용보증기금";
			break;
			case "81":$pm="하나";
			break;
			case "82":$pm="하나";
			break;
			case "83":$pm="우리";
			break;
			case "84":$pm="우리";
			break;
			case "85":$pm="새마을금고";
			break;
			case "86":$pm="새마을금고";
			break;
			case "88":$pm="신한";
			break;
			case "95":$pm="경찰청";
			break;
			case "99":$pm="금융결제원";
			break;

		}

		return $pm;

	}



	function location_is($url,$option,$text){

		if($text and $url){
			echo "
			<script language=javascript>
				alert('$text');
				location.href='$url?$option';
			</script>
			";
		}else if(!$text and $url){
			echo "
			<script language=javascript>
				location.href='$url?$option';
			</script>
			";
		}else if($text and !$url){
			echo "
			<script language=javascript>
				alert('$text');
				history.back();
			</script>
			";
		}else if(!$text and !$url){
			echo "
			<script language=javascript>
				history.back();
			</script>
			";
		}

	}


	function location_is_reload($url,$option,$text){

		if($text and $url){
			echo "
			<script language=javascript>
				alert('$text');
				location.href='$url?$option';
				opener.location.reload();
			</script>
			";
		}else if(!$text and $url){
			echo "
			<script language=javascript>
				location.href='$url?$option';
			opener.location.reload();
			</script>
			";
		}else if($text and !$url){
			echo "
			<script language=javascript>
				alert('$text');
				opener.location.reload();
				window.close();
			</script>
			";
		}else if(!$text and !$url){
			echo "
			<script language=javascript>
				opener.location.reload();
				window.close();
				history.back();
			</script>
			";
		}

	}

	function location_is_close($text){

		if($text){
			echo "
			<script language=javascript>
				alert('$text');
				window.close();
				opener.location.reload();
			</script>
			";
		}else{
			echo "
			<script language=javascript>
				window.close();
			opener.location.reload();
			</script>
			";
		}

	}

	function location_is_direct($url,$option,$text){

		if($url){
			echo "
			<script language=javascript>
				window.close();
				opener.location.href='$url?$option';
			</script>
			";
		}else{
			echo "
			<script language=javascript>
				window.close();
			opener.location.reload();
			</script>
			";
		}

	}

	function window_close_is($text){

		if($text){
			echo "
			<script language=javascript>
				alert('$text');
				window.close();
			</script>
			";
		}else{
			echo "
			<script language=javascript>
				window.close();
			</script>
			";
		}

	}

function w_date($w){


		switch($w) {

		case 0:$w_date="<font color=red>일</font>";
		break;

		case 1:$w_date="월";
		break;

		case 2:$w_date="화";
		break;

		case 3:$w_date="수";
		break;

		case 4:$w_date="목";
		break;

		case 5:$w_date="금";
		break;

		case 6:$w_date="<font color=blue>토</font>";
		break;



		}

		return $w_date;

	}

function w_kanji_date($w){


		switch($w) {

		case 0:$w_date="日";
		break;

		case 1:$w_date="月";
		break;

		case 2:$w_date="火";
		break;

		case 3:$w_date="水";
		break;

		case 4:$w_date="木";
		break;

		case 5:$w_date="金";
		break;

		case 6:$w_date="土";
		break;



		}

		return $w_date;

	}


function _mulutiByteStrCut($str,$limit,$after_str){ 
        $_val = $str; 
        $_val  = mb_strcut( $_val,0,$limit); 
        if(strlen($str) > $limit ){ 
            $_val .= $after_str; 
        } 
        return $_val; 
    } 


	function stringcut( $String, $Length, $EndMark='' )
{
		$String=str_replace("\"","",$String);
		$String=str_replace("'","",$String);
       // 자를필요없으면 리턴
       if( strlen( stripslashes($String) ) <= $Length ) return stripslashes($String);

       for( $i=0; $i<strlen( $String ); $i++ )
       {
              //아스키코드 129 번부터는 2 Byte 문자
              //2 Byte 문자인경우 1 Byte 를 더 읽은 샘으로 침.
              if( ord( substr( $String, $i-1, $i ) ) > 128 )
              {
                      $i++;
                      $Length++;
              }
              //$Length 까지 왔을경우 리턴
              if( $i >= $Length )
                     return stripslashes(substr( $String, 0, $Length )).$EndMark;
       }
       // 자를필요가 없지만 글자수와 byte 수를 비교하지 못함으로
       // 루프를 다돌아도 리턴되지 않는다면 그냥 월래 문자열 return;
       return stripslashes($String);
}

function stringcututf($str, $len, $checkmb=false, $tail='...') {
    preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);
 
    $m    = $match[0];
	$str=stripslashes($str);
    $slen = strlen($str);  // length of source string
    $tlen = strlen($tail); // length of tail string
    $mlen = count($m); // length of matched characters
 
    if ($slen <= $len) return $str;
    if (!$checkmb && $mlen <= $len) return $str;
 
    $ret   = array();
    $count = 0;
 
    for ($i=0; $i < $len; $i++) {
        $count += ($checkmb && strlen($m[$i]) > 1)?2:1;
 
        if ($count + $tlen > $len) break;
        $ret[] = $m[$i];
    }
 
    return stripslashes(join('', $ret).$tail);
}

function stringcutname($str, $len, $checkmb=false, $tail='**') {
    preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);
 
    $m    = $match[0];
	$str=stripslashes($str);
    $slen = strlen($str);  // length of source string
    $tlen = strlen($tail); // length of tail string
    $mlen = count($m); // length of matched characters
 
    if ($slen <= $len) return $str;
    if (!$checkmb && $mlen <= $len) return $str;
 
    $ret   = array();
    $count = 0;
 
    for ($i=0; $i < $len; $i++) {
        $count += ($checkmb && strlen($m[$i]) > 1)?2:1;
 
        if ($count + $tlen > $len) break;
        $ret[] = $m[$i];
    }
 
    return stripslashes(join('', $ret).$tail);
}

function removeHackTag($content) 
    {

        // iframe 제거
        $content = preg_replace("!<iframe(.*?)<\/iframe>!is", '', $content);

        // script code 제거
        $content = preg_replace("!<script(.*?)<\/script>!is", '', $content);

        // meta 태그 제거
        $content = preg_replace("!<meta(.*?)>!is", '', $content);

        // style 태그 제거
        $content = preg_replace("!<style(.*?)<\/style>!is", '', $content);

        // XSS 사용을 위한 이벤트 제거
        $content = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeJSEvent, $content);

        /**
        * 이미지나 동영상등의 태그에서 src에 관리자 세션을 악용하는 코드를 제거
        * - 취약점 제보 : 김상원님
        **/
        $content = preg_replace_callback("!<([a-z]+)(.*?)>!is", removeSrcHack, $content);

        return $content;
    }

function removeJSEvent($matches) 
    {
        $tag = strtolower($matches[1]);
        if(preg_match('/(src|href)=("|\'?)javascript:/i',$matches[2])) $matches[0] = preg_replace('/(src|href)=("|\'?)javascript:/i','$1=$2_javascript:', $matches[0]);
        return preg_replace('/ on([a-z]+)=/i',' _on$1=',$matches[0]);
}

function removeSrcHack($matches){
        $tag = strtolower(trim($matches[1]));

        $buff = trim(preg_replace('/(\/>|>)/','/>',$matches[0]));
        $buff = preg_replace_callback('/([^=^"^ ]*)=([^ ^>]*)/i', fixQuotation, $buff);

        $oXmlParser = new XmlParser();
        $xml_doc = $oXmlParser->parse($buff);

        // src값에 module=admin이라는 값이 입력되어 있으면 이 값을 무효화 시킴
        $src = $xml_doc->{$tag}->attrs->src;
        $dynsrc = $xml_doc->{$tag}->attrs->dynsrc;
        if(_isHackedSrc($src) || _isHackedSrc($dynsrc) ) return sprintf("<%s>",$tag);

        return $matches[0];
    }

function _isHackedSrc($src) {
        if(!$src) return false;
        if($src && preg_match('/javascript:/i',$src)) return true;
        if($src) 
        {
            $url_info = parse_url($src);
            $query = $url_info['query'];
            $queries = explode('&', $query);
            $cnt = count($queries);
            for($i=0;$i<$cnt;$i++) 
            {
                $pos = strpos($queries[$i],'=');
                if($pos === false) continue;
                $key = strtolower(trim(substr($queries[$i], 0, $pos)));
                $val = strtolower(trim(substr($queries[$i] ,$pos+1)));
                if(($key == 'module' && $val == 'admin') || $key == 'act' && preg_match('/admin/i',$val)) return true;
            }
        }
        return false;
}

	?>

