<?php

namespace scbit\cookieconsent;

/**
 * Поисковые роботы: тот же HTML, без оверлея.
 * Не путать с браузером Яндекса — в шаблоне нет голого «Yandex».
 */
class SearchCrawler
{
    const PATTERN = '/Googlebot|Google-InspectionTool|GoogleOther|Google-Extended|YandexBot|YandexWebmaster|YandexRenderResourcesBot|YandexImages|YandexVideo|YandexMedia|YandexMobileBot|bingbot|BingPreview|DuckDuckBot|Mail\.RU_Bot|Applebot|Baiduspider/i';

    /**
     * @param string|null $userAgent
     * @return bool
     */
    public static function isSearchCrawler($userAgent = null)
    {
        if ($userAgent === null) {
            $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? (string) $_SERVER['HTTP_USER_AGENT'] : '';
        }

        return $userAgent !== '' && preg_match(self::PATTERN, $userAgent) === 1;
    }
}
