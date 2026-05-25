/**
 * FlyoverBD – Currency Switcher
 *
 * Strategy
 * ────────
 * • All prices in the HTML are stored as BDT via data-price-bdt="<number>"
 *   and rendered inside a <span data-currency-display>.
 * • On load this module:
 *   1. Checks localStorage for a saved currency preference.
 *   2. If none, hits ipapi.co (free, no key) to detect the visitor's country
 *      and maps it to a sensible default currency.
 *   3. Fetches live exchange rates from open.er-api.com once per session
 *      (cached in sessionStorage).  Falls back to hardcoded rates if the
 *      network request fails.
 *   4. Converts all [data-price-bdt] elements.
 * • The dropdown updates localStorage and re-converts on each selection.
 */

const CURRENCIES = [
    { code: 'BDT', symbol: '৳',  label: 'BDT – Bangladeshi Taka',   flag: '🇧🇩' },
    { code: 'USD', symbol: '$',  label: 'USD – US Dollar',           flag: '🇺🇸' },
    { code: 'EUR', symbol: '€',  label: 'EUR – Euro',                flag: '🇪🇺' },
    { code: 'GBP', symbol: '£',  label: 'GBP – British Pound',       flag: '🇬🇧' },
    { code: 'AED', symbol: 'د.إ',label: 'AED – UAE Dirham',          flag: '🇦🇪' },
    { code: 'SAR', symbol: '﷼',  label: 'SAR – Saudi Riyal',         flag: '🇸🇦' },
    { code: 'MYR', symbol: 'RM', label: 'MYR – Malaysian Ringgit',   flag: '🇲🇾' },
    { code: 'SGD', symbol: 'S$', label: 'SGD – Singapore Dollar',    flag: '🇸🇬' },
    { code: 'INR', symbol: '₹',  label: 'INR – Indian Rupee',        flag: '🇮🇳' },
    { code: 'THB', symbol: '฿',  label: 'THB – Thai Baht',           flag: '🇹🇭' },
    { code: 'CAD', symbol: 'C$', label: 'CAD – Canadian Dollar',     flag: '🇨🇦' },
    { code: 'AUD', symbol: 'A$', label: 'AUD – Australian Dollar',   flag: '🇦🇺' },
];

const COUNTRY_CURRENCY_MAP = {
    BD: 'BDT',
    US: 'USD', CA: 'CAD', AU: 'AUD', GB: 'GBP',
    DE: 'EUR', FR: 'EUR', IT: 'EUR', ES: 'EUR', NL: 'EUR', BE: 'EUR',
    AT: 'EUR', PT: 'EUR', FI: 'EUR', IE: 'EUR', GR: 'EUR', LU: 'EUR',
    AE: 'AED', SA: 'SAR', QA: 'SAR', KW: 'SAR', BH: 'SAR', OM: 'SAR',
    MY: 'MYR', SG: 'SGD', IN: 'INR', TH: 'THB',
};

const FALLBACK_RATES_VS_BDT = {
    BDT: 1,
    USD: 0.0091,
    EUR: 0.0083,
    GBP: 0.0071,
    AED: 0.0334,
    SAR: 0.0340,
    MYR: 0.0421,
    SGD: 0.0122,
    INR: 0.757,
    THB: 0.315,
    CAD: 0.0124,
    AUD: 0.0139,
};

const STORAGE_KEY  = 'flyover_currency';
const RATES_KEY    = 'flyover_rates_session';
const GEO_DONE_KEY = 'flyover_geo_done';

let currentRates = { ...FALLBACK_RATES_VS_BDT };
let currentCode  = 'BDT';

/* ── helpers ─────────────────────────────────────────────────────────────── */

function getCurrencyInfo(code) {
    return CURRENCIES.find(c => c.code === code) || CURRENCIES[0];
}

function formatPrice(bdtValue, code, rates) {
    const rate     = rates[code] ?? FALLBACK_RATES_VS_BDT[code] ?? 1;
    const converted = bdtValue * rate;
    const info     = getCurrencyInfo(code);

    const formatted = converted >= 10000
        ? Math.round(converted).toLocaleString()
        : converted >= 100
            ? converted.toLocaleString(undefined, { maximumFractionDigits: 0 })
            : converted.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    return `${info.symbol}${formatted}`;
}

/* ── DOM update ──────────────────────────────────────────────────────────── */

function updateAllPrices(code, rates) {
    document.querySelectorAll('[data-price-bdt]').forEach(el => {
        const bdt = parseFloat(el.dataset.priceBdt);
        if (isNaN(bdt)) return;

        const display = el.querySelector('[data-currency-display]') ?? el;
        display.textContent = formatPrice(bdt, code, rates);
    });
}

/* ── dropdown UI ─────────────────────────────────────────────────────────── */

function buildDropdownHTML(selectedCode) {
    const opts = CURRENCIES.map(c => `
        <button type="button"
                data-currency-option="${c.code}"
                class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition ${c.code === selectedCode ? 'bg-red-50 text-red-600 font-semibold' : ''}">
            <span class="text-base leading-none">${c.flag}</span>
            <span class="truncate">${c.label}</span>
            ${c.code === selectedCode ? '<svg class="w-3.5 h-3.5 ml-auto flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>' : ''}
        </button>`).join('');

    return `<div class="max-h-72 overflow-y-auto py-1">${opts}</div>`;
}

function renderDropdowns(code) {
    document.querySelectorAll('[data-currency-dropdown-content]').forEach(el => {
        el.innerHTML = buildDropdownHTML(code);
        el.querySelectorAll('[data-currency-option]').forEach(btn => {
            btn.addEventListener('click', () => {
                const chosen = btn.dataset.currencyOption;
                selectCurrency(chosen);
                document.querySelectorAll('[data-currency-dropdown]').forEach(d => {
                    d.dispatchEvent(new CustomEvent('currency-close'));
                });
            });
        });
    });

    document.querySelectorAll('[data-currency-trigger-label]').forEach(el => {
        const info = getCurrencyInfo(code);
        el.textContent = `${info.flag} ${info.code}`;
    });
}

function selectCurrency(code) {
    currentCode = code;
    localStorage.setItem(STORAGE_KEY, code);
    updateAllPrices(code, currentRates);
    renderDropdowns(code);
}

/* ── rates fetch ─────────────────────────────────────────────────────────── */

async function loadRates() {
    try {
        const cached = sessionStorage.getItem(RATES_KEY);
        if (cached) {
            currentRates = JSON.parse(cached);
            return;
        }
        const res  = await fetch('https://open.er-api.com/v6/latest/BDT', { cache: 'no-store' });
        if (!res.ok) throw new Error('rate fetch failed');
        const data = await res.json();
        if (data.result === 'success' && data.rates) {
            currentRates = data.rates;
            sessionStorage.setItem(RATES_KEY, JSON.stringify(currentRates));
        }
    } catch (_) {
        currentRates = { ...FALLBACK_RATES_VS_BDT };
    }
}

/* ── geo detection ───────────────────────────────────────────────────────── */

async function detectCountry() {
    if (localStorage.getItem(GEO_DONE_KEY)) return null;
    try {
        const res  = await fetch('https://ipapi.co/json/', { cache: 'no-store' });
        if (!res.ok) throw new Error('geo failed');
        const data = await res.json();
        localStorage.setItem(GEO_DONE_KEY, '1');
        return data.country_code || null;
    } catch (_) {
        localStorage.setItem(GEO_DONE_KEY, '1');
        return null;
    }
}

/* ── init ────────────────────────────────────────────────────────────────── */

async function init() {
    const saved = localStorage.getItem(STORAGE_KEY);
    currentCode = saved || 'BDT';

    await loadRates();

    if (!saved) {
        const country = await detectCountry();
        if (country && COUNTRY_CURRENCY_MAP[country]) {
            currentCode = COUNTRY_CURRENCY_MAP[country];
            localStorage.setItem(STORAGE_KEY, currentCode);
        }
    }

    updateAllPrices(currentCode, currentRates);
    renderDropdowns(currentCode);
}

/* ── Alpine component (used in app.blade.php via x-data) ─────────────────── */

window.currencyDropdown = function () {
    return {
        open: false,
        toggle()  { this.open = !this.open; },
        close()   { this.open = false; },
    };
};

window.FlyoverCurrency = {
    init,
    selectCurrency,
    getCurrencyInfo,
    CURRENCIES,
    formatPrice: (bdt) => formatPrice(bdt, currentCode, currentRates),
};

document.addEventListener('DOMContentLoaded', init);
