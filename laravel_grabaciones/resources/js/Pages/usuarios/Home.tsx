import { Head, Link, usePage } from "@inertiajs/react";
import { formateaFecha } from "../../../js/Helpers/Helpers";
import { UsuariosProps } from "resources/js/Interfaces/UsuariosInterfaces";
import { route } from "ziggy-js";
import PaginacionCustom from "../../../js/componentes/PaginacionCustom";
import MensajesFlash from "../../../js/componentes/MensajesFlash";


const Home = () => {
  const {datos, flash} = usePage<UsuariosProps>().props;
  const handleEliminar=(id: number)=>
  {
    if(confirm("¿Realmente desea eliminar el registro?"))
    {
      window.location.href=`${route('usuarios_delete', {id: id})}`;
    }
  };
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
            <li className="breadcrumb-item active" aria-current='page'>
              Usuarios
            </li>
          </ol>
        </nav>
        <MensajesFlash flash={flash} />
        <h1>Usuarios</h1>
        <p className="d-flex justify-content-end">
          <Link href={route('usuarios_add')} title="Crear" className="btn btn-outline-success">
            <i className="fas fa-plus"></i> Crear
          </Link>
        </p>
        <div className="table-responsive">
          <table className="table table-bordered table-hover table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Estado</th>
                <th>Perfil</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {datos.data.map((dato, index)=>(
              <tr key={index}>
                <td>{dato.id}</td>
                <td>{dato.estados?.nombre}</td>
                <td>{dato.perfiles?.nombre}</td>
                <td>{dato.users?.name}</td>
                <td>{dato.users?.email}</td>
                <td>{formateaFecha(dato.users?.created_at)}</td>
                <td className="text-center">
                  <Link href={route('usuarios_edit', {id: dato.id})}>
                      <i className="fas fa-edit"></i>
                    </Link>
                    &nbsp;&nbsp;
                    <a href="#" title="Eliminar" onClick={()=>{ dato.id && handleEliminar(dato.id)  }}>
                      <i className="fas fa-trash"></i>
                    </a>
                </td>
              </tr>
            ))}
            </tbody>
          </table>
          <PaginacionCustom datos={datos} />
        </div>

      </div>
    </div>
    </>
  )
}

export default Home