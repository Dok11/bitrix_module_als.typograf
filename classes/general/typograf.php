<?php

class ALSTypograf
{
	public static function Format(
		$data,
		$charset = 'UTF-8',
		$entityType = 4,
		$useBr = 0,
		$useP = 0,
		$maxNobr = 3,
		$quotA = 'laquo raquo',
		$quotB = 'bdquo ldquo'
	) {
		$result = $data;
		$serviceError = false;

		if(ToUpper($charset) != 'UTF-8') {
			$data = $GLOBALS['APPLICATION']->ConvertCharset($data, $charset, 'UTF-8');
		}

		if(CModule::IncludeModule('webservice')) {
			$resultTypo = ALSTypograf::artlebedev($data);

			if(substr($resultTypo, 0, 9) !== '<!DOCTYPE') {
				$result = $resultTypo;
			} else {
				$serviceError = true;
			}

		} else {
			$resultTypo = ALSTypograf::typograf($data);

			if(substr($resultTypo, 0, 9) !== '<!DOCTYPE') {
				$result = $resultTypo;
			} else {
				$serviceError = true;
			}

		}

		if($serviceError) {
			$typo = new ALSTypografLight();
			$result = $typo->getResult($data);
		}

		return $result;

	}

  static function addButtonName(&$items)
  {
    global $APPLICATION;

    if ($APPLICATION->GetCurPage() == "/bitrix/admin/iblock_element_edit.php") {
      $APPLICATION->SetAdditionalCSS('/local/modules/als.typograf/f/css/style.css');
    }

    $APPLICATION->AddHeadScript('/local/modules/als.typograf/f/js/add_htmleditor_button_typograf.js', true);
  }

  /**
   * //typograf.ru/webservice/
   */
  private function typograf($data)
  {
    $xml = '<?xml version="1.0" encoding="utf-8" ?>
<preferences>
	<!-- Теги -->
	<tags delete="0">1</tags>
	<!-- Абзацы -->
	<paragraph insert="1">
		<start><![CDATA[<p>]]></start>
		<end><![CDATA[</p>]]></end>
	</paragraph>
	<!-- Переводы строк -->
	<newline insert="1"><![CDATA[<br />]]></newline>
	<!-- Переводы строк <p>&nbsp;</p> -->
	<cmsNewLine valid="0" />
	<!-- DOS текст -->
	<dos-text delete="0" />
	<!-- Неразрывные конструкции -->
	<nowraped insert="1" nonbsp="0" length="0">
		<start><![CDATA[<nobr>]]></start>
		<end><![CDATA[</nobr>]]></end>
	</nowraped>
	<!-- Висячая пунктуация -->
	<hanging-punct insert="0" />
	<!-- Удалять висячие слова -->
	<hanging-line delete="0" />
	<!-- Символ минус -->
	<minus-sign><![CDATA[&ndash;]]></minus-sign>
	<!-- Переносы -->
	<hyphen insert="0" length="0" />
	<!-- Акронимы -->
	<acronym insert="1"></acronym>
	<!-- Вывод символов 0 - буквами 1 - числами -->
	<symbols type="0" />
	<!-- Параметры ссылок -->
	<link target="" class="" />
</preferences>
';
    return ALSTypograf::post('www.typograf.ru', '/webservice/', 'text=' . urlencode($data) . '&xml=' . urlencode($xml) . '&chr=UTF-8');
  }

  /**
   * //typograf.artlebedev.ru/webservices/
   */
  private function artlebedev($data)
  {
    $request = new CSOAPRequest("ProcessText", "http://typograf.artlebedev.ru/webservices/");

    $request->addParameter("text", htmlspecialchars($data));
    $request->addParameter("entityType", $entityType);
    $request->addParameter("useBr", $useBr);
    $request->addParameter("useP", $useP);
    $request->addParameter("maxNobr", $maxNobr);
    $request->addParameter("quotA", $quotA);
    $request->addParameter("quotB", $quotB);

    $client = new CSOAPClient('typograf.artlebedev.ru', '/webservices/typograf.asmx');
    $response = $client->send($request);

    return $response->Value['ProcessTextResult'];
  }


  /**
   * Функция возвращает результат post запроса
   * $host - хост сайта куда предполагается делать post запросы. Напр., имеется сайт
   *    http://www.typograf.ru/webservice/, хостом в данном случае является www.typograf.ru
   * $script - имя каталога или скрипта, который обрабатывает ваш post запрос.
   *    Для сайта http://www.typograf.ru/webservice/ обработчиком будет каталог /webservice/.
   * $data - это данные формата имя=значение, которые передаются для обработки. Для веб-сервиса
   *    http://www.typograf.ru/webservice/ необходимо передать значение переменной text, поэтому
   *    значение переменной $data будет text=текст для типографирования.
   */
  private function post($host, $script, $data)
  {

    $fp = fsockopen($host, 80, $errno, $errstr, 5);

    if ($fp) {
      fputs($fp, "POST $script HTTP/1.1\n");
      fputs($fp, "Host: $host\n");
      fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
      fputs($fp, "Content-length: " . strlen($data) . "\n");
      fputs($fp, "User-Agent: PHP Script\n");
      fputs($fp, "Connection: close\n\n");
      fputs($fp, $data);
      while (fgets($fp, 2048) != "\r\n" && !feof($fp)) ;
      unset($buf);
      while (!feof($fp)) $buf .= fread($fp, 2048);
      fclose($fp);
    } else {
      return "Сервер не отвечает";
    }
    return $buf;
  }
}
