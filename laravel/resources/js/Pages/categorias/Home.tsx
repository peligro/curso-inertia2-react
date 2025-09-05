import { Link, useForm, usePage } from "@inertiajs/react"
import { useState } from "react";
import { Form, Modal } from "react-bootstrap";
import MensajesFlash from "../../../js/componentes/MensajesFlash";
import { CategoriaInterface, CategoriaProps } from "../../../js/Interfaces/CategoriaInterface";
import { FormularioProps } from "../../../js/Interfaces/FormularioProps";
import { route } from "ziggy-js"

const Home = () => {
  const { datos } = usePage<CategoriaProps>().props;

  //console.log('Datos recibidos:', datos);
  //formulario
  const { flash } = usePage().props as FormularioProps;

  const { data, setData, post, put, delete:destroy, processing } = useForm({
    nombre: ''
  });
  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (data.nombre.trim() == "") {
      alert("El campo nombre es obligatorio");
      setData({
        nombre: ''
      });
      return false;
    }
    if(acciones==1)
    {
      post(route('categorias_post'), {
      onSuccess: () => {
        setData({
          nombre: ''
        });
      },

     });
    }
    if(acciones==2)
    {
      //alert(route('categorias_put', {id:accionesId}));return false;
      put(route('categorias_put', {id:accionesId}), {
      onSuccess: () => {
        setData({
          nombre: ''
        });
      },

     });
    }
   
  }
  //ventana modal
  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const [acciones, setAcciones] = useState(1);
  const [accionesId, setAccionesId] = useState<number | undefined>();
  const handleCrear = () => {
    setAcciones(1);
    setData({
      nombre: ''
    });
    handleShow();
  };
  const handleEditar = (modulo:CategoriaInterface) => {

    setAcciones(2);
    setAccionesId(modulo.id);
    setData({
      nombre: modulo.nombre
    });
    handleShow();
  };
  const handleEliminar = async (id: number) => {
      
      if(confirm("¿Realmente desea eliminar este registro?"))
      {
        destroy(route('categorias_delete', {id:id}));
      }
    };
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
          <MensajesFlash flash={flash} />
          <h1>Categorías</h1>
          <p className=' d-flex justify-content-end'>
            <a className="btn btn-outline-success" href="#" onClick={() => { handleCrear() }}><i className="fas fa-plus"></i> Crear</a>
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
                    <td className="text-center">

                      <a href="#" onClick={() => { handleEditar(dato) }} title="Editar">
                        <i className="fas fa-edit"></i>
                      </a>
                      &nbsp;&nbsp;
                      <a onClick={() => { dato.id && handleEliminar(dato.id) }} href="#" title="Eliminar"><i className="fas fa-trash"></i></a>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

        </div>
      </div>
      {/*modal */}
      <Modal show={show} onHide={handleClose} dialogClassName="modal-90w">
        <Modal.Header closeButton>
          <Modal.Title>
            {acciones == 1 ? "Crear" : "Editar"}
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form onSubmit={handleSubmit}>
            <div className="row gy-3">
              <div className='col-lg-12'>
                <label className="form-label" htmlFor="nombre" style={{ fontWeight: "bold" }}>
                  Nombre
                </label>
                <input
                  className="form-control"
                  id="nombre"
                  type="text"
                  value={data.nombre}
                  onChange={e => setData('nombre', e.target.value)}
                  placeholder="Nombre"
                />

              </div>

            </div>
            <hr />
            <div className="row">
              <div className="col-6"></div>
              <div className="col-6 d-flex justify-content-end">
                <button className="btn btn-primary" disabled={processing}>
                  {acciones == 1 ?
                    (
                      <>
                        <i className="fas fa-plus"></i> Crear
                      </>
                    ) :
                    (
                      <>
                        <i className="fas fa-pencil-alt"></i>  Editar
                      </>
                    )}
                </button>
              </div>
            </div>

          </Form>
        </Modal.Body>
      </Modal>
      {/*
            <Modal show={show} onHide={handleClose} dialogClassName="modal-90w">
                <Modal.Header closeButton>
                    <Modal.Title>
                        {acciones == 1 ? "Crear" : "Editar"}
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <h1>hola</h1>
                </Modal.Body>
            </Modal>
            */}
      {/*fin modal */}
    </>
  )
}

export default Home