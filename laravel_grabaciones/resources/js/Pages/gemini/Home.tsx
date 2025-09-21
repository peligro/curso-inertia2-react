import { Head, Link } from "@inertiajs/react"
import { route } from "ziggy-js"


const Home = () => {
  return (
    <>
    <Head title="Gemini" />
      <div className="row">
        <div className="col-12">
          <nav aria-label="breadcrumb">
            <ol className="breadcrumb">
              <li className="breadcrumb-item">
                <Link href={route('gemini_index')}><i className="fas fa-home"></i></Link>
              </li>
              <li className="breadcrumb-item active" aria-current="page">Gemini</li>
            </ol>
          </nav> 
          <h1>Gemini</h1>
          
          <ul>
            <li>
              <Link href={route('gemini_ejemplo_1')}>Via API</Link>
            </li>
            <li>
              <Link href={route('gemini_ejemplo_2')}>Chatbot API</Link>
            </li>
            <li>
              <Link href={route('gemini_ejemplo_3')}>Consulta simple</Link>
            </li>
            <li>
              <Link href={route('gemini_ejemplo_4')}>Google Gemini PHP</Link>
            </li>
            <li>
              <Link href={route('gemini_ejemplo_5')}>Google Gemini PHP Chatbot</Link>
            </li>
            <li>
              <Link href={route('gemini_ejemplo_6')}>Reconocimiento de imágenes Google Gemini PHP</Link>
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