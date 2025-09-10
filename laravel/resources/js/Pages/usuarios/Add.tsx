import { Head, useForm, usePage } from "@inertiajs/react"
import { Breadcrumb } from "react-bootstrap";
import MensajesFlash from "../../../js/componentes/MensajesFlash";
import { UsuariosFormularioProps } from "resources/js/Interfaces/UsuariosFormulario";
import { PerfilInterface } from "resources/js/Interfaces/UsuariosInterface";
import { route } from "ziggy-js";
 
const Add = () => {
     const { perfiles, errors, flash } = usePage<UsuariosFormularioProps>().props
    const { data, setData, post, processing } = useForm({
        perfil_id:'0',
        nombre: '',
        correo: '',
        password: '',
        password_confirmation:'',
        telefono: ''
    }); 
    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route('usuarios_add_post'))
    }
    // Verificar si hay errores
    const hasErrors = errors && Object.keys(errors).length > 0;
    return (
        <>
            <Head title="Login" />
            <div className="row">
                <div className="col-12">
                    <Breadcrumb>
                        <Breadcrumb.Item href="/"><i className="fas fa-home"></i></Breadcrumb.Item>
                        <Breadcrumb.Item href="/usuarios">Usuarios</Breadcrumb.Item>
                        <Breadcrumb.Item active>Crear</Breadcrumb.Item>
                    </Breadcrumb>


                    <h1>Crear</h1>
                    {/* Mostrar errores generales en una lista UL/LI */}
                    {hasErrors && (
                        <div className="alert alert-danger alert-dismissible fade show">
                            <h5 className="alert-heading">Errores de validación:</h5>
                            <ul className="mb-0">
                                {Object.entries(errors).map(([field, message]) => (
                                    <li key={field}>{message}</li>
                                ))}
                            </ul>
                            <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    )}
                    <MensajesFlash flash={flash}/>
                    <form onSubmit={handleSubmit} encType="multipart/form-data">
                        <div className="row">

                            <div className="mb-3">
                                <label htmlFor="perfil_id" className="form-label">Perfil</label>
                                <select 
                                id="perfil_id" 
                                value={data.perfil_id}
                                className="form-control"
                                onChange={e => setData('perfil_id', e.target.value)}>
                                    <option value="0">Seleccione.....</option>
                                    {perfiles.map((perfil: PerfilInterface) => (
                                        <option key={perfil.id} value={perfil.id}>
                                            {perfil.nombre}
                                        </option>
                                    ))}
                                </select>
                                
                            </div>

                            <div className="mb-3">
                                <label htmlFor="nombre" className="form-label">Nombre</label>
                                <input
                                    className="form-control"
                                    type="text"
                                    id="nombre"
                                    value={data.nombre}
                                    onChange={e => setData('nombre', e.target.value)}
                                    placeholder="Nombre"
                                />
                                
                            </div>

                            <div className="mb-3">
                                <label htmlFor="correo" className="form-label">Correo</label>
                                <input
                                    className="form-control"
                                    type="email"
                                    id="correo"
                                    value={data.correo}
                                    onChange={e => setData('correo', e.target.value)}
                                    placeholder="Correo"
                                />
                                
                            </div>

                            <div className="mb-3">
                                <label htmlFor="telefono" className="form-label">Teléfono</label>
                                <input
                                    className="form-control"
                                    type="text"
                                    id="telefono"
                                    value={data.telefono}
                                    onChange={e => setData('telefono', e.target.value)}
                                    placeholder="Teléfono"
                                />
                                
                            </div>

                            <div className="mb-3">
                                <label htmlFor="password" className="form-label">Contraseña</label>
                                <input
                                    className="form-control"
                                    type="password"
                                    id="password"
                                    value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                    placeholder="Contraseña"
                                />
                            </div>

                            <div className="mb-3">
                                <label htmlFor="password_confirmation" className="form-label">Repetir Contraseña</label>
                                <input
                                    className="form-control"
                                    type="password"
                                    id="password_confirmation"
                                    value={data.password_confirmation}
                                    onChange={e => setData('password_confirmation', e.target.value)}
                                    placeholder="Repetir Contraseña"
                                    required
                                />
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

export default Add;

{/* 
    const Add = () => {
    const { errors } = usePage().props as LoginProps;
    const { data, setData, processing } = useForm({
        perfil_id:'0',
        nombre: '',
        correo: '',
        password: '',
        telefono: ''
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
                        <Breadcrumb.Item href="/usuarios">Usuarios</Breadcrumb.Item>
                        <Breadcrumb.Item active>Crear</Breadcrumb.Item>
                    </Breadcrumb>


                    <h1>Crear</h1>
                    <form onSubmit={handleSubmit} encType="multipart/form-data">
                        <div className="row">

                            <div className="mb-3">
                                <label htmlFor="perfil_id" className="form-label">Perfil</label>
                                <select 
                                id="perfil_id" 
                                value={data.perfil_id}
                                className="form-control"
                                onChange={e => setData('perfil_id', e.target.value)}>
                                    <option value="0">Seleccione.....</option>
                                </select>
                                
                            </div>

                            <div className="mb-3">
                                <label htmlFor="nombre" className="form-label">Nombre</label>
                                <input
                                    className="form-control"
                                    type="text"
                                    id="nombre"
                                    value={data.nombre}
                                    onChange={e => setData('nombre', e.target.value)}
                                    placeholder="Nombre"
                                />
                                
                            </div>

                            <div className="mb-3">
                                <label htmlFor="correo" className="form-label">Correo</label>
                                <input
                                    className="form-control"
                                    type="email"
                                    id="correo"
                                    value={data.correo}
                                    onChange={e => setData('correo', e.target.value)}
                                    placeholder="Correo"
                                />
                                
                            </div>

                            <div className="mb-3">
                                <label htmlFor="telefono" className="form-label">Teléfono</label>
                                <input
                                    className="form-control"
                                    type="text"
                                    id="telefono"
                                    value={data.telefono}
                                    onChange={e => setData('telefono', e.target.value)}
                                    placeholder="Teléfono"
                                />
                                
                            </div>

                            <div className="mb-3">
                                <label htmlFor="password" className="form-label">Contraseña</label>
                                <input
                                    className="form-control"
                                    type="password"
                                    id="password"
                                    value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                    placeholder="Contraseña"
                                />
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