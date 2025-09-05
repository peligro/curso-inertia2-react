import { PageProps as BasePageProps } from '@inertiajs/core';

declare module '@inertiajs/core' {
    interface PageProps extends BasePageProps {
        id?: string | number;
        slug?: string;
        // Agrega aquí props globales que uses en múltiples páginas
    }
}