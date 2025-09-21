import { Head, Link, useForm, usePage } from "@inertiajs/react"
import { Form } from "react-bootstrap";
import MensajesFlash from "../../../js/componentes/MensajesFlash";
import { UsuariosFormularioProps } from "resources/js/Interfaces/UsuariosFormularioInterface";
import { PerfilInterface } from "resources/js/Interfaces/UsuariosInterfaces";
import { route } from "ziggy-js"

 

const Add = () => {
    const {perfiles, errors, flash} = usePage<UsuariosFormularioProps>().props;
    const {data, setData, post, processing} = useForm({
        perfil_id:'0',
        nombre: '',
        correo: '',
        password: '',
        password_confirmation: '',
        telefono: ''
    });
    const handleSubmit=(e: React.FormEvent<HTMLFormElement>)=>{
    e.preventDefault();
    post(route('usuarios_add_post') );
  };
  //verificación si hay errores
  const hasErrors = errors && Object.keys(errors).length>0;
  return (
   <>
   <Head title="Usuarios"/>
   <div className="row">
      <div className="col-12">
        <nav aria-label='breadcrumb'>
          <ol className="breadcrumb">
            <li className="breadcrumb-item">
              <Link href={route('home_index')}>
                <i className="fas fa-home"></i>
              </Link>
            </li>
            <li className="breadcrumb-item">
              <Link href={route('usuarios_index')}>
                Usuarios
              </Link>
            </li>
            <li className="breadcrumb-item active" aria-current='page'>
              Crear
            </li>
          </ol>
        </nav>
        {/* Mostramos errores generales en una lista UL/LI*/}
        {hasErrors && (
            <div className="alert alert-danger alert-dismissible face show">
                <h5 className="alert-heading">Errores de validación</h5>
                <ul className="mb-0">
                    {Object.entries(errors).map(([field, message])=>(
                        <li key={field}>{message}</li>
                    ))}
                </ul>
                <button type="button" className="btn-close" data-bs-dismiss="alert" arial-label="Close"></button>
            </div>
        )}
        <MensajesFlash flash={flash} />
        <h1>Crear</h1>
        <Form onSubmit={handleSubmit}>
            <div className="row">
                <div className="mb-3">
                    <label htmlFor="perfil_id" className="form-label" style={{fontWeight:"bold"}}>Perfiles:</label>
                    <select 
                    id="perfil_id" 
                    className={`form-control `}
                    value={data.perfil_id}
                    onChange={e =>{setData('perfil_id', e.target.value)}}
                    disabled={processing}>
                        <option value="0">Seleccione.....</option>
                        {perfiles.map((perfil:PerfilInterface)=>(
                            <option value={perfil.id} key={perfil.id}>{perfil.nombre}</option>
                        ))}
                    </select>
                    
                </div>

                <div className="mb-3">
                <label htmlFor="nombre" className="form-label" style={{fontWeight:"bold"}}>Nombre:</label>
                  <input 
                      type="text"
                      className={`form-control `}
                      placeholder="Nombre:"
                      value={data.nombre}
                      onChange={(e)=>{setData('nombre', e.target.value)}}
                      disabled={processing}
                      />
                      
                </div>

                <div className="mb-3">
                <label htmlFor="correo" className="form-label" style={{fontWeight:"bold"}}>Correo:</label>
                  <input 
                      type="email"
                      className={`form-control `}
                      placeholder="Correo:"
                      value={data.correo}
                      onChange={(e)=>{setData('correo', e.target.value)}}
                      disabled={processing}
                      />
                      
                </div>

                <div className="mb-3">
                <label htmlFor="telefono" className="form-label" style={{fontWeight:"bold"}}>Teléfono:</label>
                  <input 
                      type="text"
                      className={`form-control `}
                      placeholder="Teléfono:"
                      value={data.telefono}
                      onChange={(e)=>{setData('telefono', e.target.value)}}
                      disabled={processing}
                      />
                      
                </div>

                <div className="mb-3">
                <label htmlFor="password" className="form-label" style={{fontWeight:"bold"}}>Contraseña:</label>
                  <input 
                      type="password"
                      className={`form-control `}
                      placeholder="Contraseña:"
                      value={data.password}
                      onChange={(e)=>{setData('password', e.target.value)}}
                      disabled={processing}
                      />
                      
                </div>

                <div className="mb-3">
                <label htmlFor="password_confirmation" className="form-label" style={{fontWeight:"bold"}}>Repetir Contraseña:</label>
                  <input 
                      type="password"
                      className={`form-control `}
                      placeholder="Repetir Contraseña:"
                      value={data.password_confirmation}
                      onChange={(e)=>{setData('password_confirmation', e.target.value)}}
                      disabled={processing}
                      />
                      
                </div>

                <div className="mb-3">
                <button type="submit" className="btn btn-warning" disabled={processing}>
                  <i className="fas fa-arrow-up"></i> {processing ? 'Enviando':'Enviar'}
                </button>
              </div>

            </div>
        </Form>
        

      </div>
    </div>
   </>
  )
}

export default Add