import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { NDialogProvider } from 'naive-ui';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Use the plugin and Ziggy
        app.use(plugin).use(ZiggyVue, Ziggy);

        // Define the NDialogProvider as a global component
        app.component('NDialogProvider', NDialogProvider);

        // Mount the app to the custom element '#app'
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});