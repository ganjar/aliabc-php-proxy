<?php

use ALI\ALIAbc;
use ALI\Buffer\BufferTranslate;
use ALI\Buffer\PreProcessors\IgnoreHtmlTagsPreProcessor;
use ALI\Buffer\Processors\HtmlAttributesProcessor;
use ALI\Buffer\Processors\HtmlLinkProcessor;
use \ALI\Translate\Language\Language;
use \ALI\Buffer\Processors\HtmlTagProcessor;
use ALI\Translate\OriginalProcessors\HtmlEntityDecodeOriginalProcessor;
use ALI\Translate\OriginalProcessors\ReplaceNumbersOriginalProcessor;
use \ALI\Translate\Sources\CsvFileSource;
use ALI\Translate\TranslateProcessors\ReplaceNumbersTranslateProcessor;

$originalLang = getenv('LANGUAGE_ORIGINAL');
$allLanguages = explode(',', getenv('LANGUAGES'));

//Set translation source
$source = new CsvFileSource(__DIR__ . '/lng/', ",", 'csv');

//Parse language
$languageAlias = false;
if (preg_match('#^/(?<language>\w{2})(?:/|\Z|\?)#', $_SERVER['REQUEST_URI'], $parseUriMatches)) {
    $languageAlias = $parseUriMatches['language'];
}
if ($languageAlias && !in_array($languageAlias, $allLanguages, true)) {
    $languageAlias = $originalLang;
}

//Set language
$language = new Language($languageAlias, '', $languageAlias == $originalLang || !$languageAlias);

//Yandex translate
$yaTranslate = new \Yandex\Translate\Translator(getenv('YANDEX_TRANSLATE_API_KEY'));

//Make Translate instance
$translate = new \ALI\Translate\Translate(
    $language,
    $source,
    function ($phrase, \ALI\Translate\Translate $translate) use ($yaTranslate, $originalLang) {
        $translatedPhrase = $yaTranslate->translate($phrase, $originalLang . '-' . $translate->getLanguage()->getAlias());
        $translate->getSource()->saveTranslate($translate->getLanguage(), $phrase, $translatedPhrase);

        return $translatedPhrase;
    }
);
$translate->addOriginalProcessor(new ReplaceNumbersOriginalProcessor());
$translate->addOriginalProcessor(new HtmlEntityDecodeOriginalProcessor());
$translate->addTranslateProcessor(new ReplaceNumbersTranslateProcessor());

//BufferTranslate
$bufferTranslate = new BufferTranslate($translate);
$bufferTranslate->addPreProcessor(new IgnoreHtmlTagsPreProcessor(['script', 'style']));
$bufferTranslate->addProcessor(new HtmlTagProcessor());
$bufferTranslate->addProcessor(new HtmlAttributesProcessor(['alt', 'title', 'placeholder', 'content']));

//Add buffer processor for replace language in URLs
$linkProcessor = new HtmlLinkProcessor($_SERVER['HTTP_HOST']);
$bufferTranslate->addProcessor($linkProcessor);

$ali = new ALIAbc();
$ali->setTranslate($translate);
$ali->setBufferTranslate($bufferTranslate);

//Delete language from REQUEST_URI
$_SERVER['REQUEST_URI'] = preg_replace('#^/' . $language->getAlias() . '(?:/|\Z|(\?))#Us', '/$1', $_SERVER['REQUEST_URI']);

return $ali;