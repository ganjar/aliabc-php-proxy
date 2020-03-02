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
        height: 55px;
    }

    #ali-lang-switcher-container .lang-switcher {
        float: right;
        margin: 10px 0 0 0 ;
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
        padding: 2px;
        border-radius: 5px;
        margin-right: 3px;
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
            <li>
                <a % href="/" class="selected">
                    <img src="/static/img/flags-big/en.png">
                </a>
            </li>
            <li>
                <a % href="/ru/">
                    <img src="/static/img/flags-big/ru.png">
                </a>
            </li>
            <li>
                <a % href="/ua/">
                    <img src="/static/img/flags-big/ua.png">
                </a>
            </li>
            <li>
                <a % href="/zh/">
                    <img src="/static/img/flags-big/cn.png">
                </a>
            </li>
        </ul>
    </div>
</div>