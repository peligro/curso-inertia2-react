import { Head, Link, useForm, usePage } from "@inertiajs/react"
import { formatTime } from "../../../js/Helpers/Helpers";
import { PageCustomProps } from "resources/js/Interfaces/OpenAIProps";
import { route } from "ziggy-js"
import { Form } from "react-bootstrap";
import { useEffect, useState } from "react";


const Chatbot = () => {
     
   const {errors, api_response} = usePage<PageCustomProps>().props;
   
    //estados locales
    const [respuesta, setRespuesa] =useState('');
    const [tiempo, setTiempo] = useState(0);
    const [preguntaEnviada, setPreguntaEnviada] = useState('');

   const {data, setData, post, processing} = useForm({
    pregunta: ''
   });
    const handleSubmit=(e: React.FormEvent<HTMLFormElement>)=>
    {
      e.preventDefault();
      post(route('deepseek_chatbot_post'), {
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

    useEffect(() => {
      if(api_response)
      {
        setRespuesa(api_response.respuesta);
        setTiempo(api_response.tiempo);
        setPreguntaEnviada(api_response.pregunta_enviada);
      }
    }, [ api_response]) 
  return (
    <>
    <Head title="Deepseek" />
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
              <li className="breadcrumb-item active" aria-current="page">Chatbot API</li>
            </ol>
          </nav> 
          <h1>Chatbot API</h1>

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

              
              
              {respuesta && (
                <>
                <div className="card-header">
                    <h3 className="card-title">Respuesta de la IA</h3>
                    <h5>Se tomó {formatTime(tiempo)}</h5>
                    {preguntaEnviada && (
                      <small className="text-muted">
                        Pregunta: "{preguntaEnviada}"
                      </small>
                    )}
                </div>
                <div className="card-body">
                  
                  <div className="alert alert-info">
                    <pre className="mb-0" style={{whiteSpace: 'pre-wrap'}}>
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
  )
}

export default Chatbot
