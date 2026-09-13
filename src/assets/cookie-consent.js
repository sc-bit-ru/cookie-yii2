// Cookie consent (152-ФЗ): явное согласие, аналитика только после «Принять все».
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

	function hideBanner(box) {
		if (!box) {
			return;
		}
		box.classList.remove('show');
		box.style.display = 'none';
	}

	function showBanner(box) {
		if (!box) {
			return;
		}
		box.style.display = '';
		box.classList.add('show');
	}

	function updateStatus(value) {
		var status = document.getElementById('cookieConsentStatus');
		if (!status) {
			return;
		}
		status.textContent = value === 'all'
			? 'Сейчас включены нужные и аналитические cookie.'
			: 'Сейчас включены только нужные cookie. Аналитика не запускается.';
	}

	function applyConsent(value) {
		setCookie(COOKIE_NAME, value, 365);
		hideBanner(document.getElementById('cookieConsent'));
		updateStatus(value);

		if (value === 'all') {
			loadAnalytics();
			return;
		}

		clearMetrikaCookies();
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
				if (value === 'all' || value === 'necessary') {
					applyConsent(value);
				}
			});
		}
	}

	document.addEventListener('DOMContentLoaded', function () {
		var box = document.getElementById('cookieConsent');
		var consent = getCookie(COOKIE_NAME);

		if (consent === 'all') {
			loadAnalytics();
			hideBanner(box);
			updateStatus(consent);
			bindButtons();
			return;
		}

		if (consent === 'necessary') {
			hideBanner(box);
			updateStatus(consent);
			bindButtons();
			return;
		}

		// Старое «true» / пусто — спрашиваем заново (раньше часто писали «продолжая использовать»).
		if (box) {
			setTimeout(function () {
				showBanner(box);
			}, 400);
		}
		bindButtons();
	});
})();
