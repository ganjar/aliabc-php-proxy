<?php

use ALI\ALIAbc;
use ALI\Buffer\BufferTranslate;
use ALI\Event;
use \ALI\Translate\Language\Language;
use \ALI\Buffer\Processors\HtmlTagProcessor;
use ALI\Translate\OriginalProcessors\TrimSpacesOriginalProcessor;
use \ALI\Translate\Sources\CsvFileSource;

//Set translation source - from file with || delimiter (original||translate)
$source = new CsvFileSource(__DIR__ . '/lng/', ",", 'csv');

//Parse language
$languageAlias = false;
if (preg_match('#^/(?<language>\w{2})(?:/|\Z)#', $_SERVER['REQUEST_URI'], $parseUriMatches)) {
    $languageAlias = $parseUriMatches['language'];
}

//Set language
$language = new Language($languageAlias, '', $languageAlias == 'en' || !$languageAlias);

//Make Translate instance
$translate = new \ALI\Translate\Translate(
    $language,
    $source,
    new Event()
);
$translate->addOriginalProcessor(new TrimSpacesOriginalProcessor());

//BufferTranslate
$bufferTranslate = new BufferTranslate($translate);
$bufferTranslate->addProcessor(new HtmlTagProcessor());

//Add buffer processor for parse phrases in custom tags
//$bufferTranslate->addProcessor(new CustomTagProcessor('[[', ']]'));

//Add buffer processor for replace language in URLs
//$bufferTranslate->addProcessor(new HtmlLinkProcessor());

$ali = new ALIAbc();
$ali->setTranslate($translate);
$ali->setBufferTranslate($bufferTranslate);

//events
$ali->getEvent()->on(Event::EVENT_MISSING_TRANSLATION, function ($phrase, \ALI\Translate\Translate $translate) use ($aliTranslateSource) {
    //$translate->getSource()->saveTranslate($translate->getLanguage(), $phrase, '');
    //$translate->getSource()->saveToTranslateQueue($phrase);
    //$aliTranslateSource->insertOriginal($phrase);
});

//Use buffers
$ali->iniSourceBuffering();

//start/end
/*$ali->getBuffer()->start();
echo '<b>[[Hello word]]</b>';
$ali->getBuffer()->end();

echo 'Untranslated: <b>[[Currency pairs]]</b>';

$ali->getBuffer()->start();
echo '<b>[[Currency pairs]]</b>';
$ali->getBuffer()->end();

//simple add
echo $ali->getBuffer()->add('<b>Hello word 3</b>');
//fail
$ali->getBuffer()->add('<b>Hello word 4</b>');

echo $ali->getBuffer()->buffering(function () {
    echo '<b>Hello word 4</b>';
});*/

//buffering all content
$ali->getBuffer()->start();

//Fast translate
//echo $ali->getTranslate()->translate('Hello word');
//Save translate
//$ali->getTranslate()->saveTranslate($language, 'Hello word', 'Привет мир');
//$ali->getTranslate()->delete('Hello word');

return $ali;