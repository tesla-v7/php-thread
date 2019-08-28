<?php

class Parser{
	private static $title = '<td class="resultsbottomBorder resultspadding" id="[\w-]+?_C1_R\d+">\n\s*<a.*?>\n\s*(?P<title>.*)[\n\s]+?<\/a>[\n\s]+?<\/td>';
	private static $product = '<td class="resultsbottomBorder resultspadding" id="[\w-]+?_C2_R\d+">\n(?P<product>.*)[\n\s]+?<\/td>';
	private static $productClass = '<td class="resultsbottomBorder resultspadding" id="[\w-]+?_C3_R\d+">\n(?P<class>.*)[\n\s]+?<\/td>';
	private static $dateUpdate = '<td class="resultsbottomBorder resultspadding\s?" id="[\w-]+?_C4_R\d+">\n(?P<data>.*)[\n\s]+?<\/td>';
	private static $version = '<td class="resultsbottomBorder resultspadding\s?" id="[\w-]+?_C5_R\d+">\n(?P<version>.*)[\n\s]+?<\/td>';
	private static $size = '<td class="resultsbottomBorder resultspadding resultsSizeWidth" id="[\w-]+?_C6_R\d+">\n\s*<span id=".*">.*<\/span>\s\n\s*<span class="noDisplay" id=".*">(?P<size>\d*)<\/span>';
	private $parseResult = [];
	private $names = ['title', 'product', 'class', 'data', 'version', 'size'];
	private $glue = ' <=> ';

	public function getResult(string $body){
		preg_match_all($this->getPattern(), $body, $this->parseResult);
		return $this->buildResult();
	}

	public function getNextUrl(){
	    return null;
    }

	private function getPattern(){
		return '/'. self::$title. self::$product . self::$productClass . self::$dateUpdate . self::$version . self::$size .'/';
	}

	private function buildResult(){
		$lines = count($this->parseResult['title']);
		$result = [];
		for ($i=0; $i < $lines; $i++) {
            $result[] = implode($this->glue, array_map(function($name)use($i){return trim($this->parseResult[$name][$i]);}, $this->names));
		}
		return implode("\n", $result);
	}
}