export interface LoginProps {
    errors?: {
        correo?: string;
        password?: string;
    };
    flash?: {
        success?: string;
        css?: string;
        mensaje?: string;
    };
}
 