import './bootstrap';
import '../css/app.css';
import "primevue/resources/themes/lara-light-indigo/theme.css";   
import 'primeicons/primeicons.css';

    
//core
import "primevue/resources/primevue.min.css";

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import PrimeVue from 'primevue/config';


import { createVuestic } from "vuestic-ui";
import "vuestic-ui/css";


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue)
            .use(ZiggyVue, Ziggy)
            .use(createVuestic())
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
