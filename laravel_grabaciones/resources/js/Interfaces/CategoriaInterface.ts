import {PageProps} from '@inertiajs/core';


export interface CategoriaInterface{
    id?:number;
    nombre: string;
    slug: string;
}

export interface CategoriaProps extends PageProps{
    datos : CategoriaInterface[];
    flash?:{
        success?:string;
        css?:string;
        mensaje?:string;
    }
}