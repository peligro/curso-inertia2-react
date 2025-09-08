import { usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import { PageProps } from '@inertiajs/core';

interface FlashProps extends PageProps {
    flash?: {
        mensaje?: string;
        css?: string;
        success?: string;
        error?: string;
        message?: string;
    };
}

const ToastFlash = () => {
    const { flash } = usePage<FlashProps>().props;
    const { mensaje, css, success, error, message } = flash || {};

    useEffect(() => {
        const finalMessage = mensaje || success || error || message;
        
        if (finalMessage) {
            const toastr = (window as any).toastr;
            if (!toastr) {
                console.warn('Toastr no está disponible');
                return;
            }

            switch (css) {
                case 'success':
                    toastr.success(finalMessage);
                    break;
                case 'error':
                case 'danger':
                    toastr.error(finalMessage);
                    break;
                case 'warning':
                    toastr.warning(finalMessage);
                    break;
                case 'info':
                    toastr.info(finalMessage);
                    break;
                default:
                    toastr.info(finalMessage);
            }
        }
    }, [mensaje, css, success, error, message]);

    return null;
};

export default ToastFlash;