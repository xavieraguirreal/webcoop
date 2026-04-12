/* ==========================================================================
   Cooperativa Liberté - i18n Auto-detection v1.0
   PHP ya detecta Accept-Language y setea cookie en la primera visita.
   Este script mejora con geolocalización IP solo para refinar el país
   dentro del mismo idioma (ej: "es" genérico → es_CO si está en Colombia).
   No cambia el idioma base del usuario.
   Mismo approach que appULIB.
   ========================================================================== */
(function () {
  'use strict';

  if (document.cookie.indexOf('coop_lang=') === -1) return;

  if (sessionStorage.getItem('coop_ip_checked')) return;
  sessionStorage.setItem('coop_ip_checked', '1');

  var COUNTRY_LOCALE = {
    AR: 'es_AR', UY: 'es_UY', CO: 'es_CO', EC: 'es_EC',
    PE: 'es_PE', ES: 'es_ES', BR: 'pt_BR', MX: 'es_MX',
    GT: 'es_GT', HN: 'es_HN', SV: 'es_SV', NI: 'es_NI',
    CR: 'es_CR', PA: 'es_PA', CU: 'es_CU', DO: 'es_DO',
    PR: 'es_PR', CL: 'es_CL', VE: 'es_VE', BO: 'es_BO',
    PY: 'es_PY', PT: 'pt_PT', US: 'en_US', CA: 'en_CA',
    GY: 'en_GY', BZ: 'en_BZ', JM: 'en_JM', TT: 'en_TT',
    BS: 'en_BS', HT: 'fr_HT', SR: 'nl_SR', AG: 'en_AG',
    BB: 'en_BB', DM: 'en_DM', GD: 'en_GD', KN: 'en_KN',
    VC: 'en_VC', LC: 'en_LC', AW: 'nl_AW', CW: 'nl_CW',
    IT: 'it_IT'
  };

  function getCurrentLocale() {
    var match = document.cookie.match(/coop_lang=([^;]+)/);
    return match ? match[1] : 'es_AR';
  }

  try {
    fetch('https://api.country.is/', { mode: 'cors' })
      .then(function (res) {
        if (!res.ok) throw new Error();
        return res.json();
      })
      .then(function (data) {
        var cc = data.country || '';
        var ipLocale = COUNTRY_LOCALE[cc];
        if (!ipLocale) return;

        var current = getCurrentLocale();
        if (current === ipLocale) return;

        var currentBase = current.substring(0, 2);
        var ipBase = ipLocale.substring(0, 2);

        if (currentBase === ipBase) {
          document.cookie = 'coop_lang=' + ipLocale + '; path=/; max-age=31536000; SameSite=Lax';
          location.reload();
        }
      })
      .catch(function () {});
  } catch (e) {}
})();
