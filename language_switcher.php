<style>
    .container {
        margin-right: auto;
        margin-left: auto;
        width: 960px;
        padding: 0;
    }

    .languages {
        background-color: #035d90;
        height: 55px;
    }

    .language-switcher {
        float: right;
        margin: 5px 0 0;
    }

    .languages .language-switcher li {
        display: block;
        float: left;
        padding-top: 1px;
        height: 22px;
    }

    .languages .language-switcher li a.selected {
        cursor: default;
    }

    .languages .language-switcher li a {
        display: block;
        width: 16px;
        height: 16px;
        padding: 3px 4px 0px 4px;
    }

    .languages .language-switcher li a img {
        display: block;
    }

    .languages .language-switcher li a:hover,
    .languages .language-switcher li a.selected {
        background: #dfdfdf;
        border-radius: 3px;
    }
    .ali-logo {
        display: inline-block;
        padding: 5px;
    }
    .ali-logo img {
        height: 45px;
    }
</style>
<div class="languages">
    <div class="container">
        <a href="#" class="ali-logo">
            <img src="/static/img/aliabc.png" alt="">
        </a>
        <ul class="language-switcher">
            <li>
                <a % href="/" class="selected">
                    <img src="/static/img/flags/en.png">
                </a>
            </li>
            <li>
                <a % href="/ru/">
                    <img src="/static/img/flags/ru.png">
                </a>
            </li>
            <li>
                <a % href="/ua/">
                    <img src="/static/img/flags/ua.png">
                </a>
            </li>
            <li>
                <a % href="/cn/">
                    <img src="/static/img/flags/cn.png">
                </a>
            </li>
        </ul>
    </div>
</div>