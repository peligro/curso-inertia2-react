import { Head, Link, usePage } from "@inertiajs/react"
import { UsersMetadaInterface, UsuariosProps } from "../../../js/Interfaces/UsuariosInterface";
import { route } from "ziggy-js"
import { formateaFecha } from "../../../js/Helpers/Helpers";



const Home = () => {
    const { datos } = usePage<UsuariosProps>().props;
  console.log(datos.data  )
  return (
    <>
      <Head title="Usuarios" />
      <div className="row">
        <div className="col-12">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item">
                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Usuarios</li>
            </ol>
          </nav> 
          <h1>Usuarios</h1>
          <p className=' d-flex justify-content-end'>
            <a className="btn btn-outline-success" href="#" onClick={() => {   }}><i className="fas fa-plus"></i> Crear</a>
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
                 {datos.data.map((dato: UsersMetadaInterface) => (
                  <tr key={dato.id}>
                    <td>{dato.id}</td>
                    <td>{dato.estados?.nombre }</td>
                    <td>{dato.perfiles?.nombre}</td>
                    <td>{dato.users?.name}</td>
                    <td>{dato.users?.email}</td>
                    <td>{formateaFecha(dato.users?.created_at)}</td>
                    <td className="text-center">

                      <a href="#" onClick={() => {   }} title="Editar">
                        <i className="fas fa-edit"></i>
                      </a>
                      &nbsp;&nbsp;
                      <a onClick={() => {   }} href="#" title="Eliminar"><i className="fas fa-trash"></i></a>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </>
  )
}

export default Home
{/* 
  <Head title="Usuarios" />
      <div className="row">
        <div className="col-12">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item">
                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Usuarios</li>
            </ol>
          </nav> 
          <h1>Usuarios</h1>
          <p className=' d-flex justify-content-end'>
            <a className="btn btn-outline-success" href="#" onClick={() => {   }}><i className="fas fa-plus"></i> Crear</a>
          </p>
          <div className="table-responsive">
            <table className="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                 
              </tbody>
            </table>
          </div>

        </div>
      </div>
  */}