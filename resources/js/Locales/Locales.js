import pl from './pl.json';
import en from './en.json';

const i18nOptions = {
    locale: 'pl',
    globalInjection: true,
    messages: {
        en: en,
        pl: pl
    }
};

export default i18nOptions;
