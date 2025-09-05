import { usePage } from "@inertiajs/react";
import { PageProps } from "@inertiajs/core";

 

const Querystring = () => {
    const { props } = usePage<PageProps>();
    const { id, slug } = props;
  return (
   <>
   <h1>Parámetros Querystring</h1>
    <ul>
        <li>ID: {id}</li>
        <li>Slug: {slug}</li>
    </ul>
   </>
  )
}

export default Querystring