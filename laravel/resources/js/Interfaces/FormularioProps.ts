export interface FormularioProps {
    errors?: {
        nombre?: string;
        correo?: string;
    };
    flash?: {
        success?: string;
        css?: string;
        mensaje?: string;
    };
}
/*
export interface FormularioProps {
    errors?: {
        nombre?: string;
        correo?: string;
    };
     
}*/