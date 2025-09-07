export  const formateaFecha = (dateString: string | undefined): string => {
  if (!dateString) return 'N/A';
  
  const date = new Date(dateString);
 
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
  });
};
 