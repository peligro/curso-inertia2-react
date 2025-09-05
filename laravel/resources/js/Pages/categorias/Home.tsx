import { Link, usePage } from "@inertiajs/react" 
import { CategoriaProps } from "resources/js/Interfaces/CategoriaInterface";
import { route } from "ziggy-js"

const Home = () => {
   const { datos } = usePage<CategoriaProps>().props;
  
  //console.log('Datos recibidos:', datos);
  return (
    <>
      <div className="row">
        <div className="col-12">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item">
                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Categorías</li>
            </ol>
          </nav>

          <h1>Categorías</h1>
          <p className=' d-flex justify-content-end'>
          <Link className="btn btn-outline-success" href="#"><i className="fas fa-plus"></i> Crear</Link>
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
            {datos.map((dato) => (
              <tr key={dato.id}>
                <td>{dato.id}</td>
                <td>{dato.nombre}</td> 
                <td>{dato.id}</td>
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