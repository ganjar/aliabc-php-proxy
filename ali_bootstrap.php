<?php

use ALI\ALIAbc;
use ALI\Buffer\BufferTranslate;
use ALI\Buffer\PreProcessors\IgnoreHtmlTagsPreProcessor;
use ALI\Buffer\Processors\HtmlAttributesProcessor;
use ALI\Buffer\Processors\HtmlLinkProcessor;
use ALI\Helpers\UrlHelper;
use \ALI\Translate\Language\Language;
use \ALI\Buffer\Processors\HtmlTagProcessor;
use ALI\Translate\Language\LanguageInterface;
use ALI\Translate\OriginalProcessors\ReplaceNumbersOriginalProcessor;
use \ALI\Translate\Sources\CsvFileSource;
use ALI\Translate\Sources\SourceInterface;
use ALI\Translate\TranslateProcessors\ReplaceNumbersTranslateProcessor;

$originalLang = getenv('LANGUAGE_ORIGINAL');
$allLanguages = explode(',', getenv('LANGUAGES'));

//Yandex translate
$yaTranslate = new \Yandex\Translate\Translator(getenv('YANDEX_TRANSLATE_API_KEY'));

//Set translation source
$source = new CsvFileSource(__DIR__ . '/lng/', ",", 'csv');

$aliFactory = new AliFactory($allLanguages, $originalLang);

$translateCallback = function ($phrase, \ALI\Translate\Translate $translate) use ($yaTranslate, $originalLang) {
    $translatedPhrase = $yaTranslate->translate($phrase,
        $originalLang . '-' . $translate->getLanguage()->getAlias());
    $translate->getSource()->saveTranslate($translate->getLanguage(), $phrase, $translatedPhrase);

    return $translatedPhrase;
};

return $aliFactory->makeAliUsingURLDetection($source, $translateCallback);

class AliFactory
{

    protected $requestURI;
    protected $httpHost;
    protected $allLanguagesAliases;
    protected $originalLangAlias;

    /**
     * AliFactory constructor.
     * @param array  $allLanguagesAliases
     * @param string $originalLangAlias
     * @param string $httpHost
     * @param string $requestURI
     */
    public function __construct(array $allLanguagesAliases, $originalLangAlias, $httpHost = null, $requestURI = null)
    {
        if (is_null($httpHost) && isset($_SERVER['HTTP_HOST'])) {
            $httpHost = $_SERVER['HTTP_HOST'];
        }

        if (is_null($requestURI) && isset($_SERVER['REQUEST_URI'])) {
            $requestURI = $_SERVER['REQUEST_URI'];
        }

        $this->httpHost = $httpHost;
        $this->requestURI = $requestURI;
        $this->allLanguagesAliases = $allLanguagesAliases;
        $this->originalLangAlias = $originalLangAlias;
    }

    /**
     * @param LanguageInterface $language
     * @param SourceInterface   $source
     * @param Closure|null      $missingTranslationCallback
     * @return ALIAbc
     * @throws \ALI\Exceptions\TranslateNotDefinedException
     */
    public function makeAli(LanguageInterface $language, SourceInterface $source, Closure $missingTranslationCallback = null)
    {
        $ali = new ALIAbc();
        $ali->setTranslate(
            $this->makeTranslate($language, $source, $missingTranslationCallback)
        );
        $ali->setBufferTranslate(
            $this->makeBufferTranslate($ali->getTranslate())
        );

        return $ali;
    }

    /**
     * @param SourceInterface $source
     * @param Closure|null    $missingTranslationCallback
     * @return ALIAbc
     * @throws \ALI\Exceptions\TranslateNotDefinedException
     */
    public function makeAliUsingURLDetection(SourceInterface $source, Closure $missingTranslationCallback = null)
    {
        $urlHelper = new UrlHelper($this->allLanguagesAliases, $this->requestURI);
        $languageAlias = $urlHelper->getLangAliasFromURI();
        //todo - use language registry
        $title = '';
        $language = $this->makeLanguage($languageAlias, $title);

        //Delete language from REQUEST_URI
        $_SERVER['REQUEST_URI'] = $urlHelper->getRequestUriWithoutLangAlias();

        return $this->makeAli($language, $source, $missingTranslationCallback);
    }

    /**
     * @param $languageAlias
     * @param $title
     * @return Language
     */
    public function makeLanguage($languageAlias, $title)
    {
        return new Language($languageAlias, $title, $languageAlias == $this->originalLangAlias || !$languageAlias);
    }

    /**
     * @param LanguageInterface $language
     * @param SourceInterface   $source
     * @param Closure|null      $missingTranslationCallback
     * @return \ALI\Translate\Translate
     */
    protected function makeTranslate(LanguageInterface $language, SourceInterface $source, Closure $missingTranslationCallback = null)
    {
        $translate = new \ALI\Translate\Translate($language, $source, $missingTranslationCallback);
        $translate->addOriginalProcessor(new ReplaceNumbersOriginalProcessor());
        $translate->addTranslateProcessor(new ReplaceNumbersTranslateProcessor());

        return $translate;
    }

    /**
     * @param \ALI\Translate\Translate $translate
     * @return BufferTranslate
     * @throws \ALI\Exceptions\TranslateNotDefinedException
     */
    protected function makeBufferTranslate(\ALI\Translate\Translate $translate)
    {
        $bufferTranslate = new BufferTranslate($translate);
        $bufferTranslate->addPreProcessor(new IgnoreHtmlTagsPreProcessor(['script', 'style']));
        $bufferTranslate->addProcessor(new HtmlTagProcessor());
        $bufferTranslate->addProcessor(new HtmlAttributesProcessor(['alt', 'title', 'placeholder', 'content']));

        //Add buffer processor for replace language in URLs
        $linkProcessor = new HtmlLinkProcessor($this->httpHost);
        $bufferTranslate->addProcessor($linkProcessor);

        return $bufferTranslate;
    }
}