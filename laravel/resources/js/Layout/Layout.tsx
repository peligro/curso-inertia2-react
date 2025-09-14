import { LayoutPropsInterface } from '../Interfaces/LayoutPropsInterface';
import { Head, Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { LogueadoProps } from '../Interfaces/AuthInterface';

const Layout = ({ children }: LayoutPropsInterface) => {
  const { auth } = usePage<LogueadoProps>().props;
  const { url } = usePage();
  const currentPath = new URL(url, window.location.origin).pathname;
  const isAuthenticated = !!auth.user;

  return (
    <>
      <Head>
        <meta head-key="description" name="description" content="This is the default description" />
      </Head>
      <div className="container-fluid">
        <nav className="navbar navbar-expand-lg bg-primary navbar-dark">
          <div className="container-fluid">
            <a className="navbar-brand" href="/">Inertia 2</a>
            <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span className="navbar-toggler-icon"></span>
            </button>
            <div className="collapse navbar-collapse" id="navbarNav">
              <ul className="navbar-nav me-auto">
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/' ? 'active' : ''}`} aria-current="page" href={route('home_index')}>Home</Link>
                </li>
               
                <li className="nav-item dropdown">
                    <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownSeguridad" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Opciones
                    </a>
                    <ul className="dropdown-menu" aria-labelledby="navbarDropdownSeguridad">
                       <li>
                        <Link className="dropdown-item">Parámetros</Link>
                      </li>
                      <li>
                        <Link className="dropdown-item" href="/parametros-querystring?id=11&slug=cesar-cancino">Querystring</Link>
                      </li>
                      <li>
                        <Link className="dropdown-item" href={route('layout_index')}>Layout</Link>
                      </li>
                      <li>
                        <a className="dropdown-item" href="/health">Health</a>
                      </li>
                      <li>
                        <Link className="dropdown-item" href={route('layout_ProgressIndicator')}>Progress Indicator</Link>
                      </li>
                      
                    </ul>
                  </li>
                <li className="nav-item dropdown">
                    <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownSeguridad" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Formularios
                    </a>
                    <ul className="dropdown-menu" aria-labelledby="navbarDropdownSeguridad">
                      <li><Link className="dropdown-item" href={route('formulario_index')}>Formulario</Link></li>
                    <li><Link className="dropdown-item" href={route('formulario_post')}>Formulario post</Link></li>
                    </ul>
                  </li>

                
                
                {isAuthenticated && (
                  <>
                  <li className="nav-item dropdown">
                  <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownEloquent" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Eloquent ORM
                  </a>
                  <ul className="dropdown-menu" aria-labelledby="navbarDropdownEloquent">
                    
                    <li><Link className="dropdown-item" href={route('categorias_index')}>Categorías</Link></li>
                    <li><Link className="dropdown-item" href={route('publicaciones_index')}>Publicaciones</Link></li>
                  </ul>
                </li>
                  <li className="nav-item dropdown">
                    <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownSeguridad" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Seguridad
                    </a>
                    <ul className="dropdown-menu" aria-labelledby="navbarDropdownSeguridad">
                      <li><Link className="dropdown-item" href={route('perfiles_index')}>Perfiles</Link></li>
                      <li><Link className="dropdown-item" href={route('usuarios_index')}>Usuarios</Link></li>
                    </ul>
                  </li>
                  </>
                )}
               
                   <li className="nav-item dropdown">
                  <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownIA" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Inteligencia Artificial
                  </a>
                  <ul className="dropdown-menu" aria-labelledby="navbarDropdownIA">
                    <li><Link className="dropdown-item" href={route('openai_index')}>Openai</Link></li>
                    <li><Link className="dropdown-item" href={route('deepseek_index')}>Deepseek</Link></li>
                    <li><Link className="dropdown-item" href={route('gemini_index')}>Gemini</Link></li>
                     
                  </ul>
                </li>
              </ul>
              <ul className="navbar-nav">
                {isAuthenticated ? (
                  <li className="nav-item">
                    <Link className="nav-link" method="post" aria-current="page" href={route('logout')}><i className="fas fa-lock"></i></Link>
                  </li>
                ) : (
                  <li className="nav-item">
                    <Link className={`nav-link ${currentPath === '/login' ? 'active' : ''}`} aria-current="page" href={route('login')}>Login</Link>
                  </li>
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
  );
};

export default Layout;
