import { Head, Link, useForm, usePage } from "@inertiajs/react"
import { formatTime } from "../../../js/Helpers/Helpers";
import { PageCustomProps } from "resources/js/Interfaces/OpenAIProps";
import { route } from "ziggy-js"
import { Form } from "react-bootstrap";
import { useEffect, useState } from "react";


const ClienteOficial3 = () => {
     
   const {errors, api_response, bucket} = usePage<PageCustomProps>().props;
    //estados locales
    const [respuesta, setRespuesa] =useState('');
    const [tiempo, setTiempo] = useState(0);
    const [preguntaEnviada, setPreguntaEnviada] = useState('');
    const [url, setUrl] = useState('');

   const {data, setData, post, processing} = useForm({
    pregunta: 'yoda bebiendo coca cola'
   });
    const handleSubmit=(e: React.FormEvent<HTMLFormElement>)=>
    {
      e.preventDefault();
      post(route('openai_cliente_oficial_3_post'), {
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
        setUrl(`${api_response.url}`);
      }
    }, [ api_response]) 
  return (
    <>
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
              <li className="breadcrumb-item active" aria-current="page">Generación de imagen con Cliente oficial de Laravel y dall-e-3</li>
            </ol>
          </nav> 
          <h1>Generación de imagen con Cliente oficial de Laravel y dall-e-3</h1>

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
                        Pregunta: "{preguntaEnviada}" <br/> URL: {url}
                      </small>
                    )}
                </div>
                <div className="card-body">
                  
                  <img src={`/s3/${bucket}/${respuesta}`} width="50%" />

                </div>
                </>
              )}
              

            </div>

        </div>
      </div>
    </>
  )
}

export default ClienteOficial3
 