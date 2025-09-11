import { Head, Link, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";
import { InertiaPageProps } from "resources/js/Interfaces/OpenAIProps";
import { formatTime } from "../../../js/Helpers/Helpers";

const ClienteOficial1 = () => {
   const { props } = usePage<{ props: InertiaPageProps }>();
  const { respuesta, pregunta, tiempo } = props;

  // Asegurar que response es string
  const responseText = typeof respuesta === 'string' ? respuesta : String(respuesta);
  const preguntaText = typeof pregunta === 'string' ? pregunta : String(pregunta);
  const tiempoNumber = typeof tiempo === 'number' ? tiempo : Number(tiempo);

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
                <Link href={route('openai_index')}>Openai</Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Cliente Oficial de Laravel</li>
            </ol>
          </nav> 
          <h1>Cliente Oficial de Laravel</h1>
          <p>https://github.com/openai-php/laravel</p>
          
          <div className="card mt-4">
            
            <div className="card-header">
              <h3 className="card-title">Pregunta</h3>
            </div>
            <div className="card-body">
              <div className="alert alert-warning">
                {preguntaText}
              </div>
            </div>


            <div className="card-header">
              <h3 className="card-title">Respuesta de la IA</h3>
              <h5>Se tomó {formatTime(tiempoNumber)}</h5>
            </div>
            <div className="card-body">
              {respuesta ? (
                <div className="alert alert-info">
                  <pre className="mb-0" style={{ whiteSpace: 'pre-wrap' }}>
                    {responseText}
                  </pre>
                </div>
              ) : (
                <div className="alert alert-warning">
                  No se recibió respuesta de la IA
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default ClienteOficial1;