// Cookie consent (152-ФЗ): явное согласие, аналитика только после «Принять все».
// mode=banner — сайт открыт; mode=wall — без all экран закрыт (см. README).
(function () {
	var COOKIE_NAME = 'cookieConsent';
	var METRIKA_COOKIES = ['_ym_uid', '_ym_d', '_ym_isad', '_ym_visorc', '_ym_debug'];

	function getCookie(name) {
		var prefix = name + '=';
		var parts = document.cookie.split(';');
		for (var i = 0; i < parts.length; i++) {
			var part = parts[i].replace(/^\s+/, '');
			if (part.indexOf(prefix) === 0) {
				return decodeURIComponent(part.substring(prefix.length));
			}
		}
		return '';
	}

	function setCookie(name, value, days) {
		var date = new Date();
		date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
		var secure = location.protocol === 'https:' ? ';Secure' : '';
		document.cookie = name + '=' + encodeURIComponent(value) + ';expires=' + date.toUTCString() + ';path=/;SameSite=Lax' + secure;
	}

	function expireCookie(name) {
		document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;SameSite=Lax';
	}

	function clearMetrikaCookies() {
		for (var i = 0; i < METRIKA_COOKIES.length; i++) {
			expireCookie(METRIKA_COOKIES[i]);
		}
	}

	function loadAnalytics() {
		if (window.yiiCookieConsent && typeof window.yiiCookieConsent.loadAnalytics === 'function') {
			window.yiiCookieConsent.loadAnalytics();
		}
		if (window.webdkMetrika && typeof window.webdkMetrika.load === 'function') {
			window.webdkMetrika.load();
		}
	}

	function analyticsAlreadyLoaded() {
		return (window.yiiCookieConsent && window.yiiCookieConsent.loaded)
			|| (window.webdkMetrika && window.webdkMetrika.loaded);
	}

	function getBox() {
		return document.getElementById('cookieConsent');
	}

	function getMode(box) {
		return (box && box.getAttribute('data-cookie-consent-mode')) || 'banner';
	}

	function isWall(box) {
		return getMode(box) === 'wall';
	}

	function lockPage(on) {
		var root = document.documentElement;
		if (on) {
			root.classList.add('cookie-yii2-locked');
		} else {
			root.classList.remove('cookie-yii2-locked');
		}
	}

	function setDeniedView(box, denied) {
		if (!box) {
			return;
		}
		var choice = box.querySelector('.cookie-consent-choice');
		var deniedBox = box.querySelector('.cookie-consent-denied');
		if (choice) {
			if (denied) {
				choice.setAttribute('hidden', 'hidden');
			} else {
				choice.removeAttribute('hidden');
			}
		}
		if (deniedBox) {
			if (denied) {
				deniedBox.removeAttribute('hidden');
			} else {
				deniedBox.setAttribute('hidden', 'hidden');
			}
		}
		if (denied) {
			box.classList.add('is-denied');
		} else {
			box.classList.remove('is-denied');
		}
	}

	function hideBanner(box) {
		if (!box) {
			return;
		}
		box.classList.remove('show');
		box.style.display = 'none';
		lockPage(false);
		setDeniedView(box, false);
	}

	function showBanner(box, denied) {
		if (!box) {
			return;
		}
		box.style.display = '';
		box.classList.add('show');
		setDeniedView(box, !!denied);
		if (isWall(box)) {
			lockPage(true);
		}
	}

	function updateStatus(value) {
		var status = document.getElementById('cookieConsentStatus');
		if (!status) {
			return;
		}
		if (value === 'all') {
			status.textContent = 'Сейчас включены нужные и аналитические cookie.';
			return;
		}
		if (value === 'denied') {
			status.textContent = 'Вы отказались. Сайт без согласия не показываем.';
			return;
		}
		status.textContent = 'Сейчас включены только нужные cookie. Аналитика не запускается.';
	}

	function applyConsent(value) {
		var box = getBox();
		setCookie(COOKIE_NAME, value, 365);
		updateStatus(value);

		if (value === 'all') {
			hideBanner(box);
			loadAnalytics();
			return;
		}

		clearMetrikaCookies();

		if (isWall(box) && value === 'denied') {
			showBanner(box, true);
			return;
		}

		if (isWall(box) && value === 'necessary') {
			setCookie(COOKIE_NAME, 'denied', 365);
			showBanner(box, true);
			return;
		}

		hideBanner(box);
		if (analyticsAlreadyLoaded()) {
			window.location.reload();
		}
	}

	function bindButtons(root) {
		var nodes = (root || document).querySelectorAll('[data-cookie-consent]');
		for (var i = 0; i < nodes.length; i++) {
			nodes[i].addEventListener('click', function (event) {
				event.preventDefault();
				var value = this.getAttribute('data-cookie-consent');
				if (value === 'all' || value === 'necessary' || value === 'denied') {
					applyConsent(value);
				}
			});
		}
	}

	document.addEventListener('DOMContentLoaded', function () {
		var box = getBox();
		var consent = getCookie(COOKIE_NAME);

		if (consent === 'all') {
			loadAnalytics();
			hideBanner(box);
			updateStatus(consent);
			bindButtons();
			return;
		}

		if (isWall(box) && (consent === 'denied' || consent === 'necessary')) {
			showBanner(box, true);
			updateStatus('denied');
			bindButtons();
			return;
		}

		if (consent === 'necessary') {
			hideBanner(box);
			updateStatus(consent);
			bindButtons();
			return;
		}

		// Старое «true» / пусто — спрашиваем заново.
		if (box) {
			setTimeout(function () {
				showBanner(box, false);
			}, 400);
		}
		bindButtons();
	});
})();
