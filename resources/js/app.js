import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import PrimeVue from 'primevue/config';
import ToastService from "primevue/toastservice";
import Tooltip from 'primevue/tooltip';
import { createI18n } from 'vue-i18n';
import en from "./Locales/en.json";
import pl from "./Locales/pl.json";
import getBrowserLocale from "./Locales/BrowserLocale";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Router';

const browserLocale = getBrowserLocale({ countryCodeOnly: true });

const i18nOptions = {
    locale: browserLocale,
    fallbackLng: 'en',
    messages: {
        en: en,
        pl: pl
    }
};

const i18n = createI18n(i18nOptions);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .directive('tooltip', Tooltip)
            .use(PrimeVue)
            .use(i18n)
            .use(ToastService)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
