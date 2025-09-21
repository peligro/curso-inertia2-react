import './bootstrap';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import Layout from './Layout/Layout';
import { PageModuleInterface } from './Interfaces/PageModuleInterface';


import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

// Extender la interfaz Window
declare global {
  interface Window {
    toastr: typeof toastr;
  }
}

// Configurar toastr
toastr.options = {
  closeButton: true,
  debug: false,
  newestOnTop: false,
  progressBar: true,
  positionClass: 'toast-top-right',
  preventDuplicates: false, 
  showDuration: 300,
  hideDuration: 1000,
  timeOut: 5000,
  extendedTimeOut: 1000,
  showEasing: 'swing',
  hideEasing: 'linear',
  showMethod: 'fadeIn',
  hideMethod: 'fadeOut'
};

// Asignar a window
window.toastr = toastr;


createInertiaApp({
  title: (title)=>
    title ? `Inertia - ${title}` : 'Inertia', 
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
  progress:{//barra de progreso en peticiones http
    color:'#ff0000',
    showSpinner: true
  }
})