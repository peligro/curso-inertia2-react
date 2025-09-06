import { Link } from "@inertiajs/react"; 
import { PaginacionProps } from "../Interfaces/PaginacionInterfaces";



const PaginacionCustom = ({ datos }: PaginacionProps) => {
  // Filtrar solo los links numéricos (excluir primero y último que son los botones de navegación)
  const numericLinks = datos.links.filter((link, index) => {
    // Excluir el primer elemento (usually "Previous")
    // Excluir el último elemento (usually "Next")
    // Y solo incluir links con URL
    return index !== 0 && 
           index !== datos.links.length - 1 && 
           link.url !== null;
  });

  return (
    <nav aria-label="Page navigation example">
      <ul className="pagination">
        {/* Botón Primera página */}
        <li className="page-item">
          <Link className="page-link" href={datos.first_page_url}>
            Primera
          </Link>
        </li>

        {/* Botón Anterior */}
        <li className={`page-item ${!datos.prev_page_url ? 'disabled' : ''}`}>
          <Link className="page-link" href={datos.prev_page_url || '#'}>
            Anterior
          </Link>
        </li>

        {/* Números de página */}
        {numericLinks.map((link, index) => (
          <li
            key={index}
            className={`page-item ${link.active ? 'active' : ''}`}
          >
            <Link
              className="page-link"
              href={link.url || '#'}
              dangerouslySetInnerHTML={{ __html: link.label }}
            />
          </li>
        ))}

        {/* Botón Siguiente */}
        <li className={`page-item ${!datos.next_page_url ? 'disabled' : ''}`}>
          <Link className="page-link" href={datos.next_page_url || '#'}>
            Siguiente
          </Link>
        </li>

        {/* Botón Última página */}
        {datos.last_page_url && (
          <li className="page-item">
            <Link className="page-link" href={datos.last_page_url}>
              Última
            </Link>
          </li>
        )}
      </ul>
    </nav>
  );
};

export default PaginacionCustom;