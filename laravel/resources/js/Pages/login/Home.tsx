import { Head, useForm, usePage } from "@inertiajs/react"
import { route } from "ziggy-js";
import { Breadcrumb } from "react-bootstrap";
import { LoginProps } from "resources/js/Interfaces/LoginProps";
import ToastFlash from "../../../js/componentes/ToastFlash";
 
const Home = () => {
    const { errors } = usePage().props as LoginProps;
    const { data, setData, post, processing } = useForm({
        correo: '',
        password: ''
    });
    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route('login_post'));
    }

    return (
        <>
            <Head title="Login" />
            <div className="row">
                <div className="col-12">
                    <Breadcrumb>
                        <Breadcrumb.Item href="/"><i className="fas fa-home"></i></Breadcrumb.Item>
                        <Breadcrumb.Item active>Login</Breadcrumb.Item>
                    </Breadcrumb>


                    <h1>Login</h1>
                     <ToastFlash />
                    <form onSubmit={handleSubmit} encType="multipart/form-data">
                        <div className="row">



                            <div className="mb-3">
                                <label htmlFor="nombre" className="form-label" style={{fontWeight: "bold"}}>Correo</label>
                                <input
                                    className={`form-control ${errors?.correo ? 'is-invalid' : ''}`}
                                    type="email"
                                    id="nombre"
                                    value={data.correo}
                                    onChange={e => setData('correo', e.target.value)}
                                    placeholder="Nombre"
                                    disabled={processing}
                                />
                                {errors?.correo && (
                                    <div className="invalid-feedback">{errors.correo}</div>
                                )}
                            </div>



                            <div className="mb-3">
                                <label htmlFor="password" className="form-label" style={{fontWeight: "bold"}}>Contraseña</label>
                                <input
                                    className={`form-control ${errors?.password ? 'is-invalid' : ''}`}
                                    type="password"
                                    id="password"
                                    value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                    placeholder="Contraseña"
                                    disabled={processing}
                                />
                                {errors?.password && (
                                    <div className="invalid-feedback">{errors.password}</div>
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
                    </form>
                </div>
            </div>
        </>
    );
}

export default Home;


{/*
    const Home = () => {
    const { errors, flash } = usePage().props as LoginProps;
    const { data, setData, post, put, delete: destroy, processing } = useForm({
        correo: '',
        password: ''
    });
    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
    }

    return (
        <>
            <Head title="Login" />
            <div className="row">
                <div className="col-12">
                    <Breadcrumb>
                        <Breadcrumb.Item href="/"><i className="fas fa-home"></i></Breadcrumb.Item>
                        <Breadcrumb.Item active>Login</Breadcrumb.Item>
                    </Breadcrumb>


                    <h1>Login</h1>
                    <form onSubmit={handleSubmit} encType="multipart/form-data">
                        <div className="row">



                            <div className="mb-3">
                                <label htmlFor="nombre" className="form-label">Correo</label>
                                <input
                                    className={`form-control ${errors?.correo ? 'is-invalid' : ''}`}
                                    type="text"
                                    id="nombre"
                                    value={data.correo}
                                    onChange={e => setData('correo', e.target.value)}
                                    placeholder="Nombre"
                                    disabled={processing}
                                />
                                {errors?.correo && (
                                    <div className="invalid-feedback">{errors.correo}</div>
                                )}
                            </div>



                            <div className="mb-3">
                                <label htmlFor="password" className="form-label">Contraseña</label>
                                <input
                                    className={`form-control ${errors?.password ? 'is-invalid' : ''}`}
                                    type="password"
                                    id="password"
                                    value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                    placeholder="Contraseña"
                                    disabled={processing}
                                />
                                {errors?.password && (
                                    <div className="invalid-feedback">{errors.password}</div>
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
                    </form>
                </div>
            </div>
        </>
    );
}

    */}