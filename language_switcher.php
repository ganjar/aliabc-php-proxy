<?php
/** @var ALI\Translation\ALIAbc $ali */

use ALI\Translation\Url\UrlParserFactory;

$currentUrl = $_SERVER['REQUEST_URI'];

$urlParserFactory = new UrlParserFactory(
        $ali->getTranslator()->getSource()->getOriginalLanguageAlias(),
        $ali->getLanguageRepository()
);
$urlParser = $urlParserFactory->createParser();
?>
<style>
    #ali-lang-switcher-container::before,
    #ali-lang-switcher-container::after,
    #ali-lang-switcher-container *::before,
    #ali-lang-switcher-container *::after {
        all: unset;
    }

    #ali-lang-switcher-container a,
    #ali-lang-switcher-container ul,
    #ali-lang-switcher-container li,
    #ali-lang-switcher-container img {
        all: initial;
    }

    #ali-lang-switcher-container .container {
        margin-right: auto;
        margin-left: auto;
        width: 960px;
        padding: 0;
    }

    #ali-lang-switcher-container {
        background-color: #035d90;
        height: 57px;
    }

    #ali-lang-switcher-container .lang-switcher {
        float: right;
        margin: 10px 0 0 0;
    }

    #ali-lang-switcher-container .lang-switcher li {
        display: block;
        float: left;
        padding-top: 1px;
        height: 22px;
    }

    #ali-lang-switcher-container .lang-switcher li a.selected {
        cursor: default;
    }

    #ali-lang-switcher-container .lang-switcher li a {
        display: block;
        padding: 2px 5px;
        border-radius: 5px;
        margin-right: 3px;
        font-size: 25px;
        color: #FFFFFF;
    }

    #ali-lang-switcher-container .lang-switcher li a img {
        display: block;
        height: 30px;
    }

    #ali-lang-switcher-container .lang-switcher li a:hover,
    #ali-lang-switcher-container .lang-switcher li a:hover img {
        cursor: pointer;
    }

    #ali-lang-switcher-container .lang-switcher li a:hover,
    #ali-lang-switcher-container .lang-switcher li a.selected {
        background: #FFF;
        color: #000000;
    }

    #ali-lang-switcher-container .ali-logo {
        display: inline-block;
        padding: 5px;
    }

    #ali-lang-switcher-container .ali-logo img {
        height: 47px;
    }
</style>
<div id="ali-lang-switcher-container">
    <div class="container">
        <a href="#" class="ali-logo">
            <img src="/static/img/aliabc.png" alt="">
        </a>
        <ul class="lang-switcher">
            <?php foreach ($ali->getLanguageRepository()->getAll() as $language) { ?>
                <li>
                    <a %
                       href="<?=htmlspecialchars($urlParser->generateUrlWithLanguageAlias($currentUrl, $language->getAlias()), ENT_QUOTES);?>" <?= ($language->getAlias() === $ali->getCurrentLanguageAlias() ? 'class="selected"' : '') ?>>
                        <?=htmlspecialchars($language->getAlias());?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>