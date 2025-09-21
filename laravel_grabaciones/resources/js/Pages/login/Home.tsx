import { Head, Link, useForm, usePage } from "@inertiajs/react"
import ToastFlash from "../../../js/componentes/ToastFlash"
import { route } from "ziggy-js"
import { LoginProps } from "resources/js/Interfaces/LoginProps";
import { Form } from "react-bootstrap";


const Home = () => {
  const {errors} = usePage().props as LoginProps;
  const {data, setData, post, processing} = useForm({
    correo: '',
    password: ''
  });
   const handleSubmit=(e: React.FormEvent<HTMLFormElement>)=>{
    e.preventDefault();
    post(route('login_post') );
  };
  return (
    <>
    <Head title="Login"/>
   <div className="row">
      <div className="col-12">
        <nav aria-label='breadcrumb'>
          <ol className="breadcrumb">
            <li className="breadcrumb-item">
              <Link href={route('home_index')}>
                <i className="fas fa-home"></i>
              </Link>
            </li>
            <li className="breadcrumb-item active" aria-current='page'>
              Login
            </li>
          </ol>
        </nav>
        <ToastFlash />
        <h1>Login</h1>
       
        <Form onSubmit={handleSubmit}>
          <div className="row">

             <div className="mb-3">
                <label htmlFor="correo" className="form-label" style={{fontWeight:"bold"}}>Correo:</label>
                  <input 
                      type="email"
                      className={`form-control ${errors?.correo ? 'is-invalid': ''}`}
                      placeholder="Correo:"
                      value={data.correo}
                      onChange={(e)=>{setData('correo', e.target.value)}}
                      disabled={processing}
                      />
                      {errors?.correo && (
                        <div className="invalid-feedback">{errors.correo}</div>
                      )}
                </div>

                <div className="mb-3">
                <label htmlFor="password" className="form-label" style={{fontWeight:"bold"}}>Contraseña:</label>
                  <input 
                      type="password"
                      className={`form-control ${errors?.password ? 'is-invalid': ''}`}
                      placeholder="Contraseña:"
                      value={data.password}
                      onChange={(e)=>{setData('password', e.target.value)}}
                      disabled={processing}
                      />
                      {errors?.password && (
                        <div className="invalid-feedback">{errors.password}</div>
                      )}
                </div>

                <div className="mb-3">
                  <button type="submit" className="btn btn-danger" disabled={processing}>
                    <i className="fas fa-arrow-up"></i> {processing ? 'Enviando':'Enviar'}
                  </button>
                </div>

          </div>
        </Form>

      </div>
    </div>
    </>
  )
}

export default Home