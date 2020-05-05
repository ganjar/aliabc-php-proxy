<?php

use ALI\Translation\Helpers\QuickStart\ALIAbcFactory;
use ALI\Translation\Languages\Repositories\Factories\ArrayLanguageRepositoryFactory;
use ALI\Translation\Url\UrlParserFactory;
use Yandex\Translate\Translator;

$originalLang = getenv('LANGUAGE_ORIGINAL');
$allLanguages = explode(',', getenv('LANGUAGES'));
$allLanguages = array_combine($allLanguages, $allLanguages);

//Yandex translate
$yaTranslate = new Translator(getenv('YANDEX_TRANSLATE_API_KEY'));

$langRepositoryFactory = new ArrayLanguageRepositoryFactory();
$langRepository = $langRepositoryFactory->createArrayLanguageRepository($allLanguages);
$urlParser = new UrlParserFactory($originalLang, $langRepository);
$currentLanguage = $urlParser->createUrlLanguageResolver()->resolveUrlCurrentLanguage();
$aliFactory = new ALIAbcFactory();
$ali = $aliFactory->createALIByHtmlBufferCsvSource(__DIR__ . '/lng/', $originalLang, $currentLanguage);

$translateCallback = function ($phrase, ALI\Translation\Translate\Translators\Translator $translate) use (
    $yaTranslate,
    $originalLang
) {
    $translatedPhrase = $yaTranslate->translate($phrase,
        $originalLang . '-' . $translate->getLanguageAlias());
    $translate->getSource()->saveTranslate($translate->getLanguageAlias(), $phrase, $translatedPhrase);

    return $translatedPhrase;
};
$ali->getTranslator()->addMissingTranslationCallback($translateCallback);

return $ali;
