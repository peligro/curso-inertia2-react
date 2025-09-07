import { Head, Link, useForm, usePage } from "@inertiajs/react"
import { route } from "ziggy-js"
import React, { useRef } from 'react';
import MensajesFlash from "../../componentes/MensajesFlash";
import { Breadcrumb } from "react-bootstrap";
import { PublicacionesEditProps } from "../../../js/Interfaces/PublicacionesEditProps";

const Edit = () => {
       const { datos, categorias, flash, errors, bucket } = usePage<PublicacionesEditProps>().props;
    const fileInputRef = useRef<HTMLInputElement>(null); // ← Referencia para el file input

    /*
    const { data, setData, post, processing } = useForm({
        categoria_id:'0',
        nombre: '',
        descripcion: '',
        foto:''
    });*/
    const { data, setData, post, processing } = useForm({
        categoria_id: `${datos.categorias_id}`,
        nombre: datos.nombre,
        descripcion: datos.descripcion,
        foto: null as File | null // ← Cambiado a null para archivos
    });
   const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setData('foto', e.target.files[0]);
        }
    }
       
      const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        //post(route('publicaciones_add_post')  );
        post(route('publicaciones_edit_post', {id: datos.id}) ,{forceFormData: true } );
    }
    // Mostrar mensajes flash
  /*
  React.useEffect(() => {
        if (flash?.success) {
            // Si fue exitoso, entonces sí limpiar
            setData({
                categoria_id: '0',
                nombre: '',
                descripcion: '',
                foto: ''
            });
        }
    }, [flash]);*/
    React.useEffect(() => {
        if (flash?.success) {
            setData({
                categoria_id: '0',
                nombre: '',
                descripcion: '',
                foto: null
            });
            // Limpiar también el input file
            if (fileInputRef.current) {
                fileInputRef.current.value = '';
            }
        }
    }, [flash]);

    return (
        <>
        <Head title="Publicaciones" />
            <div className="row">
                <div className="col-12">
                   <Breadcrumb>
                      <Breadcrumb.Item href="/"><i className="fas fa-home"></i></Breadcrumb.Item>
                      <Breadcrumb.Item href="/publicaciones">Publicaciones</Breadcrumb.Item>
                      <Breadcrumb.Item active>Editar: <strong>{datos.nombre}</strong></Breadcrumb.Item>
                    </Breadcrumb>
                    
                    
                    <h1>Editar: <strong>{datos.nombre}</strong></h1>
                    
                    {/* Mostrar mensaje flash */}
                   
                     <MensajesFlash flash={flash} />
                     {/*<form onSubmit={handleSubmit}> */}
                    <form onSubmit={handleSubmit}  encType="multipart/form-data">
                        <div className="row">
                             <div className="mb-3">
                                <hr />
                                <img src={`/s3/${bucket}/${datos.foto}`} width="25%"  />
                                <hr />
                             </div>
                             <div className="mb-3">
                                <label htmlFor="categoria_id" className="form-label">Categoría *</label>
                                <select 
                                    id="categoria_id" 
                                    className={`form-control ${errors?.categoria_id ? 'is-invalid' : ''}`}
                                    value={data.categoria_id}
                                    onChange={e => setData('categoria_id', e.target.value)}
                                    disabled={processing}
                                >
                                    <option value="0">Seleccione.....</option>
                                    {categorias.map((categoria) => (
                                        <option key={categoria.id} value={categoria.id}>
                                            {categoria.nombre}
                                        </option>
                                    ))}
                                </select>
                               
                                {errors?.categoria_id && (
                                    <div className="invalid-feedback">{errors.categoria_id}</div>
                                )}
                            </div>
                            
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
                                <label htmlFor="descripcion" className="form-label">Descripción</label>
                                <textarea 
                                  className={`form-control ${errors?.descripcion ? 'is-invalid' : ''}`}
                                    id="descripcion"
                                    value={data.descripcion}
                                    onChange={e => setData('descripcion', e.target.value)}
                                    placeholder="Descripción"
                                    disabled={processing}
                                ></textarea>
                                
                                {errors?.descripcion && (
                                    <div className="invalid-feedback">{errors.descripcion}</div>
                                )}
                            </div>
                            
                             <div className="mb-3">
                                <label htmlFor="foto" className="form-label">Foto</label>
                                <input
                                    ref={fileInputRef} // ← Agregar referencia
                                    className={`form-control ${errors?.foto ? 'is-invalid' : ''}`}
                                    type="file"
                                    id="foto"
                                    onChange={handleFileChange} // ← Cambiado a manejador personalizado
                                    disabled={processing}
                                    accept="image/*" // ← Solo aceptar imágenes
                                />
                                {data.foto && (
                                    <div className="mt-2">
                                        <small>Archivo seleccionado: {data.foto.name}</small>
                                    </div>
                                )}
                                {errors?.foto && (
                                    <div className="invalid-feedback">{errors.foto}</div>
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

export default Edit;