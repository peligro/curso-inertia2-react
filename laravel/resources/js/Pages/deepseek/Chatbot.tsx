import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { Form } from "react-bootstrap";
import { useState, useEffect } from "react";
import { PageCustomProps } from "resources/js/Interfaces/OpenAIProps";


const Chatbot = () => {
    // Obtener props de la página
    const { errors, api_response } = usePage<PageCustomProps>().props;
    
    // Estados locales
    const [respuesta, setRespuesta] = useState('');
    const [tiempo, setTiempo] = useState(0);
    const [preguntaEnviada, setPreguntaEnviada] = useState('');

    // Form handler
    const { data, setData, post, processing } = useForm({
        pregunta: '',
    });

    // Actualizamos estado de la respuesta con useEffect
    useEffect(() => {
        if (api_response) {
            setRespuesta(api_response.respuesta);
            setTiempo(api_response.tiempo);
            setPreguntaEnviada(api_response.pregunta_enviada);
        }
    }, [api_response]);

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        
        post(route('deepseek_chatbot_api_post'), {
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

    return (
        <>
            <Head title="Openia" />
            <div className="row">
                <div className="col-12">
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb">
                            <li className="breadcrumb-item">
                                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
                            </li>
                            <li className="breadcrumb-item">
                                <Link href={route('deepseek_index')}>Deepseek</Link>
                            </li>
                            <li className="breadcrumb-item active" aria-current="page">Chatbot</li>
                        </ol>
                    </nav> 
                    <h1>Chatbot</h1>
                    
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
                                            className={`form-control ${errors?.pregunta ? 'is-invalid' : ''}`}
                                            id="pregunta"
                                            value={data.pregunta}
                                            onChange={e => setData('pregunta', e.target.value)}
                                            placeholder="Escribe tu pregunta aquí..."
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

                        {/* Mostrar respuesta solo si existe */}
                        {respuesta && (
                            <>
                                <div className="card-header">
                                    <h3 className="card-title">Respuesta de la IA</h3>
                                    <h5>Se tomó {tiempo} ms</h5>
                                    {preguntaEnviada && (
                                        <small className="text-muted">
                                            Pregunta: "{preguntaEnviada}"
                                        </small>
                                    )}
                                </div>
                                <div className="card-body">
                                    <div className="alert alert-info">
                                        <pre className="mb-0" style={{ whiteSpace: 'pre-wrap' }}>
                                            {respuesta}
                                        </pre>
                                    </div>
                                </div>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
};

export default Chatbot;

 