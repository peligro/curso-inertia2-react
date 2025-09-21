import { Head, Link } from "@inertiajs/react"
import { route } from "ziggy-js"


const Home = () => {
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
              <li className="breadcrumb-item active" aria-current="page">Deepseek</li>
            </ol>
          </nav> 
          <h1>Deepseek</h1>
          
          <ul>
            <li>
              <Link href={route('deepseek_api')}>Via API</Link>
            </li>
            <li>
              <Link href={route('deepseek_chatbot')}>Chatbot API</Link>
            </li>
            <li>
              <Link href={route('deepseek_consulta_simple')}>Consulta simple</Link>
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