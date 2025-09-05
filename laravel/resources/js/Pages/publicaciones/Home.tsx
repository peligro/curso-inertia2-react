import { Link, usePage } from "@inertiajs/react";
import { PublicacionesProps } from "../../../js/Interfaces/PublicacionesInterface";
import MensajesFlash from "resources/js/componentes/MensajesFlash";
import { route } from "ziggy-js";
import PaginacionCustom from "../../../js/componentes/PaginacionCustom";
import ImagenCustom from "../../../js/componentes/ImagenCustom";





const Home = () => {
  const { datos } = usePage<PublicacionesProps>().props;
  // console.log(datos)
  return (
    <>

      <div className="row">
        <div className="col-12">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item">
                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Publicaciones</li>
            </ol>
          </nav>
          <h1>Publicaciones</h1>
          <p className=' d-flex justify-content-end'>
            <a className="btn btn-outline-success" href="#" onClick={() => { }}><i className="fas fa-plus"></i> Crear</a>
          </p>
          <div className="table-responsive">
            <table className="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Categoría</th>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Foto</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                {datos.data.map((dato) => (
                  <tr key={dato.id}>
                    <td>{dato.id}</td>
                    <td>{dato.categorias ? dato.categorias.nombre : 'Sin categoría'}</td>
                    <td>{dato.nombre}</td>
                    <td>{dato.descripcion}</td>
                    <td className="text-center">
                      <ImagenCustom
                        imagenUrl={`${dato.foto}`}
                        titulo={`Logo ${dato.nombre}`}
                      >
                        <i className="fas fa-image" style={{ color: "#2f64b1" }} title={`Foto ${dato.nombre}`}></i>
                      </ImagenCustom>
                    </td>
                    <td className="text-center">

                      <a href="#" onClick={() => { }} title="Editar">
                        <i className="fas fa-edit"></i>
                      </a>
                      &nbsp;&nbsp;
                      <a onClick={() => { }} href="#" title="Eliminar"><i className="fas fa-trash"></i></a>
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