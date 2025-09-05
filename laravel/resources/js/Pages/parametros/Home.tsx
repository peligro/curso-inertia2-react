import { usePage } from "@inertiajs/react";
import { PageProps } from "@inertiajs/core";

const Home = () => {
    const { props } = usePage<PageProps>();
    const { id, slug } = props;

    return (
        <>
            <h1>id {id}</h1>
            <h1>slug {slug}</h1>
        </>
    );
}

export default Home;

{/*
  import { usePage } from "@inertiajs/react";

const Home = () => {
    const { props } = usePage(); 
    const { id, slug } = props as { id?: string; slug?: string };

    return (
        <>
            <h1>Parámetros Path {id}</h1>
            <h2>Slug: {slug}</h2> 
        </>
    );
}

export default Home;
  */}