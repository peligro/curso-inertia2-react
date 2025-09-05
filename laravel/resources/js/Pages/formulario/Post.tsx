import { Link, useForm, usePage } from "@inertiajs/react"
import { route } from "ziggy-js"
import React from 'react';
import { FormularioProps } from "../../Interfaces/FormularioProps";
import MensajesFlash from "../../componentes/MensajesFlash";

const Post = () => {
    const { errors, flash } = usePage().props as FormularioProps;
    
    const { data, setData, post, processing } = useForm({
        nombre: '',
        correo: ''
    });
   
    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        //post("/formulario/post")
        post(route('formulario_post_post'), {
            onSuccess: () => {
                setData({
                    nombre: '',
                    correo: ''
                });
            },
            
        });
    }

    // Mostrar mensajes flash
    React.useEffect(() => {
        if (flash?.success) {
            alert(flash.success);  
        }
    }, [flash]);

    return (
        <>
            <div className="row">
                <div className="col-12">
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb">
                            <li className="breadcrumb-item">
                                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
                            </li>
                            <li className="breadcrumb-item">
                                <Link href={route('formulario_index')}>Formulario</Link>
                            </li>
                            <li className="breadcrumb-item active" aria-current="page">Formulario post</li>
                        </ol>
                    </nav>
                    
                    <h1>Formulario post</h1>
                    
                    {/* Mostrar mensaje flash */}
                    {/*
                    {flash?.mensaje && (
                        <div className={`alert alert-${flash.css || 'success'}  alert-dismissible fade show`}>
                            {flash.mensaje}
                              <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                        </div>
                    )}
                    */}
                     <MensajesFlash flash={flash} />
                    <form onSubmit={handleSubmit}>
                        <div className="row">
                            <div className="mb-3">
                                <label htmlFor="nombre" className="form-label">Nombre</label>
                                <input
                                    className={`form-control ${errors?.nombre ? 'is-invalid' : ''}`}
                                    type="text"
                                    id="nombre"
                                    value={data.nombre}
                                    onChange={e => setData('nombre', e.target.value)}
                                    placeholder="Nombre"
                                    disabled={processing}
                                />
                                {errors?.nombre && (
                                    <div className="invalid-feedback">{errors.nombre}</div>
                                )}
                            </div>

                            <div className="mb-3">
                                <label htmlFor="correo" className="form-label">Correo</label>
                                <input
                                    className={`form-control ${errors?.correo ? 'is-invalid' : ''}`}
                                    type="test"
                                    id="correo"
                                    value={data.correo}
                                    onChange={e => setData('correo', e.target.value)}
                                    placeholder="Correo electrónico"
                                    disabled={processing}
                                />
                                {errors?.correo && (
                                    <div className="invalid-feedback">{errors.correo}</div>
                                )}
                            </div>

                            <div className="mb-3">
                                <button 
                                    type="submit" 
                                    className='btn btn-danger' 
                                    disabled={processing}
                                >
                                    <i className="fas fa-arrow-up"></i> 
                                    {processing ? 'Enviando...' : 'Enviar'}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}

export default Post;