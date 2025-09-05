import { Link, useForm } from "@inertiajs/react"
import { route } from "ziggy-js"

const Home = () => {
  
     const { data, setData  } = useForm({
        nombre: ''
    });
    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        alert(data.nombre)
    } 
  return (
    <>
      <div className="row">
        <div className="col-12">
             <nav aria-label="breadcrumb">
                <ol className="breadcrumb">
                    <li className="breadcrumb-item">
                        <Link href={route('home_index')}><i className="fas fa-home"></i></Link>
                    </li>
                    <li className="breadcrumb-item active" aria-current="page">Formulario</li>
                </ol>
            </nav>
          <h1>Formulario</h1>
          <form onSubmit={handleSubmit}>
                <div className="row">
                    <div className="mb-3">
                        <label htmlFor="nombre" className="form-label">Nombre</label>
                        <input
                            className='form-control'
                            type="text"
                            id="nombre"
                            value={data.nombre}
                            onChange={e => setData('nombre', e.target.value)}
                            placeholder="Nombre"
                        />
                    </div>

                  

                    <div className="mb-3">
                        <button type="submit" className='btn btn-danger' >
                            <i className="fas fa-arrow-up"></i> Enviar
                        </button>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </>
  )
}

export default Home