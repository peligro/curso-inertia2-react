import './bootstrap';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import Layout from './Layout/Layout';
import { PageModuleInterface } from './Interfaces/PageModuleInterface';
createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob<PageModuleInterface>('./Pages/**/*.tsx', { eager: true });
        const pagePath = `./Pages/${name}.tsx`;

        const pageModule = pages[pagePath];

        if (!pageModule) {
            throw new Error(`Página "${name}" no encontrada.`);
        }
        const pageComponent = pageModule.default;
        const Page = pageComponent;
        const pageWithLayout = pageModule.layout ? (
            pageModule.layout(<Page />)
        ) : (
            <Layout><Page /></Layout>
        );

        return {
            ...pageModule,
            default: () => pageWithLayout,
        };
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />)
  },
})