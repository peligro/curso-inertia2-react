<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Publicaciones;
use App\Models\Categorias;
use Illuminate\Support\Str;

class PublicacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //creamos categorías
       /* $cats = [
            [
                'nombre' => 'Categoría 1',
                'slug' => 'categoria-1'
            ],
            [
                'nombre' => 'Categoría 2',
                'slug' => 'categoria-2'
            ],
            [
                'nombre' => 'Categoría 3',
                'slug' => 'categoria-3'
            ],
            [
                'nombre' => 'Categoría 4',
                'slug' => 'categoria-4'
            ],
            [
                'nombre' => 'Categoría 5',
                'slug' => 'categoria-5'
            ],
            [
                'nombre' => 'Categoría 6',
                'slug' => 'categoria-6'
            ],
            [
                'nombre' => 'Categoría 7',
                'slug' => 'categoria-7'
            ],
            [
                'nombre' => 'Categoría 8',
                'slug' => 'categoria-8'
            ]
        ];
        foreach ($cats as $cat) {
            $save = new Categorias();
            $save->nombre = $cat['nombre'];
            $save->slug = $cat['slug'];
            $save->save();
        }*/
        // Obtener todas las categorías disponibles
        $categorias = Categorias::all();
        
        if ($categorias->isEmpty()) {
            $this->command->info('No hay categorías disponibles. Primero ejecuta el seeder de categorías.');
            return;
        }
        $imagenUrl='publicaciones/HDZ1KHlHsoFfEkDOGwnu96jidZ9ghdcM0Kv2Ikuk.png';
        $publicaciones = [
            [
                'titulo' => 'Avances en Inteligencia Artificial Revolucionan la Medicina',
                'descripcion' => 'La inteligencia artificial está transformando el campo de la medicina con diagnósticos más precisos y tratamientos personalizados. Hospitales alrededor del mundo están implementando sistemas de IA que pueden analizar imágenes médicas con una precisión superior al 95%, detectando enfermedades en etapas tempranas. Esta tecnología no reemplaza a los médicos, sino que les proporciona herramientas poderosas para tomar decisiones más informadas y salvar más vidas.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Cambio Climático: Nuevo Acuerdo Internacional',
                'descripcion' => 'Líderes mundiales se reúnen para establecer metas más ambiciosas en la lucha contra el cambio climático. El nuevo acuerdo incluye compromisos para reducir las emisiones de carbono en un 50% para 2030 y alcanzar la neutralidad de carbono para 2050. Países desarrollados se comprometen a financiar proyectos de energía renovable en naciones en desarrollo, marcando un hito histórico en la cooperación global para salvar nuestro planeta.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Exploración Espacial: Misión a Marte Supera Expectativas',
                'descripcion' => 'La última misión a Marte ha proporcionado datos revolucionarios sobre la posibilidad de vida en el planeta rojo. Los rover han descubierto evidencia de agua líquida en el subsuelo y compuestos orgánicos que sugieren que Marte pudo albergar vida en el pasado. Los científicos están emocionados por los hallazgos que podrían reescribir nuestra comprensión del sistema solar y el potencial de colonización humana en el futuro.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Economía Digital: Criptomonedas y el Futuro de las Finanzas',
                'descripcion' => 'El auge de las criptomonedas está transformando el sistema financiero global. Bancos centrales de varios países están explorando la creación de sus propias monedas digitales, mientras que las criptomonedas descentralizadas ganan adopción masiva. Este cambio paradigmático presenta tanto oportunidades como desafíos regulatorios, requiriendo un equilibrio entre innovación y protección al consumidor en la nueva era digital.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Educación Híbrida: El Nuevo Modelo Post-Pandemia',
                'descripcion' => 'La combinación de educación presencial y virtual se consolida como el modelo educativo del futuro. Instituciones educativas reportan mejores resultados académicos cuando combinan lo mejor de ambos mundos: la interacción social del aula tradicional con la flexibilidad y recursos ilimitados del aprendizaje online. Este modelo híbrido está democratizando el acceso a educación de calidad a nivel global.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Tecnología 5G: Transformando la Conectividad Global',
                'descripcion' => 'La implementación masiva de redes 5G está creando una revolución en la conectividad. Con velocidades hasta 100 veces más rápidas que el 4G y una latencia mínima, el 5G habilita tecnologías como Internet de las cosas, ciudades inteligentes y vehículos autónomos. Esta infraestructura está sentando las bases para la próxima generación de innovación tecnológica a escala global.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Salud Mental: Enfoque Integral Gana Importancia',
                'descripcion' => 'La salud mental recibe finalmente la atención que merece en la agenda global. Empresas implementan programas de bienestar emocional, gobiernos destinan más recursos a servicios de salud mental y la sociedad rompe estigmas centuries-old. Este cambio cultural representa un avance significativo en el reconocimiento de que la salud mental es tan importante como la física para el bienestar humano.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Energías Renovables: Récord en Generación Mundial',
                'descripcion' => 'Las energías renovables superan por primera vez a los combustibles fósiles en generación eléctrica global. Solar y eólica lideran este crecimiento, con costos que han caído más del 80% en la última década. Este hito histórico marca un punto de inflexión en la transición hacia un futuro energético sostenible y libre de emisiones de carbono.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Inteligencia Artificial en la Creación Artística',
                'descripcion' => 'Herramientas de IA están transformando el proceso creativo en música, arte y literatura. Artistas colaboran con algoritmos para crear obras innovadoras que combinan la sensibilidad humana con la capacidad computacional de las máquinas. Este nuevo paradigma está expandiendo los límites de la expresión artística y generando debates fascinantes sobre la naturaleza del arte y la creatividad.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Movilidad Urbana: Vehículos Eléctricos Dominan Mercado',
                'descripcion' => 'Las ventas de vehículos eléctricos superan a los de combustión interna en mercados clave. Ciudades implementan infraestructura de carga rápida y incentivos fiscales para acelerar la transición eléctrica. Esta revolución no solo reduce emisiones, sino que también está transformando la industria automotriz y creando nuevas oportunidades económicas en energía limpia.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Realidad Virtual: Más Allá del Entretenimiento',
                'descripcion' => 'La realidad virtual encuentra aplicaciones prácticas en medicina, educación y negocios. Cirujanos practican procedimientos complejos en entornos virtuales, estudiantes exploran historia mediante recreaciones inmersivas, y empresas realizan reuniones globales en espacios virtuales. Esta tecnología está demostrando su valor más allá del gaming y el entretenimiento.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Seguridad Cibernética: Prioridad Global',
                'descripcion' => 'Ataques cibernéticos sofisticados elevan la seguridad digital a prioridad nacional para muchos países. Gobiernos y empresas invierten billones en proteger infraestructura crítica y datos sensibles. La colaboración internacional y el desarrollo de estándares globales se vuelven esenciales para enfrentar amenazas que no reconocen fronteras en el mundo digital.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Agricultura Sustentable: Tecnología Alimentando al Mundo',
                'descripcion' => 'Innovaciones en agricultura de precisión y foodtech están revolucionando la producción de alimentos. Sensores IoT, drones y AI optimizan el uso de agua y fertilizantes, mientras que proteínas alternativas reducen la dependencia de la ganadería tradicional. Estas tecnologías son cruciales para alimentar a una población global en crecimiento de manera sostenible.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Telemedicina: Atención Médica sin Fronteras',
                'descripcion' => 'La telemedicina se consolida como un pilar fundamental de los sistemas de salud. Pacientes en áreas remotas acceden a especialistas de clase mundial, y el monitoreo remoto permite manejo crónico de enfermedades desde casa. Esta transformación digital está haciendo que la atención médica de calidad sea más accesible y equitativa para todos.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Blockchain: Más Allá de las Criptomonedas',
                'descripcion' => 'La tecnología blockchain encuentra aplicaciones en cadena de suministro, votación digital y gestión de identidad. Su naturaleza descentralizada e inmutable ofrece transparencia y seguridad en procesos críticos. Empresas y gobiernos exploran cómo esta tecnología puede optimizar operaciones y crear sistemas más confiables y eficientes.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Trabajo Remoto: Transformando la Cultura Laboral',
                'descripcion' => 'El trabajo remoto redefine conceptos tradicionales de oficina y productividad. Empresas adoptan modelos híbridos que ofrecen flexibilidad mientras mantienen la colaboración y cultura organizacional. Este cambio está democratizando oportunidades laborales y permitiendo que talento de cualquier ubicación geográfica contribuya al crecimiento global.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Nanotecnología: Avances en Medicina y Materiales',
                'descripcion' => 'La nanotecnología permite desarrollos revolucionarios en administración de medicamentos y materiales inteligentes. Nanopartículas pueden dirigir fármacos específicamente a células cancerosas, mientras que nanomaterials crean estructuras más fuertes y ligeras. Esta ciencia está abriendo fronteras en múltiples industrias con aplicaciones que parecían ciencia ficción.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Turismo Sostenible: Nuevo Paradigma Post-Pandemia',
                'descripcion' => 'El turismo se reinventa con enfoque en sostenibilidad y experiencias auténticas. Viajeros buscan menor impacto ambiental y mayor conexión con comunidades locales. Este cambio está impulsando economías locales mientras promueve conservación y respeto por culturas y ecosistemas alrededor del mundo.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Computación Cuántica: Próxima Revolución Tecnológica',
                'descripcion' => 'La computación cuántica avanza hacia aplicaciones prácticas que resolverán problemas imposibles para computadoras tradicionales. Desde descubrimiento de medicamentos hasta optimización logística, esta tecnología promete transformar industrias completas. Laboratorios y empresas compiten en la carrera por alcanzar la supremacía cuántica práctica.',
                'foto'=>$imagenUrl
            ],
            [
                'titulo' => 'Derechos Digitales: Nuevo Marco Legal Global',
                'descripcion' => 'La legislación evoluciona para proteger privacidad y derechos en la era digital. Nuevas leyes regulan uso de datos personales, inteligencia artificial y contenido online. Este marco legal busca equilibrar innovación tecnológica con protección de derechos fundamentales en un mundo cada vez más digitalizado.',
                'foto'=>$imagenUrl
            ]
        ];
 

        foreach ($publicaciones as $publicacionData) {
            $publicacion = new Publicaciones();
            $publicacion->nombre = $publicacionData['titulo'];
            $publicacion->slug = Str::slug($publicacionData['titulo']);
            $publicacion->descripcion = $publicacionData['descripcion'];
            $publicacion->categorias_id = $categorias->random()->id;
            $publicacion->foto = $publicacionData['foto'];
            $publicacion->fecha = now()->subDays(rand(0, 365));
            $publicacion->save();
        }

        $this->command->info('20 publicaciones de noticias creadas con éxito!');
    }
}