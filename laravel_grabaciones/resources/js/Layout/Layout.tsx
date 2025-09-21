import { LayoutPropsInterface } from "../Interfaces/LayoutPropsInterfaces";
import { Link, usePage } from "@inertiajs/react";
import {route} from 'ziggy-js';
import { LogueadoProps } from "../Interfaces/AuthInterface";
import { useEffect, useState } from "react";

const Layout = ({children}: LayoutPropsInterface) => {
    const {auth} = usePage<LogueadoProps>().props;

    const isAuthenticated = !!auth.user;
    /*
    if(isAuthenticated)
    {
        console.log("si tiene permiso");
    }else
    {
        console.log("no tiene permiso");
    }*/
    const [time, changeTime] = useState(new Date().toLocaleTimeString());
    useEffect(() => {
       setInterval(() => {
            changeTime(new Date().toLocaleTimeString());
       }, 1000);
    }, [ ]);
    const cerrarSesion=()=>
    {
        if(confirm('¿Realmente desea cerrar la sesión?'))
        {
            window.location.href=`${route('logout')}`;
        }
    };
  return (
    <>
        <div className="container-fluid">
            <nav className="navbar navbar-expand-lg bg-primary navbar-dark">
                <div className="container-fluid">
                    <Link href={route('home_index')} className="navbar-brand">Inertia 2</Link>
                     <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                    </button>
                    <div className="collapse navbar-collapse" id="navbarNav">
                        <ul className="navbar-nav me-auto">
                            <li className="nav-item">
                                <Link href={route('home_index')} className="nav-link">Home</Link>
                            </li>
                            <li className="nav-item dropdown">
                                <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Opciones
                                </a>
                                <ul className="dropdown-menu">
                                    <li>
                                        <Link href={route('parametros_index', {id:1, slug:'cesar'})} className="dropdown-item">Parámetros</Link>
                                    </li>
                                    <li>
                                        <Link href="/parametros-querystring?id=1&slug=cesar-cancino" className="dropdown-item">Querystring</Link>
                                    </li>
                                    <li>
                                        <Link href={route('layout_index')} className="dropdown-item">Layout</Link>
                                    </li>
                                    <li>
                                        <a href="/health" className="dropdown-item">Health</a>
                                    </li>
                                    <li>
                                        <Link href={route('layout_ProgressIndicator')} className="dropdown-item">Progress Indicator</Link>
                                    </li>
                                </ul>
                            </li>
                            <li className="nav-item dropdown">
                                <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Formularios
                                </a>
                                <ul className="dropdown-menu">
                                    <li>
                                        <Link href={route('formularios_index')} className="dropdown-item">Formulario simple</Link>
                                    </li>
                                    <li>
                                        <Link href={route('formularios_post')} className="dropdown-item">Formulario post</Link>
                                    </li>
                                </ul>
                            </li>
                            {isAuthenticated && (
                                <>
                                <li className="nav-item dropdown">
                                <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Eloquent ORM
                                </a>
                                <ul className="dropdown-menu">
                                    <li>
                                        <Link href={route('categorias_index')} className="dropdown-item">Categorías</Link>
                                    </li>
                                    <li>
                                        <Link href={route('publicaciones_index')} className="dropdown-item">Publicaciones</Link>
                                    </li>
                                </ul>
                            </li>
                            {auth.user?.perfil_id=="1" && (
                                <>
                                <li className="nav-item dropdown">
                                <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Seguridad
                                </a>
                                <ul className="dropdown-menu">
                                    <li>
                                        <Link href={route('perfiles_index')} className="dropdown-item">Perfiles</Link>
                                    </li>
                                    <li>
                                        <Link href={route('usuarios_index')} className="dropdown-item">Usuarios</Link>
                                    </li>
                                </ul>
                            </li>
                                </>
                            )}
                            
                                </>
                            )}
                            
                            <li className="nav-item dropdown">
                                <a className="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Inteligencia artificial
                                </a>
                                <ul className="dropdown-menu">
                                    <li>
                                        <Link href={route('openai_index')} className="dropdown-item">Openai</Link>
                                    </li>
                                    <li>
                                        <Link href={route('deepseek_index')} className="dropdown-item">Deepseek</Link>
                                    </li>
                                    <li>
                                        <Link href={route('gemini_index')} className="dropdown-item">Gemini</Link>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul className="navbar-nav">
                            {isAuthenticated ? (
                                <>
                                <li className="nav-item">
                                    <a className="nav-link">
                                        Hola {auth.user?.name}
                                    </a>
                                </li>
                                <li className="nav-item">
                                    <a className="nav-link">
                                        {time}
                                    </a>
                                </li>
                                <li className="nav-item">
                                    {/* 
                                    <Link href={route('logout')} method="post" className="nav-link" aria-current="page">
                                    <i className="fas fa-lock"></i>
                                    </Link>
                                    */}
                                    <a href="#" className="nav-link" onClick={()=>{cerrarSesion()}} title="Cerrar sesión">
                                        <i className="fas fa-lock"></i>
                                    </a>
                                    
                                </li>
                                </>
                            ):(
                                <>
                                <li className="nav-item">
                                    <Link href={route('login')} className="nav-link" aria-current="page">Login</Link>
                                </li>
                                </>
                            )}
                            
                        </ul>
                        
                    </div>

                </div>
            </nav>
        </div>
        <div className="container">
            <main>
                {children}
            </main>
        </div>
         
    </>
  )
}

export default Layout