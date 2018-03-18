<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

error_reporting(E_ALL & ~E_NOTICE);
define('LIMIT', 10); // time limit
define('START_TIME', time());

if(isset($_REQUEST["bitrix_sessid"])) 
  bitrix_sessid_set($_REQUEST["bitrix_sessid"]);
  


$STEP = intval($_REQUEST['step']);
if (!$STEP)
	$STEP = 0;

if($STEP==0){
  if(version_compare(phpversion(), '5.0.0') == -1)  
	  Error('PHP 5.0.0 or higher is required!', 1);
	if (!function_exists('mb_convert_encoding'))
		Error('Не доступна библиотека mbstring', 2);
    
	$arResult["func_overload"] = (int)ini_get("mbstring.func_overload");
  $arResult["internal_encoding"] = strtoupper(ini_get("mbstring.internal_encoding"));
  
  if(!($arResult["func_overload"] == 2 && $arResult["internal_encoding"] =="UTF-8")){ 
    Error("<b>Ошибки настройки сервера!</b> Обратитесь в службу поддержки хостинга или к Вашему администратору, чтобы изменить настройки для PHP:<br><br>                   
          <ul>
            <li>mbstring.func_overload = 2</li>
            <li>mbstring.internal_encoding = UTF-8</li>
          </ul>
          <br />
          На данный момент сервер настроен так:<br />
          <ul>
            <li>mbstring.func_overload = ".$arResult["func_overload"]."</li>
            <li>mbstring.internal_encoding = ".$arResult["internal_encoding"]."</li>
          </ul>       
        ",3);
  }	
              

        
  //If try to autochange
  if($_REQUEST["code_error"]==4){
    $arError = false;             
    if(BX_UTF !== true){ 
      $dbconn = $_SERVER["DOCUMENT_ROOT"].BX_ROOT."/php_interface/dbconn.php";
      if(file_exists($dbconn)){
      	$arFile = file($dbconn);
      	foreach($arFile as $line){  
      			$strFile.=$line;
      	}
        $strFile = str_replace("?".">", 'define("BX_UTF", true);'."\n?".">", $strFile);
      	$f = fopen($dbconn,"wb");
      	fputs($f,$strFile);
      	fclose($f);        
      }   
    }
    Respone("Константа BX_UTF определена в true", 0); 
  }    
	if (!defined('BX_UTF') || BX_UTF !== true)
		Error('Константа BX_UTF не определена в true в /bitrix/php_interface/dbconn.php: <br><br><i>define("BX_UTF", true);</i><br><br> Вы можете изменить эту настройку вручную или нажать на кнопку "Изменить автоматически". (Рекомендуем нажать на кнопку "Изменить автоматически")', 4);
  
  //Go to next steep
  Respone("Начинаем конвертацию файлов...",$STEP+1);  
}elseif($STEP==1){  
	define('START_PATH', $_SERVER['DOCUMENT_ROOT']); // стартовая папка для поиска
  if ($_REQUEST['break_point']!="") 
    define('SKIP_PATH',$_REQUEST['break_point']); // промежуточный путь

	Search(START_PATH);
  if(defined('BREAK_POINT')){
    Respone("Идёт обработка файлов...", $STEP, htmlspecialchars(BREAK_POINT));    
	}else{
    Respone("Файлы изменены, меняем кодировку базы данных.", $STEP+1);
	}
}elseif($STEP==2){

  $res = $DB->Query('SHOW VARIABLES LIKE "character_set_results"');
  $f = $res->Fetch();
  if(strtolower($f['Value']) != 'cp1251'){		
    Error('Ошибка: База не нуждается в смене кодировки. <br />Рекомендации: Нажмите "Повторить попытку". Если ошибка повторится - пропустите этот шаг.', 5);      
  }
    
	if ($_REQUEST['break_point']) 
    define('SKIP_PATH',$_REQUEST['break_point']); // промежуточный путь

	$res = $DB->Query('SHOW TABLES');
  while($f = $res->Fetch()){
    $table = $f['Tables_in_'.$DBName];
		if (defined('SKIP_PATH') && !defined('FOUND')){
      if ($table == SKIP_PATH)
        define('FOUND', true);
			else
				continue;
		}
		$DB->Query('ALTER TABLE `'.$table.'` CHARACTER SET utf8 COLLATE utf8_unicode_ci');
		$res0 = $DB->Query('SHOW FULL COLUMNS FROM `'.$table.'`');
		while($f0 = $res0->Fetch()){
      if (false!==strpos($f0['Type'],'char') || false!==strpos($f0['Type'],'text')){
        $Collation = strpos($f0['Collation'], '_bin') ? 'utf8_bin' : 'utf8_unicode_ci';
				$q = 'ALTER TABLE `'.$table.'` MODIFY `'.$f0['Field'].'` '.$f0['Type'].' CHARACTER SET utf8 COLLATE '.$Collation.($f0['Null']!='YES'?' NOT ':'').' NULL '.($f0['Default'] === NULL ? ($f0['Null'] == 'YES' ? 'DEFAULT NULL' : '') : 'DEFAULT "'.$DB->ForSql($f0['Default']).'"');
				$DB->Query($q);
				if (time() - START_TIME > LIMIT){
          if (!defined('BREAK_POINT'))
            define('BREAK_POINT', $table);
					break;
				}
			}
		}
		if (defined('BREAK_POINT'))
		  break;
	}

  $dbconn = $_SERVER["DOCUMENT_ROOT"].BX_ROOT."/php_interface/after_connect.php";
  if(file_exists($dbconn)){        
    $arFile = file($dbconn);
		foreach($arFile as $line){        
      $line = str_replace("cp1251", "utf8", $line);
      $line = str_replace("?".">", '$DB->Query(\'SET collation_connection = "utf8_unicode_ci"\');'."\n".'?'.'>', $line);
      $strFile.=$line;
		}  
	  $f = fopen($dbconn,"wb");
    fputs($f,$strFile);
		fclose($f);      
	}else{      
    Error("Не удалось прописать '".'$DB->Query("SET NAMES \'utf8\'");'."'", 6);    
  } 
  	
  if(defined('BREAK_POINT')){
    Respone("Идёт конвертация БД", $STEP, htmlspecialchars(BREAK_POINT));  
	}else{
		$DB->Query('ALTER DATABASE `'.$DBName.'` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    Respone("Конвертация БД прошла успешно", $STEP+1);          
	}
  
}elseif($STEP==3){

  $obSite = new CSite;
  $rsSites = CSite::GetList( $by="sort", $order="desc");
  while ($arSite = $rsSites->Fetch()){
    $obSite->Update($arSite["ID"], array("CHARSET"=>"utf-8"));      
  }
  
  $obLang = new CLanguage;
  $rsLang = CLanguage::GetList($by="sort", $order="desc", Array("ID" => "RU"));
  while ($arLang = $rsLang->Fetch()){
    $obLang->Update($arLang["ID"], array("CHARSET"=>"utf-8"));  
  }
      
  Respone("Настройки сайта изменены", $STEP+1);  
}elseif($STEP==4){


  Respone("Смена кодировки прошла успешно", $STEP+1);  
}


function Search($path){
	if (time() - START_TIME > LIMIT)
	{
		if (!defined('BREAK_POINT'))
			define('BREAK_POINT', $path);
		return;
	}

	if (defined('SKIP_PATH') && !defined('FOUND')) // проверим, годится ли текущий путь
	{
		if (0!==strpos(SKIP_PATH, dirname($path))) // отбрасываем имя или идём ниже 
			return;

		if (SKIP_PATH==$path) // путь найден, продолжаем искать текст
			define('FOUND',true);
	}
  
  if(__DIR__ == $path)
    return true;  

	if (is_dir($path)) // dir
	{
		$dir = opendir($path);
		while($item = readdir($dir))
		{
			if ($item == '.' || $item == '..')
				continue;

			Search($path.'/'.$item);
		}
		closedir($dir);
	}
	else // file
	{
		if (!defined('SKIP_PATH') || defined('FOUND'))
		{
			if ((substr($path,-3) == '.js' || substr($path,-4) == '.php' || basename($path) == 'trigram') && $path != __FILE__){
				Process($path);
      }
		}
	}
}

function Process($file)
{
	global $STEP;

	if (!is_writable($file))
		Error('Файл не доступен на запись: '.$file);

	$content = file_get_contents($file);
  
	if (GetStringCharset($content) != 'cp1251')
		return;

	if ($content === false)
		Error('Не удалось прочитать файл: '.$file);

	if (file_put_contents($file, mb_convert_encoding($content, 'utf8', 'cp1251')) === false)
		Error('Не удалось сохранить файл: '.$file);

}

function Respone($str, $steep=0, $break_point = ""){
  $arResult = array(
    "RES" => $str,
    "STEEP" => $steep,
    "error" => "",
    "break_point" => $break_point,  
  );
  echo json_encode($arResult);
  exit;
}

function GetStringCharset($str)
{ 
	global $APPLICATION;
	if (preg_match("/[\xe0\xe1\xe3-\xff]/",$str))
		return 'cp1251';
	$str0 = $APPLICATION->ConvertCharset($str, 'utf8', 'cp1251');
	if (preg_match("/[\xe0\xe1\xe3-\xff]/",$str0,$regs))
		return 'utf8';
	return 'ascii';
}

function Error($text, $code=1){
  echo json_encode(array("error"=>$text, "code"=>$code));
  exit;
}
?>