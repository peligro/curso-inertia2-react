import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { Form } from "react-bootstrap";
import { useState, useEffect } from "react";
import { PageCustomLinkedinProps } from "resources/js/Interfaces/OpenAIProps";
import { AlertCustomInterface } from "resources/js/Interfaces/AlertCustomInterface";
import AlertCustom from "../../../js/componentes/AlertCustom";
import MensajesFlash from "../../../js/componentes/MensajesFlash";

const ClienteOficial4 = () => {
    // Obtener props de la página
    const { errors, api_response, flash, aws_bucket } = usePage<PageCustomLinkedinProps>().props;
   
    // Estados locales
    const [publicacion, setPublicacion] = useState({
        titulo: '',
        texto: '',
        imagen_url: '',
        tiempo: 0,
        pregunta_enviada: '',
        success: false, 
    });

    // Estado para los campos editables
    const [editableFields, setEditableFields] = useState({
        titulo: '',
        texto: '',
        imagen_url: ''
    });

    // Form handler
    const { data, setData, post, processing } = useForm({
        pregunta: '',
    });


    // Actualizamos estado de la respuesta con useEffect
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

    // Manejar cambios en los campos editables
    const handleFieldChange = (field: string, value: string) => {
        setEditableFields(prev => ({
            ...prev,
            [field]: value
        }));
    };

     

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        
        post(route('openai_cliente_oficial_4_crear_publicacion_post'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                setData('pregunta', '');
            },
            onError: (errors) => {
                console.log('Errores:', errors);
            }
        });
    }
    //enviamos formulario publicación
    const { data: publicacionData, setData: setPublicacionData, post: postPublicacion, processing: processingPublicacion } = useForm({
    titulo: '',
    texto: '',
    imagen_url: ''
});
     const handlePublicacionSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (editableFields.titulo.length == 0 && editableFields.titulo == "") {

            setAlertData({
                estado: true,
                titulo: "Alerta !!!",
                detalle: "El campo título es obligatorio",
                headerBg: "bg-danger"
            });
            handleFieldChange('titulo', publicacion.titulo)
            return false;
        }
        if (editableFields.texto.length == 0 && editableFields.texto == "") {

            setAlertData({
                estado: true,
                titulo: "Alerta !!!",
                detalle: "El campo texto es obligatorio",
                headerBg: "bg-danger"
            });
            handleFieldChange('texto', publicacion.texto)
            return false;
        }
        if (editableFields.imagen_url.length == 0 && editableFields.imagen_url == "") {

            setAlertData({
                estado: true,
                titulo: "Alerta !!!",
                detalle: "El campo URL de la imagen es obligatorio",
                headerBg: "bg-danger"
            });
            handleFieldChange('imagen_url', publicacion.imagen_url )
            return false;
        }
        postPublicacion(route('openai_cliente_oficial_4_crear_publicacion_save_post'))
    }
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
        <AlertCustom
                estado={alertData.estado}
                titulo={alertData.titulo}
                detalle={alertData.detalle}
                onClose={handleCloseModal}
                headerBg={alertData.headerBg} // Pasa el valor del estado
            />
            <Head title="Openia" />
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
                    <MensajesFlash flash={flash} />
                    <h1>Crear publicación con Cliente oficial de Laravel y dall-e-3</h1>
                    
                    <div className="card mt-4">
                        <div className="card-header">
                            <h3 className="card-title">Promt</h3>
                        </div>
                        <div className="card-body">
                            <Form onSubmit={handleSubmit}>
                                <div className="row">
                                    <div className="mb-3">
                                        <label htmlFor="pregunta" className="form-label">Promt</label>
                                        <textarea 
                                            className={`form-control ${errors?.pregunta ? 'is-invalid' : ''}`}
                                            id="pregunta"
                                            value={data.pregunta}
                                            onChange={e => setData('pregunta', e.target.value)}
                                            placeholder="Escribe tu Promt para generar la imagen aquí..."
                                            disabled={processing}
                                            rows={4}
                                        ></textarea>
                                        
                                        {errors?.pregunta && (
                                            <div className="invalid-feedback">{errors.pregunta}</div>
                                        )}
                                    </div>

                                    <div className="mb-3">
                                        <button 
                                            type="submit" 
                                            className='btn btn-danger' 
                                            disabled={processing}
                                        >
                                            <i className="fas fa-arrow-up"></i> 
                                            {processing ? 'Enviando...' : 'Enviar'}
                                        </button>
                                    </div>
                                </div>
                            </Form>
                        </div>

                         {/* Mostrar publicación completa si existe */}
                        {publicacion.titulo && publicacion.success !== false && (
                            <>
                                <div className="card-header">
                                    <h3 className="card-title">Publicación para LinkedIn Generada</h3>
                                    <h5>Se tomó {publicacion.tiempo} ms</h5>
                                    {publicacion.pregunta_enviada && (
                                        <small className="text-muted">
                                            Prompt: "{publicacion.pregunta_enviada}" 
                                            <br/> URL: {api_response?.url}
                                        </small>
                                    )}
                                </div>
                                <div className="card-body">
                                    {/* Formulario para Título */}
                                    <Form onSubmit={handlePublicacionSubmit}>
<div className="mb-4">
                                        <label htmlFor="titulo" className="form-label fw-bold">Título:</label>
                                        <textarea
                                            className="form-control"
                                            id="titulo"
                                            value={editableFields.titulo}
                                            onChange={(e) => handleFieldChange('titulo', e.target.value)}
                                            rows={2}
                                            placeholder="Edita el título aquí..."
                                        />
                                     
                                    </div>
 
                                    <div className="mb-4">
                                        <label htmlFor="texto" className="form-label fw-bold">Texto de la publicación:</label>
                                        <textarea
                                            className="form-control"
                                            id="texto"
                                            value={editableFields.texto}
                                            onChange={(e) => handleFieldChange('texto', e.target.value)}
                                            rows={6}
                                            placeholder="Edita el texto aquí..."
                                            style={{ whiteSpace: 'pre-line', lineHeight: '1.6' }}
                                        />
                                      
                                    </div>
 
                                    <div className="mb-4">
                                        <label htmlFor="imagen_url" className="form-label fw-bold">URL de la imagen:</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="imagen_url"
                                            value={editableFields.imagen_url}
                                            onChange={(e) => handleFieldChange('imagen_url', e.target.value)}
                                            placeholder="Edita la URL de la imagen aquí..."
                                        />
                                      
                                    </div>
                                    {/* Vista previa de la imagen */}
                                    {editableFields.imagen_url && (
                                        <div className="mb-4 text-center">
                                            <h6 className="fw-bold mb-3">Vista previa de la imagen:</h6>
                                            <img 
                                                src={`/s3/${aws_bucket}/${editableFields.imagen_url}`} 
                                                alt="Imagen generada para la publicación" 
                                                className="img-fluid rounded"
                                                style={{ maxWidth: '100%', height: 'auto', maxHeight: '400px' }}
                                                onError={(e) => {
                                                    (e.target as HTMLImageElement).src = 'https://via.placeholder.com/400x400?text=Imagen+no+disponible';
                                                }}
                                            />
                                            <small className="text-muted d-block mt-2">
                                                Imagen generada con DALL-E 3
                                            </small>
                                        </div>
                                    )}

                                    {/* Botones de acción */}
                                    <div className="d-flex gap-2 mt-4 flex-wrap">
                                        <button 
                                            className="btn btn-outline-danger"
                                            onClick={() => {}}
                                        >
                                            <i className="fas fa-copy me-2"></i>
                                            Crear publicación
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
    );
};

export default ClienteOficial4;