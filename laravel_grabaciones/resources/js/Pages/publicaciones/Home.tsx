import { Head, Link, usePage } from "@inertiajs/react"
import PaginacionCustom from "../../../js/componentes/PaginacionCustom";
import { PublicacionesProps } from "resources/js/Interfaces/PublicacionesINterfaces";
import { route } from "ziggy-js"
import ImagenCustom from "../../../js/componentes/ImagenCustom";
import MensajesFlash from "../../../js/componentes/MensajesFlash";


const Home = () => {
  const {datos, bucket, flash} = usePage<PublicacionesProps>().props;
  const handleEliminar=(id: number)=>
  {
    if(confirm("¿Realmente desea eliminar el registro?"))
    {
      window.location.href=`${route('publicaciones_delete', {id: id})}`;
    }
  };
  return (
    <>
     <Head title="Publicaciones"/>
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
              Publicaciones
            </li>
          </ol>
        </nav>
        <MensajesFlash flash={flash} />
        <h1>Publicaciones</h1>
        <p className="d-flex justify-content-end">
          <a href={route('publicaciones_add')} title="Crear" className="btn btn-outline-success">
            <i className="fas fa-plus"></i> Crear
          </a>
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
              {datos.data.map((dato)=>(
                <tr key={dato.id}>
                  <td>{dato.id}</td>
                  <td>{dato.categorias ? dato.categorias.nombre : 'Sin categoría'}</td>
                  <td>{dato.nombre}</td>
                  <td>{dato.descripcion}</td>
                  <td className="text-center">
                    <ImagenCustom
                    imagenUrl={`/s3/${bucket}/${dato.foto}`}
                    titulo={`Foto ${dato.nombre}`}>
                      <i className="fas fa-image" style={{color:'#2f64b1'}} title={`Foto ${dato.nombre}`}></i>
                    </ImagenCustom>
                  </td>
                  <td className="text-center">
                    <Link href={route('publicaciones_edit', {id: dato.id})}>
                      <i className="fas fa-edit"></i>
                    </Link>
                    &nbsp;&nbsp;
                    <a href="#" title="Eliminar" onClick={()=>{ dato.id &&  handleEliminar(dato.id) }}>
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