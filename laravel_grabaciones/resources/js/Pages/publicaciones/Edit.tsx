import { Head, useForm, usePage } from "@inertiajs/react"
import { Breadcrumb } from "react-bootstrap"
import MensajesFlash from "../../../js/componentes/MensajesFlash";
import { PublicacionesEditProps } from "resources/js/Interfaces/PublicacionesEditProps";
import { route } from "ziggy-js"
import { useRef } from "react";


const Edit = () => { 
  const {categorias, flash, errors, datos, bucket} = usePage<PublicacionesEditProps>().props;
  const fileInputRef = useRef<HTMLInputElement>(null);
 
 
  const {data, setData, post, processing} = useForm({
    categoria_id: `${datos.categorias_id}`,
    nombre: datos.nombre,
    descripcion: datos.descripcion,
    foto: null as File | null
  });

  const handleFileChange = (e:React.ChangeEvent<HTMLInputElement>)=>
  {
    if(e.target.files && e.target.files[0]){
      setData('foto', e.target.files[0]);
    }
  };

  const handleSubmit=(e: React.FormEvent<HTMLFormElement>)=>{
    e.preventDefault();
    post(route('publicaciones_edit_post', {id: datos.id}), {forceFormData: true});
  };
  return (
    <>
     <Head title="Publicaciones"/>
   <div className="row">
      <div className="col-12">
        <Breadcrumb>
          <Breadcrumb.Item href="/"> <i className="fas fa-home"></i> </Breadcrumb.Item>
          <Breadcrumb.Item href="/publicaciones">Publicaciones</Breadcrumb.Item>
          <Breadcrumb.Item active>Editar: <strong>{datos.nombre}</strong></Breadcrumb.Item>
        </Breadcrumb>
        <MensajesFlash flash={flash} />
        <h1>Editar: <strong>{datos.nombre}</strong></h1>
        
        <form onSubmit={handleSubmit} encType="multipart/form-data">
          <div className="row">

              <div className="mb-3">
                <hr />
                <img 
                src={`/s3/${bucket}/${datos.foto}`} 
                onError={(e) => {
              (e.target as HTMLImageElement).src = 'https://dummyimage.com/320x320/cccccc/000.png&text=no%20disponible';
            }}
              width="25%" 
              />
                <hr />
              </div>

              <div className="mb-3">
                <label htmlFor="categoria_id" className="form-label" style={{fontWeight:"bold"}}>Categoría:</label>
                  <select 
                  id="categoria_id" 
                  className={`form-control ${errors?.categoria_id ? 'is-invalid': ''}`}
                  value={data.categoria_id}
                  onChange={e =>{setData('categoria_id', e.target.value)}}
                  disabled={processing}>
                    <option value="0">Seleccione.....</option>
                    {categorias.map((categoria)=>(
                      <option key={categoria.id} value={categoria.id}>{categoria.nombre}</option>
                    ))}
                  </select>
                  {errors?.categoria_id &&(
                    <div className="invalid-feedback">{errors.categoria_id}</div>
                  )}
              </div>
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
                <label htmlFor="descripcion" className="form-label" style={{fontWeight:"bold"}}>Descripción:</label>
                  <textarea 
                  id="descripcion" 
                  className={`form-control ${errors?.descripcion ? 'is-invalid': ''}`}
                  placeholder="Descripción"
                  value={data.descripcion}
                  onChange={(e)=>{setData('descripcion', e.target.value)}}
                  disabled={processing}
                  ></textarea>
                  {errors?.descripcion &&(
                    <div className="invalid-feedback">{errors.descripcion}</div>
                  )}
              </div>

              <div className="mb-3">
                <label htmlFor="foto" className="form-label" style={{fontWeight:"bold"}}>Foto:</label>
                  <input 
                  ref={fileInputRef}
                  id="foto"
                  type="file" 
                  className={`form-control ${errors?.foto ? 'is-invalid': ''}`}
                  disabled={processing}
                  onChange={handleFileChange}
                  accept="image/*" />
                  {data?.foto &&(
                    <div className="mt-2">
                      <small>Archivo seleccionado: {data.foto.name}</small>
                    </div>
                  )}
                  {errors?.foto &&(
                    <div className="invalid-feedback">{errors.foto}</div>
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

export default Edit