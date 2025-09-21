import {PageProps} from '@inertiajs/core';

export interface PerfilesInterface{
    id?: number;
    nombre: string;
}

export interface PerfilesProps extends PageProps{
    datos: PerfilesInterface[];
    flash?:{
        success?:string;
        css?:string;
        mensaje?:string;
    }
}