import { Head, Link, useForm, usePage } from "@inertiajs/react"
import { formatTime } from "../../../js/Helpers/Helpers";
import { PageCustomLinkedinProps } from "resources/js/Interfaces/OpenAIProps";
import { route } from "ziggy-js"
import { Form } from "react-bootstrap";
import { useEffect, useState } from "react";
import { AlertCustomInterface } from "resources/js/Interfaces/AlertCustomInterface";
import AlertCustom from "../../../js/componentes/AlertCustom";
import ToastFlash from "../../../js/componentes/ToastFlash";


const ClienteOficial4 = () => {
     
   const {errors, api_response, bucket, flash} = usePage<PageCustomLinkedinProps>().props;
  
   //Estados locales
    const [publicacion, setPublicacion] = useState({
      titulo:'',
      texto:'',
      imagen_url:'',
      tiempo: 0,
      pregunta_enviada:'',
      success: false
    });
   //Estado para los campos editables
   const [editableFields, setEditableFields] = useState({
    titulo:'',
    texto: '',
    imagen_url:''
   });
   //manejar cambios en los campos editables
   const handleFieldChance=(field: string, value: string)=>
   { 
      setEditableFields(prev =>({
        ...prev,
        [field]:value
      }))
   };
   useEffect(() => {
        if (api_response) {
            const nuevaPublicacion = {
                titulo: api_response.titulo || '',
                texto: api_response.texto || '',
                imagen_url: api_response.imagen_url || '',
                tiempo: api_response.tiempo || 0,
                pregunta_enviada: api_response.pregunta_enviada || '',
                success: api_response.success || false
            };
            
            setPublicacion(nuevaPublicacion);
            
            // Inicializar los campos editables con los valores de la API
            setEditableFields({
                titulo: nuevaPublicacion.titulo,
                texto: nuevaPublicacion.texto,
                imagen_url: nuevaPublicacion.imagen_url
            });
             setPublicacionData({
            titulo: nuevaPublicacion.titulo,
            texto: nuevaPublicacion.texto,
            imagen_url: nuevaPublicacion.imagen_url
        });
        }
    }, [api_response]);
   //enviamos formulario publicación
   const {data: publicacionData, setData: setPublicacionData, post: postPublicacionData, processing: processingPublicacionData} = useForm({
    titulo:'',
    texto: '',
    imagen_url:''
   });

   const {data, setData, post, processing} = useForm({
    pregunta: 'ayúdame a escribir un artículo para linkedin sobre realidad PHP con Inertia'
   });
    const handleSubmit=(e: React.FormEvent<HTMLFormElement>)=>
    {
      e.preventDefault();
      post(route('openai_cliente_oficial_4_post'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess:()=>{
          setData('pregunta', '');
        },
        onError: (err)=>{
          console.log('Errores:'+ err);
        }
      });
    };

    const handlePublicacionSubmit=(e: React.FormEvent<HTMLFormElement>)=>
    {
      e.preventDefault();
      if(editableFields.titulo.length==0 && editableFields.titulo=="")
      {
        setAlertData({
          estado: true,
          titulo: 'Alerta!!!',
          detalle:"El campo título es obligatorio",
          headerBg: "bg-danger"
        });
        handleFieldChance('titulo', publicacion.titulo);
        return false;
      }
      if(editableFields.texto.length==0 && editableFields.texto=="")
      {
        setAlertData({
          estado: true,
          titulo: 'Alerta!!!',
          detalle:"El campo texto es obligatorio",
          headerBg: "bg-danger"
        });
        handleFieldChance('texto', publicacion.texto);
        return false;
      }
      if(editableFields.imagen_url.length==0 && editableFields.imagen_url=="")
      {
        setAlertData({
          estado: true,
          titulo: 'Alerta!!!',
          detalle:"El campo imagen_url es obligatorio",
          headerBg: "bg-danger"
        });
        handleFieldChance('imagen_url', publicacion.imagen_url);
        return false;
      }
      postPublicacionData(route('openai_cliente_oficial_4_post_save'));
    }
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
    <Head title="Openai" />
      <div className="row">
        <div className="col-12">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item">
                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
              </li>
              <li className="breadcrumb-item">
                <Link href={route('openai_index')}>Openai</Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Crear publicación con Cliente oficial de Laravel y dall-e-3</li>
            </ol>
          </nav> 
          <ToastFlash />
          <h1>Crear publicación con Cliente oficial de Laravel y dall-e-3</h1>

            <div className="card mt-4">
              
              <div className="card-header">
                <h3 className="card-title">Pregunta</h3>
              </div>

              <div className="card-body">
                  
                  <Form onSubmit={handleSubmit}>
                    <div className="row">

                      <div className="mb-3">
                        <label htmlFor="pregunta" className="form-label">Pregunta</label>
                        <textarea 
                        id="pregunta"
                        className="form-control"
                        value={data.pregunta}
                        onChange={e=>setData('pregunta', e.target.value)}
                        placeholder="Escribe tu pregunta aquí...."
                        rows={4}
                        disabled={processing}></textarea>
                      </div>
                      {errors?.pregunta && (
                        <div className="invalid-feedback">{errors?.pregunta}</div>
                      )}
                    </div>
                    
                    <div className="mb-3">
                      <button className="btn btn-danger" type="submit" disabled={processing}>
                        <i className="fas fa-arro-up"></i> {processing ? 'Enviando': 'Enviar'}
                      </button>
                    </div>

                  </Form>

              </div>

               {publicacion.titulo && publicacion.success!==false && (
                <>
              
                  <div className="card-header">
                    <h3 className="card-title">Publicación para LinkedIn generada</h3>
                    <h5>Se tomó {formatTime(publicacion.tiempo)}</h5>
                    <small className="text-muted">
                      Prompt: "{publicacion.pregunta_enviada}"
                      <br />
                      URL: {api_response?.url}
                    </small>
                  </div>
                  <div className="card-body">
                    <Form onSubmit={handlePublicacionSubmit}>
                      <div className="mb-4">
                        <label htmlFor="titulo" className="form-label fw-bold">Título</label>
                        <textarea
                        className="form-control"
                        id="titulo"
                        value={editableFields.titulo}
                        onChange={e=>handleFieldChance('titulo', e.target.value)}
                        rows={2}
                        placeholder="Edita el título aquí...."></textarea>
                      </div>

                      <div className="mb-4">
                        <label htmlFor="texto" className="form-label fw-bold">Texto de la publicación</label>
                        <textarea
                        className="form-control"
                        id="texto"
                        value={editableFields.texto}
                        onChange={e=>handleFieldChance('texto', e.target.value)}
                        rows={2}
                        placeholder="Edita el texto aquí...."
                        style={{whiteSpace:'pre-line', lineHeight: '1.6'}}></textarea>
                      </div>

                      <div className="mb-4">
                        <label htmlFor="imagen_url" className="form-label fw-bold">URL de la publicación</label>
                        <input 
                        type="text" 
                        className="form-control"
                        id="imagen_url"
                        value={editableFields.imagen_url}
                        onChange={(e)=>handleFieldChance('imagen_url', e.target.value)}
                        placeholder="Edita la URL de la imagen aquí..."
                        />
                      </div>
                      {editableFields.imagen_url && (
                        <>
                        <div className="mb-4 text-center">
                          <h6 className="fw-bold mb-3">Vista previa de la imagen</h6>
                          <img 
                          src={`/s3/${bucket}/${editableFields.imagen_url}`} 
                          alt="Imagen generada para la publicación de la IA"
                          style={{maxWidth: '100%', height: 'auto', maxHeight:'400px'}}
                          onError={(e)=>{
                            (e.target as HTMLImageElement).src = 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg';
                          }} />
                          <small className="text-muted d-block mt-2">
                            imagen generada con DALL-E-3
                          </small>
                        </div>
                        </>
                      )}
                      <div className="d-flex gap-2 mt-4 flex-wrap">
                        <button className="btn btn-outline-danger" type="submit">
                          <i className="fas fa-copy me-2"></i> Crear publicación
                        </button>
                      </div>
                    </Form>
                  </div>
                </>
               )}
              
              
              

            </div>

        </div>
      </div>
    </>
  )
}

export default ClienteOficial4
 