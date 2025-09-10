import { LayoutPropsInterface } from '../Interfaces/LayoutPropsInterface';
import { Head, Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { LogueadoProps } from '../Interfaces/AuthInterface';

const Layout = ({ children }: LayoutPropsInterface) => {
      const { auth } = usePage<LogueadoProps>().props;
   
  const { url } = usePage()
  const currentPath = new URL(url, window.location.origin).pathname;
  const isAuthenticated = !!auth.user;
  /*
  //Entendiendo la validación de la sesión en componentes React
  if(isAuthenticated)
  {
    console.log(`existe: ${auth.user?.name}`)
  }else
  {
    console.log("no no")
  }*/ 
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
              <ul className="navbar-nav">
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/' ? 'active' : ''}`} aria-current="page" href={route('home_index')}>Home</Link>
                </li>
                <Link
                  className={`nav-link ${currentPath === '/parametros/1/hola' ? 'active' : ''}`}
                  href={route('parametros_index', { id: 1, slug: 'hola' })}
                >
                  Parámetros
                </Link>
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/parametros-querystring' ? 'active' : ''}`} href="/parametros-querystring?id=11&slug=cesar-cancino">Querystring</Link>
                </li>
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/layout' ? 'active' : ''}`} aria-current="page" href={route('layout_index')}>Layout</Link>
                </li>
                <li className="nav-item">
                  <a className="nav-link" aria-current="page" href="/health">Health</a>
                </li>
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/layout/progress-indicator' ? 'active' : ''}`} aria-current="page" href={route('layout_ProgressIndicator')}>Progress Indicator</Link>
                </li>
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/formulario' ? 'active' : ''}`} aria-current="page" href={route('formulario_index')}>Formulario</Link>
                </li>
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/formulario/post' ? 'active' : ''}`} aria-current="page" href={route('formulario_post')}>Formulario post</Link>
                </li>
                {isAuthenticated ?(
                  <>
                   <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/categorias' ? 'active' : ''}`} aria-current="page" href={route('categorias_index')}>Categorías</Link>
                </li>
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/publicaciones' ? 'active' : ''}`} aria-current="page" href={route('publicaciones_index')}>Publicaciones</Link>
                </li>
                {auth.user?.perfil_id=="1" && (
                  <>
                   <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/perfiles' ? 'active' : ''}`} aria-current="page" href={route('perfiles_index')}>Perfiles</Link>
                </li>
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/usuarios' ? 'active' : ''}`} aria-current="page" href={route('usuarios_index')}>Usuarios</Link>
                </li>
                  </>
                )}
                <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/openai' ? 'active' : ''}`} aria-current="page" href={route('openai_index')}>Openai</Link>
                </li>

                <li className="nav-item">
                  <Link className={`nav-link `} method="post" aria-current="page" href={route('logout')}><i className="fas fa-lock"></i></Link>
                </li>
                  </>
                ):(
                  <>
                  <li className="nav-item">
                  <Link className={`nav-link ${currentPath === '/login' ? 'active' : ''}`} aria-current="page" href={route('login')}>Login</Link>
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
  );
};
export default Layout;


{/*
  import { LayoutPropsInterface } from '../Interfaces/LayoutPropsInterface'; 
const Layout = ({ children }: LayoutPropsInterface) => {
  return (
    <>
      {children}
        
    </>
  );
};
export default Layout;
  */}