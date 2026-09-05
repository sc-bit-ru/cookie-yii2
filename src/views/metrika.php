<?php

/** @var int $counterId */
/** @var array $options */
$jsonOptions = json_encode($options, JSON_UNESCAPED_UNICODE);
?>
<script>
window.yiiCookieConsent = window.yiiCookieConsent || {};
window.yiiCookieConsent.loaded = window.yiiCookieConsent.loaded || false;
window.yiiCookieConsent.loadAnalytics = function () {
	if (window.yiiCookieConsent.loaded) {
		return;
	}
	window.yiiCookieConsent.loaded = true;
	var id = <?= (int) $counterId ?>;
	(function (m, e, t, r, i, k, a) {
		m[i] = m[i] || function () { (m[i].a = m[i].a || []).push(arguments); };
		m[i].l = 1 * new Date();
		for (var j = 0; j < document.scripts.length; j++) {
			if (document.scripts[j].src === r) { return; }
		}
		k = e.createElement(t);
		a = e.getElementsByTagName(t)[0];
		k.async = 1;
		k.src = r;
		a.parentNode.insertBefore(k, a);
	})(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js', 'ym');
	ym(id, 'init', <?= $jsonOptions ?>);
};
if (/(?:^|; )cookieConsent=all(?:;|$)/.test(document.cookie)) {
	window.yiiCookieConsent.loadAnalytics();
}
</script>
