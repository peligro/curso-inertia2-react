import { Link, useForm, usePage } from "@inertiajs/react";
import { FormularioProps } from "../../../js/Interfaces/FormularioProps";
import { route } from "ziggy-js"; 
import MensajesFlash from "../../../js/componentes/MensajesFlash";


const Post = () => {

  const {errors , flash} = usePage().props as FormularioProps;
   
  const {data, setData, post, processing} = useForm({
    nombre:'',
    correo: ''
  });
  const handleSubmit=(e: React.FormEvent<HTMLFormElement>)=>{
    e.preventDefault();
    post(route('formularios_post_post'), {
      onSuccess:()=>{
        setData({
           nombre:'',
           correo: ''
        })
      }
    });
  };
  
  return (
    <>
    <div className="row">
      <div className="col-12">
        <nav aria-label='breadcrumb'>
          <ol className="breadcrumb">
            <li className="breadcrumb-item">
              <Link href={route('home_index')}>
                <i className="fas fa-home"></i>
              </Link>
            </li>
            <li className="breadcrumb-item active" aria-current='page'>
              Formulario Post
            </li>
          </ol>
        </nav>
        {/* 
        {flash?.mensaje && (
          <div className={`alert alert-${flash.css} alert-dismissible fade show`}>
            {flash.mensaje}
            <button type="button" className="btn-close" data-bs-dismiss="alert" arial-label="Close"></button>
          </div>
        )}
          */}
        <MensajesFlash flash={flash} />
        <h1>Formulario Post</h1>
        <form onSubmit={handleSubmit}>
          <div className="row">

              <div className="mb-3">
                <label htmlFor="nombre" className="form-label" style={{fontWeight:"bold"}}>Nombre:</label>
                <input 
                  type="text"
                  className={`form-control ${errors?.nombre ? 'is-invalid': ''}`}
                  placeholder="Nombre:"
                  value={data.nombre}
                  onChange={(e)=>{setData('nombre', e.target.value)}}
                  disabled={processing}
                  />
                  {errors?.nombre &&(
                    <div className="invalid-feedback">{errors.nombre}</div>
                  )}
              </div>

              <div className="mb-3">
                <label htmlFor="correo" className="form-label" style={{fontWeight:"bold"}}>Correo:</label>
                <input 
                  type="text"
                  className={`form-control ${errors?.correo ? 'is-invalid': ''}`}
                  placeholder="Correo:"
                  value={data.correo}
                  onChange={(e)=>{setData('correo', e.target.value)}}
                  />
                  {errors?.correo &&(
                    <div className="invalid-feedback">{errors.correo}</div>
                  )}
              </div>

              <div className="mb-3">
                <button type="submit" className="btn btn-danger" disabled={processing}>
                  <i className="fas fa-arrow-up"></i> {processing ? 'Enviando':'Enviar'}
                </button>
              </div>

          </div>
        </form>
      </div>
    </div>
    </>
  )
}

export default Post