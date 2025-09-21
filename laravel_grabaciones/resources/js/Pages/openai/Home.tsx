import { Head, Link } from "@inertiajs/react"
import { route } from "ziggy-js"


const Home = () => {
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
              <li className="breadcrumb-item active" aria-current="page">Openai</li>
            </ol>
          </nav> 
          <h1>Openai</h1>
          
          <ul>
            <li>
              <Link href={route('openai_api')}>Via API</Link>
            </li>
            <li>
              <Link href={route('openai_chatbot_api')}>Chatbot API</Link>
            </li>
            <li>
              <Link href={route('openai_consulta_simple')}>Consulta simple</Link>
            </li>
            <li>
              <Link href={route('openai_cliente_oficial_1')}>Cliente oficial Laravel</Link>
            </li>
            <li>
              <Link href={route('openai_cliente_oficial_2')}>Chatbot Cliente oficial Laravel</Link>
            </li>
            <li>
              <Link href={route('openai_cliente_oficial_3')}>Generación de imagen con Cliente oficial de Laravel y dall-e-3</Link>
            </li>
            <li>
              <Link href={route('openai_cliente_oficial_4')}>Crear publicación con Cliente oficial de Laravel y dall-e-3</Link>
            </li>
          </ul>

        </div>
      </div>
    </>
  )
}

export default Home
{/* 
  <Head title="Openia" />
      <div className="row">
        <div className="col-12">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item">
                <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Openia</li>
            </ol>
          </nav> 
          <h1>Openia</h1>
          
          

        </div>
      </div>
  */}