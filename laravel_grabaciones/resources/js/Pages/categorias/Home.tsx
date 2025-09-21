import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { useState } from "react";
import { Form, Modal } from "react-bootstrap";
import MensajesFlash from "../../../js/componentes/MensajesFlash";
import { CategoriaInterface, CategoriaProps } from "resources/js/Interfaces/CategoriaInterface";
import { route } from "ziggy-js";
import { AlertCustomInterface } from "resources/js/Interfaces/AlertCustomInterface";
import AlertCustom from "../../../js/componentes/AlertCustom";


const Home = () => {
  const {datos, flash} = usePage<CategoriaProps>().props;

  const {data, setData, post, put, delete: destroy, processing} = useForm({
      nombre:'' 
    });

  //ventana modal
  const [show, setShow] = useState(false);
  const handleShow = () => setShow(true);
  const handleClose = () => setShow(false);
  const [acciones, setAcciones] = useState(1);
  const [accionesId, setAccionesId] = useState<number | undefined>();
  const handleCrear = ()=>{
      setAcciones(1);
      setData({
        nombre:''
      });
      handleShow();
  };
  const handleEditar=(modulo: CategoriaInterface)=>{
    setAcciones(2);
    setAccionesId(modulo.id);
    setData({
        nombre:modulo.nombre
      });
    handleShow();
  };
  const handleEliminar = (id: number)=>
  {
    if(confirm("¿Realmente desea eliminar el registro?"))
    {
      destroy(route('categorias_delete', {id: id}));
    }
  };

  //método submit formulario
  const handleSubmit = (e: React.FormEvent<HTMLFormElement>)=>{
    e.preventDefault();
    if(data.nombre.trim()=="")
    {
      setAlertData({
        estado: true,
        titulo: "Alerta!!!",
        detalle:"El campo nombre es obligatorio",
        headerBg: "bg-danger"
      });
      setData({
        nombre:''
      });
      return false;
    }
    if(acciones==1)
    {
      post(route('categorias_post'), {
        onSuccess:()=>{
          setData({
            nombre: ''
          })
        }
      })
    }
    if(acciones==2)
    {
      put(route('categorias_put', {id: accionesId}), {
        onSuccess:()=>{
          setData({
            nombre: ''
          })
        }
      });
    }
  };
  //alertcustom
  const [alertData, setAlertData] = useState<AlertCustomInterface>({
    estado: false,
    titulo:"",
    detalle:"",
    headerBg:""
  });
  const handleCloseModal=()=>
  {
    setAlertData(prev=>({
      ...prev,
      estado: false
    }));
  };
  return (
    <>
    <AlertCustom
      estado={alertData.estado}
      titulo={alertData.titulo}
      detalle={alertData.detalle}
      onClose={handleCloseModal}
      headerBg={alertData.headerBg} 
    
    />
    <Head title="Categorías"/>
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
              Categorías
            </li>
          </ol>
        </nav>
        <MensajesFlash flash={flash} />
        <h1>Categorías</h1>

        <p className="d-flex justify-content-end">
          <a href="#" onClick={()=>{handleCrear();}} title="Crear" className="btn btn-outline-success">
            <i className="fas fa-plus"></i> Crear
          </a>
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
              {datos.map((dato:CategoriaInterface)=>(
                <tr key={dato.id}>
                  <td>{dato.id}</td>
                  <td>{dato.nombre}</td>
                  <td className="text-center">
                    <a href="#" onClick={()=>{handleEditar(dato)}}>
                      <i className="fas fa-edit"></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href="#" title="Eliminar" onClick={()=>{ dato.id && handleEliminar(dato.id) }}>
                      <i className="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

      </div>
    </div>
    {/* ventana modal */}
          <Modal show={show} onHide={handleClose} dialogClassName="modal-90w">
            <Modal.Header closeButton>
              <Modal.Title>
                {acciones==1 ? 'Crear': 'Editar'}
              </Modal.Title>
            </Modal.Header>
            <Modal.Body>

                <Form onSubmit={handleSubmit}>
                  <div className="row gy-3">


                  <div className="col-lg-12">
                    <label htmlFor="nombre" className="form-label" style={{fontWeight:"bold"}}>Nombre:</label>
                    <input 
                      type="text"
                      className="form-control"
                      placeholder="Nombre:"
                      value={data.nombre}
                      onChange={(e)=>{setData('nombre', e.target.value)}}
                      />
                  </div>

                  </div>
                  <hr/>
                  <div className="row">
                    <div className="col-6"></div>
                    <div className="col-6 d-flex justify-content-end">
                      <button className="btn btn-primary">
                        {acciones==1 ?
                        (
                          <>
                           <i className="fas fa-plus"></i> Crear
                          </>
                        ):(

                          <>
                          <i className="fas fa-pencil-alt"></i> Editar
                          </>
                        )
                        }
                      </button>
                    </div>
                  </div>
                </Form>
              
            </Modal.Body>
          </Modal>
          {/*
          <Modal show={show} onHide={handleClose} dialogClassName="modal-90w">
            <Modal.Header closeButton>
              <Modal.Title>Título de la modal</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              cuerpo
            </Modal.Body>
          </Modal>
          */}
    {/* fin ventana modal */}
    </>
  )
}

export default Home

{/* 
  <Head title="Categorías"/>
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
              Categorías
            </li>
          </ol>
        </nav>
        <h1>Categorías</h1>
        
        <div className="table-responsive">
          <table className="table table-bordered table-hover table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>
    </div>
  */}