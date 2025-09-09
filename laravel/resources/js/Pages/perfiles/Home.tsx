import { Head, Link, useForm, usePage } from "@inertiajs/react"
import { useState } from "react";
import { Form, Modal } from "react-bootstrap";
import MensajesFlash from "../../../js/componentes/MensajesFlash"; 
import { FormularioProps } from "../../../js/Interfaces/FormularioProps";
import { route } from "ziggy-js"
import { AlertCustomInterface } from "resources/js/Interfaces/AlertCustomInterface";
import AlertCustom from "../../../js/componentes/AlertCustom";
import { PerfilesInterface, PerfilesProps } from "resources/js/Interfaces/PerfilesInterface";

const Home = () => {
  const { datos } = usePage<PerfilesProps>().props;

  //console.log('Datos recibidos:', datos);
  //formulario
  const { flash } = usePage().props as FormularioProps;

  const { data, setData, post, put, delete: destroy, processing } = useForm({
    nombre: ''
  });
  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (data.nombre.trim() == "") {
      setAlertData({
                estado: true,
                titulo: "Alerta !!!",
                detalle: "El campo nombre es obligatorio",
                headerBg: "bg-danger"
            });
      setData({
        nombre: ''
      });
      return false;
    }
    if (acciones == 1) {
      post(route('perfiles_post'), {
        onSuccess: () => {
          setData({
            nombre: ''
          });
        },

      });
    }
    if (acciones == 2) {
      put(route('perfiles_put', { id: accionesId }), {
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
  const handleEditar = (modulo: PerfilesInterface) => {

    setAcciones(2);
    setAccionesId(modulo.id);
    setData({
      nombre: modulo.nombre
    });
    handleShow();
  };
  const handleEliminar = async (id: number) => {

    if (confirm("¿Realmente desea eliminar este registro?")) {
      destroy(route('perfiles_delete', { id: id }));
    }
  };
  //alertCustom
  const [alertData, setAlertData] = useState<AlertCustomInterface>({
    estado: false,
    titulo: "",
    detalle: "",
    headerBg: "bg-primary" // Valor por defecto
  });

  const handleCloseModal = () => {
    setAlertData(prev => ({
      ...prev,
      estado: false
    }));

  };
  return (
    <>
    <Head title="Perfiles" />
    <AlertCustom
                estado={alertData.estado}
                titulo={alertData.titulo}
                detalle={alertData.detalle}
                onClose={handleCloseModal}
                headerBg={alertData.headerBg} // Pasa el valor del estado
            />
      <div className="row">
        <div className="col-12">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item">
                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Perfiles</li>
            </ol>
          </nav>
          <MensajesFlash flash={flash} />
          <h1>Perfiles</h1>
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
     
    </>
  )
}

export default Home