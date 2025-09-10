export interface User {
    id: number;
    name: string;
    perfil: string;
    perfil_id:string;
    estado: string;
    estados_id: string;
}

export interface Auth {
    user: User | null;
}

export interface LogueadoProps {
    auth: Auth;
    flash?: {
        success?: string;
        error?: string;
        message?: string;
        css?: string;
        mensaje?: string;
    };
    errors?: Record<string, string>;
    bucket?: string;
    [key: string]: any;
}