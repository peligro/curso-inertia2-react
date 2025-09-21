import {PageProps} from '@inertiajs/core';
import { CategoriaInterface } from './CategoriaInterface';


export interface PublicacionesAddProps extends PageProps{
    categorias: CategoriaInterface[];
    errors?:{
        categoria_id?: string;
        nombre?:string;
        descripcion?:string;
        foto?:string;
        [key: string]: string | undefined;
    },
    flash?:{
        success?:string;
        css?:string;
        mensaje?:string;
    }
}

