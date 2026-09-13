<?php

namespace scbit\cookieconsent;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Согласие на cookie по 152-ФЗ.
 *
 * banner — плашка внизу, сайт сразу читается.
 * overlay — оверлей на весь экран, пока человек не нажмёт одну из двух кнопок.
 * После «Только нужные» сайт открывается, Метрика молчит. После «Принять все» — Метрика.
 * Закрывать сайт за отказ от аналитики нельзя.
 */
class CookieConsentWidget extends Widget
{
    const MODE_BANNER = 'banner';
    const MODE_OVERLAY = 'overlay';

    /**
     * banner|overlay. Если заданы overlayPaths — overlay только на этих путях.
     * @var string
     */
    public $mode = self::MODE_BANNER;

    /**
     * Префиксы пути, где нужен оверлей: blog, catalog, news.
     * Пусто + mode=overlay — оверлей на всём сайте (кроме freePaths).
     * @var string[]
     */
    public $overlayPaths = [];

    /**
     * Пути без оверлея: политика и /cookies должны читаться до выбора.
     * @var string[]
     */
    public $freePaths = [];

    /** @var string|array */
    public $privacyUrl = ['/site/privacy'];

    /** @var string|array|null */
    public $cookiesUrl = ['/site/cookies'];

    /** @var string */
    public $privacyLinkText = 'Политике конфиденциальности';

    /** @var string */
    public $cookiesLinkText = 'описании cookie';

    /**
     * @var string|null Плейсхолдеры {privacy} {cookies}
     */
    public $text;

    /** @var string */
    public $acceptLabel = 'Принять все';

    /** @var string */
    public $rejectLabel = 'Только нужные';

    public function run()
    {
        CookieConsentAsset::register($this->view);

        $mode = $this->resolveMode();

        $privacyLink = Html::a($this->privacyLinkText, $this->privacyUrl, [
            'target' => '_blank',
            'rel' => 'noopener',
        ]);
        $cookiesLink = $this->cookiesUrl
            ? Html::a($this->cookiesLinkText, $this->cookiesUrl)
            : '';

        $text = $this->text;
        if ($text === null) {
            $text = 'На сайте стоят cookie. Нужные — чтобы открывались страницы и работали формы.'
                . ' Аналитические (Яндекс.Метрика) — чтобы понимать, как сайтом пользуются.'
                . ' Подробности — в {privacy}';
            $text .= $cookiesLink ? ' и в {cookies}.' : '.';
        }

        $text = strtr($text, [
            '{privacy}' => $privacyLink,
            '{cookies}' => $cookiesLink,
        ]);

        return $this->render('banner', [
            'mode' => $mode,
            'text' => $text,
            'acceptLabel' => $this->acceptLabel,
            'rejectLabel' => $this->rejectLabel,
        ]);
    }

    /**
     * @return string
     */
    protected function resolveMode()
    {
        if (SearchCrawler::isSearchCrawler() || $this->isFreePage()) {
            return self::MODE_BANNER;
        }
        if ($this->overlayPaths) {
            return $this->pathMatches($this->overlayPaths) ? self::MODE_OVERLAY : self::MODE_BANNER;
        }

        return $this->mode === self::MODE_OVERLAY ? self::MODE_OVERLAY : self::MODE_BANNER;
    }

    /**
     * @return bool
     */
    protected function isFreePage()
    {
        $free = array_merge(
            ['cookies', 'site/cookies', 'privacy', 'site/privacy'],
            $this->freePaths
        );
        if ($this->cookiesUrl) {
            $free[] = $this->urlToPath($this->cookiesUrl);
        }
        if ($this->privacyUrl) {
            $free[] = $this->urlToPath($this->privacyUrl);
        }

        return $this->pathMatches($free, false);
    }

    /**
     * @param string[] $prefixes
     * @param bool $asPrefix oto совпадает с oto и oto/punkt
     * @return bool
     */
    protected function pathMatches(array $prefixes, $asPrefix = true)
    {
        $path = trim((string) Yii::$app->request->pathInfo, '/');
        $urlPath = trim((string) parse_url(Yii::$app->request->url, PHP_URL_PATH), '/');

        foreach ($prefixes as $item) {
            $item = trim((string) $item, '/');
            if ($item === '') {
                continue;
            }
            foreach ([$path, $urlPath] as $candidate) {
                if (strcasecmp($candidate, $item) === 0) {
                    return true;
                }
                if ($asPrefix && $candidate !== '' && strncasecmp($candidate, $item . '/', strlen($item) + 1) === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string|array $url
     * @return string
     */
    protected function urlToPath($url)
    {
        try {
            $resolved = Url::to($url);
        } catch (\Exception $e) {
            return '';
        }

        return trim((string) parse_url($resolved, PHP_URL_PATH), '/');
    }
}
