# ESPECIFICACIÓN DE REQUERIMIENTOS FUNCIONALES DE SOFTWARE (SRS)

**Proyecto de Tesis:**  
*"Implementación de una plataforma web institucional para fortalecer la comunicación académica del IESTP Deperú Master, Lircay - 2026"*  
**Línea de Investigación:** Ingeniería de Software y Tecnologías de la Información  
**Escuela Profesional:** Ingeniería Informática — Facultad de Ciencias e Ingeniería  
**Institución Universitaria:** Universidad Para el Desarrollo Andino (UDEA)  
**Institución Beneficiaria:** Instituto de Educación Superior Tecnológico Privado "Deperú Master" (Lircay, Angaraes, Huancavelica)  
**Autor:** Bach. [Nombres y Apellidos del Autor]  
**Marco Metodológico de Requisitos:** Adaptación de los estándares IEEE Std 830-1998 e ISO/IEC/IEEE 29148:2018 para proyectos de ingeniería de software aplicada.

---

## 1. INTRODUCCIÓN Y CONTEXTO OPERACIONAL

### 1.1. Propósito del documento
El presente documento tiene por finalidad definir, estructurar y formalizar los **requerimientos funcionales (RF)** que rigen el diseño, construcción y validación de la plataforma web institucional para el Instituto de Educación Superior Tecnológico Privado "Deperú Master" de la ciudad de Lircay. 

En términos de ingeniería de software, como puntualiza Sommerville (2011), los requerimientos funcionales expresan con absoluta nitidez qué servicios concretos debe suministrar el sistema, la forma en que este reacciona frente a entradas operativas particulares y cómo se comporta ante escenarios de excepción o fallos de conexión. Este instrumento técnico funge como contrato funcional entre los objetivos de la investigación y la materialización del producto de software, alineándose rigurosamente con los problemas, objetivos e hipótesis expuestos en el proyecto de tesis.

### 1.2. Alcance del producto de software
La solución proyectada consiste en una **plataforma web institucional desacoplada** de alto rendimiento, compuesta por:
1. **Un núcleo de servidor (Backend RESTful en Laravel):** Encargado de la persistencia de datos relacionales en MySQL, la gobernanza de accesos basada en roles (RBAC), la autenticación segura mediante tokens de sesión (Laravel Sanctum), la trazabilidad auditada de publicaciones y la provisión de endpoints estructurados en formato JSON.
2. **Una interfaz web reactiva (Frontend Single Page Application en Vue 3 con Vite y Pinia):** Diseñada bajo un enfoque estrictamente *mobile-first*, garantizando una navegación ultraligera, tiempos de respuesta inferiores a 1.5 segundos y consumos mínimos de ancho de banda.
3. **Un gestor de contenidos y administración institucional (CMS Académico):** Habilitado para que las autoridades y el personal de secretaría gestionen comunicados oficiales, directivas, noticias, cronogramas y el hub centralizado de enlaces hacia las distintas herramientas del IESTP, prescindiendo de conocimientos de programación (Meza Piñas, 2024).

### 1.3. Contexto técnico y realidad de campo en Lircay
El diseño de estos requerimientos no surge de un laboratorio aséptico o descontextualizado. Responde a la realidad socio-tecnológica observada en el distrito de Lircay (provincia de Angaraes, departamento de Huancavelica), donde concurren factores operativos críticos:
- **Predominio absoluto de dispositivos móviles:** Más del 85% de la comunidad estudiantil del IESTP Deperú Master no cuenta con computadoras de escritorio ni ordenadores portátiles en sus hogares; su único punto de acceso al entorno digital es el teléfono celular (Quinto Munive, 2012).
- **Planes de datos prepago y paquetes de navegación restringidos:** Los estudiantes adquieren microrecargas diarias o semanales. Cualquier aplicación que obligue a recargas completas de página o transporte archivos pesados no optimizados agota el saldo del alumno y genera abandono de la plataforma (Gómez López, 2021).
- **Inestabilidad del enlace móvil (3G/4G oscilante):** Las variaciones climatológicas y la orografía andina generan caídas repentinas de señal. Por ello, la plataforma funcional debe tolerar desconexiones transitorias y almacenar en memoria caché local (vía Pinia y almacenamiento del navegador) los contenidos consultados previamente.
- **Sustitución progresiva de canales informales caóticos:** Los requerimientos priorizan la erradicación de comunicados oficiales dispersos en grupos de WhatsApp o vitrinas físicas, centralizando las comunicaciones y dotándolas de validez jurídica e institucional (Atagua, 2022; Lupaca & Angeles, 2024).

---

## 2. ACTORES DEL SISTEMA Y MATRIZ DE PERMISOS (RBAC)

Para garantizar la integridad y la confidencialidad de la información, el sistema implementa un modelo de Control de Acceso Basado en Roles (RBAC - *Role-Based Access Control*), tal como recomienda Pressman y Maxim (2021). Se definen cinco perfiles operativos claramente delimitados:

| Código | Actor / Rol | Descripción y Responsabilidad en la Plataforma |
| :--- | :--- | :--- |
| **ACT-01** | **Visitante / Postulante** | Usuario público sin credenciales. Accede a la información general del instituto, catálogo de carreras técnicas, convocatorias de admisión, noticias públicas y canales de contacto oficial. |
| **ACT-02** | **Estudiante Matriculado** | Alumno formalmente registrado. Accede con credenciales institucionales. Consulta comunicados oficiales, directivas de su programa de estudio, cronogramas académicos, repositorio de enlaces a sistemas del IESTP y canaliza consultas formales. |
| **ACT-03** | **Docente** | Profesor titular o contratado. Publica avisos formativos específicos vinculados a sus unidades didácticas, consulta calendarios institucionales y atiende consultas académicas formales de sus estudiantes en horario regulado. |
| **ACT-04** | **Secretaría Académica / Administrativo** | Personal responsable del registro y la atención al usuario. Redacta, programa y publica circulares oficiales, convocatorias de matrícula, cronogramas de exámenes y administra el hub de enlaces institucionales. |
| **ACT-05** | **Administrador / Dirección General** | Máxima autoridad y responsable informático. Posee control absoluto sobre la plataforma: gestión de usuarios, asignación de roles, auditoría de actividades (Audit Logs), revocación de contenidos y configuración global del CMS. |

---

## 3. MATRIZ DE TRAZABILIDAD: REQUERIMIENTOS VS. OBJETIVOS E HIPÓTESIS DE TESIS

Cada requerimiento funcional especificado en este documento se encuentra directamente acoplado con los objetivos específicos de la investigación y las dimensiones operacionales de la variable dependiente (*Comunicación Académica*), asegurando la validez metodológica del experimento preexperimental ($G \ O_1 \ X \ O_2$):

```
+----------------------------------------------------------------------------------------------------+
|                                    MATRIZ DE TRAZABILIDAD METODOLÓGICA                             |
+------------------------------+---------------------------------------+-----------------------------+
| Objetivo Específico de Tesis | Dimensión de la Comunicación          | Módulos y Requerimientos    |
+------------------------------+---------------------------------------+-----------------------------+
| OE1: Evaluar la optimización | Dimensión 1:                          | M03: Gestión de Comunicados |
| del flujo y oportunidad de la| Flujo y oportunidad de la información | (RF-09, RF-10, RF-11,       |
| información académica.       | (Inmediatez, reducción de la          |  RF-12, RF-13)              |
|                              | dispersión, avisos oportunos).        | M05: Calendario Académico   |
|                              |                                       | (RF-17, RF-18, RF-19)       |
+------------------------------+---------------------------------------+-----------------------------+
| OE2: Comprobar el incremento | Dimensión 2:                          | M02: Portal Institucional   |
| de la accesibilidad y la     | Accesibilidad y centralización        | (RF-05, RF-06, RF-07, RF-08)|
| centralización informativa.  | de la información institucional       | M04: Hub de Enlaces y       |
|                              | (Repositorio oficial, hub de accesos, | Servicios (RF-14, RF-15,    |
|                              | disminución de visitas presenciales). |  RF-16)                     |
+------------------------------+---------------------------------------+-----------------------------+
| OE3: Establecer la influencia| Dimensión 3:                          | M06: Interacción y Atención |
| de la plataforma en la       | Retroalimentación e interacción       | Académica Formal            |
| retroalimentación e interac- | académica docente-estudiante          | (RF-20, RF-21, RF-22, RF-23)|
| ción docente-estudiante.     | (Canal formal, trazabilidad, respeto  | M01: Autenticación RBAC     |
|                              | a la privacidad fuera de horario).    | (RF-01, RF-02, RF-03, RF-04)|
+------------------------------+---------------------------------------+-----------------------------+
```

---

## 4. ESPECIFICACIÓN DETALLADA DE REQUERIMIENTOS FUNCIONALES

A continuación se detalla cada requerimiento funcional siguiendo la estructura estandarizada de ingeniería de software: identificador, denominación, prioridad según metodología MoSCoW (*Must have*, *Should have*, *Could have*, *Won't have*), actores intervinientes, vinculación con la tesis, precondiciones, flujo de eventos, reglas de negocio y criterios de aceptación.

```
Leyenda de Prioridad MoSCoW:
- [MUST]: Indispensable para la puesta en producción y prueba preexperimental.
- [SHOULD]: Altamente deseable para la solidez operativa del sistema.
- [COULD]: Funcionalidad complementaria implementable según disponibilidad.
- [WONT]: Fuera del alcance del ciclo 2026.
```

---

### MÓDULO M01: AUTENTICACIÓN, SEGURIDAD Y GESTIÓN DE ROLES (RBAC)

#### RF-01: Autenticación de usuarios mediante credenciales seguras y emisión de tokens
- **Prioridad:** MUST
- **Actores:** ACT-02 (Estudiante), ACT-03 (Docente), ACT-04 (Secretaría), ACT-05 (Administrador).
- **Vinculación con Tesis:** Soporte transversal para trazabilidad de la comunicación y validez institucional.
- **Descripción:** El sistema debe autenticar la identidad del usuario a través de su identificador institucional (DNI o correo institucional) y una contraseña cifrada mediante algoritmo de un solo sentido (Bcrypt/Argon2id). Tras una verificación exitosa, la API de Laravel expedirá un token de acceso criptográfico (Laravel Sanctum) con tiempo de expiración regulado, el cual viajará en las cabeceras HTTP de las solicitudes subsecuentes de la SPA.
- **Precondiciones:** El usuario debe estar previamente registrado en el estado "Activo" dentro de la base de datos MySQL.
- **Flujo Principal:**
  1. El usuario accede al formulario de ingreso e ingresa su identificador y contraseña.
  2. El frontend en Vue 3 valida localmente el formato de los campos (no vacíos, estructura válida).
  3. La SPA envía una petición asíncrona por método POST al endpoint `/api/v1/auth/login`.
  4. El servidor verifica las credenciales y el estado del usuario.
  5. El servidor genera el token Sanctum, asocia las habilidades (*abilities*) del rol correspondiente y devuelve un payload JSON con el token y los datos de perfil mínimos.
  6. La tienda reactiva (Pinia) almacena el token y redirige al panel según el rol.
- **Flujos Alternativos:**
  - *4a. Credenciales inválidas:* El servidor responde con código HTTP 401 (*Unauthorized*) y un mensaje genérico: "Credenciales no válidas. Verifique sus datos". Se registra el intento fallido.
  - *4b. Bloqueo por fuerza bruta:* Si se superan 5 intentos fallidos consecutivos en menos de 2 minutos, la API bloquea temporalmente la IP/usuario por 10 minutos (HTTP 429 *Too Many Requests*).
- **Criterio de Aceptación:** Dado que un estudiante ingresa su DNI y clave correcta, el sistema responde en menos de 800 ms emitiendo el token de sesión y mostrando su panel personalizado sin recargar la página.

#### RF-02: Control de acceso basado en roles y permisos institucionales (RBAC)
- **Prioridad:** MUST
- **Actores:** ACT-02, ACT-03, ACT-04, ACT-05.
- **Vinculación con Tesis:** Garantiza la legitimidad y formalidad de los canales comunicacionales.
- **Descripción:** El sistema debe controlar el acceso a los endpoints y a las vistas de la aplicación verificando estrictamente los permisos asignados al rol del usuario. Ningún estudiante o docente podrá acceder a funciones de creación de circulares institucionales o borrado de bitácoras de auditoría.
- **Reglas de Negocio:**
  - `RN-01.1`: Las rutas administrativas de la API deben estar protegidas por el middleware `auth:sanctum` combinado con middlewares de roles (`role:admin`, `role:secretaria`, `role:docente`).
  - `RN-01.2`: Cualquier intento de consumo de endpoint no autorizado debe abortar de inmediato con código HTTP 403 (*Forbidden*), registrando la advertencia en el log de seguridad del servidor.
- **Criterio de Aceptación:** Si un usuario con rol "Estudiante" intenta enviar una solicitud POST a `/api/v1/comunicados`, el backend rechaza la petición con código 403 y no se ejecuta ninguna inserción en base de datos.

#### RF-03: Recuperación y cambio autónomo de credenciales
- **Prioridad:** SHOULD
- **Actores:** ACT-02, ACT-03, ACT-04.
- **Vinculación con Tesis:** Disminuye la saturación presencial en secretaría para restablecimiento de claves.
- **Descripción:** El sistema debe posibilitar que los usuarios restablezcan su contraseña mediante el envío de un enlace temporal de un solo uso a su correo electrónico registrado o mediante verificación de código de seguridad por DNI validado previamente por secretaría.
- **Criterio de Aceptación:** El enlace de restablecimiento caduca de forma estricta a los 30 minutos de haber sido emitido; una vez utilizado, queda invalidado automáticamente.

#### RF-04: Gestión de perfil de usuario y cierre de sesión seguro
- **Prioridad:** MUST
- **Actores:** Todos los actores registrados.
- **Descripción:** El sistema debe permitir al usuario visualizar sus datos institucionales (nombre, rol, carrera técnica, correo) y cerrar la sesión de manera explícita. El cierre de sesión debe revocar y purgar inmediatamente el token Sanctum del servidor y limpiar el estado de Pinia y el almacenamiento local del dispositivo móvil.
- **Criterio de Aceptación:** Al pulsar "Cerrar sesión", el token queda revocado en la tabla `personal_access_tokens` de MySQL, impidiendo su reutilización en caso de robo o pérdida del teléfono celular.

---

### MÓDULO M02: PORTAL PÚBLICO INSTITUCIONAL Y DIFUSIÓN ACADÉMICA

#### RF-05: Visualización de información institucional, identidad y acreditación
- **Prioridad:** MUST
- **Actores:** ACT-01 (Visitante), ACT-02, ACT-03, ACT-04, ACT-05.
- **Vinculación con Tesis:** Variable Y - Dimensión 2: Accesibilidad informativa e identidad digital (Dávila et al., 2023).
- **Descripción:** La plataforma debe presentar en su página de aterrizaje (*Home*) la identidad oficial del IESTP Deperú Master: reseña histórica, misión, visión, valores institucionales, resolución de licenciamiento ministerial y organigrama directivo.
- **Reglas de Negocio:**
  - `RN-02.1`: Toda imagen institucional mostrada debe servirse en formato WebP con compresión adaptativa para asegurar que el peso total de la vista inicial no supere los 350 KB.
- **Criterio de Aceptación:** La página principal carga completamente en redes 3G con un tiempo de despliegue visual (*First Contentful Paint*) inferior a 1.2 segundos.

#### RF-06: Catálogo interactivo de programas de estudio (Carreras Profesionales)
- **Prioridad:** MUST
- **Actores:** ACT-01, ACT-02.
- **Vinculación con Tesis:** Dimensión 2: Centralización de información formativa.
- **Descripción:** La plataforma debe presentar de forma modular los programas de estudio autorizados del instituto (como Computación e Informática / Enfermería Técnica u otros según el catálogo del IESTP). Para cada programa se mostrará:
  - Perfil de competencias del egresado.
  - Plan de estudios y malla curricular organizada por módulos formativos.
  - Certificaciones modulares intermedias y grado académico a otorgar.
  - Campo laboral y modalidades de titulación.
- **Criterio de Aceptación:** El usuario puede alternar entre las pestañas de las distintas carreras y visualizar la estructura curricular en formato de tabla interactiva responsive sin saltos de página.

#### RF-07: Publicación y consulta de procesos de admisión y matrícula
- **Prioridad:** MUST
- **Actores:** ACT-01 (Postulante), ACT-02 (Estudiante regular).
- **Vinculación con Tesis:** Dimensión 1: Oportunidad de la información de inicio de semestre.
- **Descripción:** El sistema debe ofrecer una sección pública y actualizada con los calendarios de admisión, vacantes disponibles por carrera, requisitos documentarios para la inscripción, tasas de matrícula oficiales y enlaces directos al formulario digital de preinscripción.
- **Criterio de Aceptación:** Las convocatorias de admisión muestran un estado visual claro: "Vigente", "Próxima" o "Finalizada", impidiendo que postulantes se guíen por información caduca de periodos anteriores.

#### RF-08: Repositorio público de documentos normativos y de transparencia
- **Prioridad:** SHOULD
- **Actores:** ACT-01, ACT-02, ACT-03.
- **Vinculación con Tesis:** Dimensión 2: Formalidad y acceso unificado a normativas (Ley N° 30512).
- **Descripción:** La plataforma debe alojar para su consulta o descarga directa los documentos oficiales de gestión del instituto: Reglamento Interno (RI), Manual de Procesos Académicos, Código de Ética y Directivas de Prácticas Preprofesionales.
- **Criterio de Aceptación:** Los documentos en PDF se sirven mediante enlaces optimizados y cuentan con un visor web ligero integrado que permite hojear el reglamento sin forzar la descarga de archivos voluminosos al almacenamiento del teléfono.

---

### MÓDULO M03: GESTIÓN Y PUBLICACIÓN DE COMUNICADOS Y DIRECTIVAS (CMS ACADÉMICO)
*(Módulo nuclear para el cumplimiento del Objetivo Específico OE1 y OE2)*

#### RF-09: Redacción y publicación de comunicados oficiales y directivas de secretaría
- **Prioridad:** MUST
- **Actores:** ACT-04 (Secretaría), ACT-05 (Administrador).
- **Vinculación con Tesis:** OE1 / Dimensión 1: Inmediatez y canal formal de difusión.
- **Descripción:** El personal autorizado de secretaría o dirección podrá crear nuevos comunicados oficiales mediante un editor de texto enriquecido ligero dentro del CMS. Cada publicación debe registrar obligatoriamente:
  - Título oficial del comunicado.
  - Número de circular o expediente administrativo.
  - Cuerpo o contenido explicativo detallado.
  - Categoría temática (Matrícula, Exámenes, Convocatorias, Trámites de Titulación, Suspensión de labores, Actividades extracurriculares).
  - Destinatarios específicos (General / Por Carrera Técnica / Por Semestre Académico).
  - Archivo anexo opcional (PDF oficial escaneado con sello, comprimido en el servidor).
- **Reglas de Negocio:**
  - `RN-03.1`: Todo comunicado publicado queda sellado irrevocablemente con el ID del autor, dirección IP de origen, fecha y hora exacta del servidor (`created_at`).
  - `RN-03.2`: Los archivos PDF adjuntos no pueden superar los 2 MB de tamaño. El backend rechazará cualquier archivo mayor o con extensiones ejecutables por razones de seguridad.
- **Criterio de Aceptación:** Al presionar "Publicar", el comunicado se persiste en MySQL y aparece inmediatamente disponible en la bandeja de los estudiantes a través de la API REST, con una latencia de persistencia inferior a 500 ms.

#### RF-10: Priorización, marcado de urgencia y fijación de avisos críticos
- **Prioridad:** MUST
- **Actores:** ACT-04, ACT-05.
- **Vinculación con Tesis:** OE1: Oportunidad de directivas urgentes.
- **Descripción:** El emisor podrá calificar un comunicado como **"Urgente"** o **"Fijado al Inicio"**. Estos comunicados se exhibirán de forma destacada en la parte superior del portal y en la vista del estudiante con una insignia visual distintiva (rojo o ámbar), garantizando que no pasen desapercibidos frente a avisos ordinarios.
- **Criterio de Aceptación:** Un aviso fijado permanece al tope de la lista independientemente de la fecha de publicación de anuncios posteriores, hasta que el administrador retire la fijación o venza su vigencia.

#### RF-11: Programación de vigencia temporal y expiración automática de avisos
- **Prioridad:** MUST
- **Actores:** ACT-04, ACT-05.
- **Vinculación con Tesis:** Dimensión 1: Eliminación de la confusión por avisos desactualizados.
- **Descripción:** El sistema debe exigir una fecha de inicio y una fecha límite de vigencia para cada comunicado. Al cumplirse la fecha de expiración, el sistema trasladará de forma automática el aviso desde la vista "Vigentes" hacia la sección "Archivo Histórico", impidiendo que los alumnos confundan cronogramas vencidos con los vigentes.
- **Reglas de Negocio:**
  - `RN-03.3`: La consulta del endpoint `/api/v1/comunicados/activos` incluirá automáticamente el condicional de fecha: `WHERE fecha_inicio <= NOW() AND fecha_fin >= NOW() AND estado = 'publicado'`.
- **Criterio de Aceptación:** Llegada la medianoche fijada como fecha límite, el aviso deja de mostrarse en la portada de anuncios activos sin intervención manual del personal.

#### RF-12: Motor de búsqueda y filtrado multicriterio de directivas y circulares
- **Prioridad:** MUST
- **Actores:** ACT-02 (Estudiante), ACT-03 (Docente), ACT-04, ACT-05.
- **Vinculación con Tesis:** OE2 / Dimensión 2: Centralización y erradicación del peregrinaje presencial.
- **Descripción:** La plataforma debe proveer un motor de búsqueda reactivo en el frontend que filtre instantáneamente la lista de comunicados conforme el usuario tipea palabras clave en el buscador. Asimismo, debe posibilitar el filtrado por:
  - Rango de fechas (Mes / Año).
  - Categoría temática (Académica, Trámites, Eventos, Urgentes).
  - Programa de estudio específico.
- **Criterio de Aceptación:** El filtrado sobre una colección de 150 avisos se ejecuta localmente en el cliente con Vue 3 en menos de 50 milisegundos, sin realizar peticiones redundantes a la base de datos si los datos ya residen en el almacén de Pinia.

#### RF-13: Registro de acuse y confirmación de lectura digital
- **Prioridad:** SHOULD
- **Actores:** ACT-02 (Estudiante), ACT-04, ACT-05.
- **Vinculación con Tesis:** OE1 / Dimensión 1: Verificación de recepción efectiva del mensaje oficial.
- **Descripción:** Cuando se emita una directiva de carácter obligatorio (ej. plazos improrrogables de subsanación o matrícula), el sistema solicitará al estudiante autenticado presionar un botón de "He leído y comprendido este comunicado". El sistema registrará la confirmación en la base de datos con marca de tiempo.
- **Reglas de Negocio:**
  - `RN-03.4`: Secretaría podrá generar una lista en tiempo real de los alumnos que confirmaron la lectura y de aquellos que aún no lo han hecho, sirviendo de respaldo administrativo formal.
- **Criterio de Aceptación:** El porcentaje de acuse de recibo se actualiza en el panel de secretaría en tiempo real mediante recarga de datos asincrónica.

---

### MÓDULO M04: HUB CENTRALIZADO DE ENLACES Y SERVICIOS DIGITALES DEL IESTP
*(Módulo nuclear para el cumplimiento del Objetivo Específico OE2)*

#### RF-14: Catálogo unificado de accesos directos a los sistemas del instituto
- **Prioridad:** MUST
- **Actores:** ACT-01, ACT-02, ACT-03, ACT-04, ACT-05.
- **Vinculación con Tesis:** OE2 / Dimensión 2: Erradicación de la dispersión de accesos y servicios institucionales.
- **Descripción:** La plataforma debe constituir la **puerta de entrada digital única (Hub Institucional)** que reúna en una cuadrícula de tarjetas claras y reconocibles los enlaces directos a todos los sistemas y plataformas con que interactúa el IESTP Deperú Master:
  - Sistema de Gestión de Matrícula y Notas (SIA / SIGA del instituto).
  - Plataforma de Aulas Virtuales (Moodle / Google Classroom / Chamilo).
  - Mesa de Partes Virtual y Trámites Documentarios.
  - Repositorio de Biblioteca Virtual y Recursos Educativos.
  - Portal oficial de Consulta de Títulos del MINEDU.
- **Reglas de Negocio:**
  - `RN-04.1`: Cada tarjeta de servicio debe incluir el logo oficial del sistema, una breve descripción de su función, estado del servicio ("Operativo" / "En Mantenimiento") y el botón de redirección con atributo `rel="noopener noreferrer"`.
- **Criterio de Aceptación:** Al pulsar el enlace del aula virtual o sistema de notas, el estudiante es conducido de manera inmediata a la URL oficial sin intermediarios inseguros ni páginas no verificadas.

#### RF-15: Clasificación y segmentación de enlaces por perfil de usuario
- **Prioridad:** SHOULD
- **Actores:** ACT-02 (Estudiante), ACT-03 (Docente), ACT-04 (Personal administrativo).
- **Vinculación con Tesis:** Dimensión 2: Accesibilidad enfocada al usuario.
- **Descripción:** Los enlaces a servicios digitales se segmentarán dinámicamente según el rol del usuario conectado:
  - El estudiante verá accesos rápidos a: matrícula, solicitudes de justificación, descarga de fichas y bolsa de trabajo.
  - El docente visualizará accesos a: sistema de ingreso de actas, registro de asistencia docente y directivas de licenciamiento.
- **Criterio de Aceptación:** La interfaz oculta o reordena los accesos no pertinentes al perfil del usuario autenticado, agilizando la navegación en pantallas reducidas de celular.

#### RF-16: Administración y actualización dinámica de enlaces en el CMS
- **Prioridad:** MUST
- **Actores:** ACT-04 (Secretaría), ACT-05 (Administrador).
- **Descripción:** El personal administrativo debe contar con la capacidad de modificar las URLs de acceso, añadir nuevos servicios o suspender temporalmente un enlace (mostrando aviso de mantenimiento) a través de un formulario sencillo en el panel del CMS, sin necesidad de recurrir al programador ni modificar el código fuente.
- **Criterio de Aceptación:** Un cambio de enlace realizado en el panel administrativo se refleja en el portal público en el instante en que se guarda, tras invalidar la caché correspondiente.

---

### MÓDULO M05: CALENDARIO ACADÉMICO DINÁMICO Y CRONOGRAMAS INSTITUCIONALES
*(Módulo de respaldo al Objetivo Específico OE1)*

#### RF-17: Visualización y navegación interactiva del calendario académico anual/semestral
- **Prioridad:** MUST
- **Actores:** ACT-01, ACT-02, ACT-03, ACT-04, ACT-05.
- **Vinculación con Tesis:** OE1 / Dimensión 1: Flujo y anticipación de fechas clave.
- **Descripción:** La plataforma debe presentar un calendario académico interactivo mensual y semestral donde se visualicen los hitos oficiales del año lectivo 2026 en el IESTP Deperú Master. El usuario podrá navegar entre meses y filtrar eventos por categoría cromática.
- **Criterio de Aceptación:** Los días con eventos presentan un marcador de color distintivo; al pulsar sobre el día, se despliega una ventana modal ligera (*bottom sheet* en móviles) con el detalle del hito académico.

#### RF-18: Gestión de hitos, fechas límite y vencimientos por secretaría
- **Prioridad:** MUST
- **Actores:** ACT-04 (Secretaría), ACT-05 (Administrador).
- **Descripción:** El CMS debe permitir la programación de eventos institucionales estructurados:
  - Periodo de matrícula ordinaria y extemporánea.
  - Inicio y culminación de clases por semestre.
  - Semanas de evaluaciones parciales y exámenes finales.
  - Fechas de entrega de actas pedagógicas por los docentes.
  - Feriados no laborables y festividades institucionales.
- **Criterio de Aceptación:** El sistema impide registrar un hito con fecha de culminación anterior a la fecha de inicio, emitiendo una alerta de validación en el formulario.

#### RF-19: Exportación ligera de cronogramas para consulta sin conexión (*Offline-ready*)
- **Prioridad:** SHOULD
- **Actores:** ACT-02 (Estudiante), ACT-03 (Docente).
- **Vinculación con Tesis:** Adaptación a las limitaciones de conectividad en zonas rurales de Angaraes.
- **Descripción:** El sistema debe ofrecer la opción de generar una versión resumida en formato PDF ultraligero (peso < 150 KB) del cronograma académico semestral, o permitir la sincronización con el calendario local del teléfono (archivo estándar `.ics`), permitiendo al alumno consultar sus fechas de examen aun cuando no disponga de saldo de internet en ese momento.
- **Criterio de Aceptación:** La descarga del archivo `.ics` o PDF resumen se efectúa en un solo clic y no excede los 150 KB de transferencia de datos móviles.

---

### MÓDULO M06: INTERACCIÓN FORMAL Y ATENCIÓN DE CONSULTAS ACADÉMICAS
*(Módulo nuclear para el cumplimiento del Objetivo Específico OE3)*

#### RF-20: Formulación y canalización de consultas académicas estructuradas
- **Prioridad:** MUST
- **Actores:** ACT-02 (Estudiante).
- **Vinculación con Tesis:** OE3 / Dimensión 3: Canal formal que erradica la invasión de WhatsApp personal docente.
- **Descripción:** El estudiante autenticado dispondrá de un módulo institucional formal para enviar dudas pedagógicas o consultas administrativas. El formulario requerirá:
  - Tipo de consulta: (Académica de curso / Trámite de secretaría / Asuntos estudiantiles).
  - Destinatario: Selección del docente a cargo de la unidad didáctica o del área de secretaría.
  - Asunto descriptivo breve.
  - Mensaje detallado.
  - Adjunto opcional (captura de ejercicio o documento, máx. 1 MB).
- **Reglas de Negocio:**
  - `RN-06.1`: No se permite el envío de consultas de manera anónima; el sistema asocia de forma automática el DNI, nombres completos, carrera y ciclo del estudiante autenticado.
  - `RN-06.2`: El estudiante no tendrá acceso al número telefónico privado del docente, resguardando la intimidad personal de la plana profesional docente.
- **Criterio de Aceptación:** Al presionar "Enviar consulta", el mensaje queda registrado en la base de datos con estado "Pendiente" y se emite un código correlativo de seguimiento al estudiante (ej. `CONS-2026-0042`).

#### RF-21: Bandeja institucional de atención y absolución de consultas
- **Prioridad:** MUST
- **Actores:** ACT-03 (Docente), ACT-04 (Secretaría).
- **Vinculación con Tesis:** OE3 / Dimensión 3: Oportunidad y orden en la retroalimentación.
- **Descripción:** Los docentes y el personal de secretaría contarán con una bandeja de entrada dentro de su panel privado donde se organizarán las consultas recibidas según su estado: *Pendientes*, *En proceso*, *Respondidas* y *Archivadas*.
- **Reglas de Negocio:**
  - `RN-06.3`: Los docentes solo visualizan las consultas remitidas a sus respectivas unidades didácticas o carreras. No pueden acceder a las consultas privadas de otros catedráticos.
  - `RN-06.4`: El docente podrá redactar una respuesta formal y, si es necesario, adjuntar material de apoyo o aclaración (PDF/imagen).
- **Criterio de Aceptación:** Una vez que el docente pulsa "Enviar respuesta", el estado cambia automáticamente a "Respondida", registrando fecha, hora y texto de absolución.

#### RF-22: Historial, trazabilidad y estado de consultas para el estudiante
- **Prioridad:** MUST
- **Actores:** ACT-02 (Estudiante).
- **Vinculación con Tesis:** OE3: Claridad y seguimiento formal sin extravío de requerimientos.
- **Descripción:** El alumno podrá acceder en todo momento a su historial de consultas realizadas, verificando en qué estado se encuentra su solicitud, quién la tiene a su cargo y la respuesta formal brindada por el docente o funcionario.
- **Criterio de Aceptación:** La interfaz muestra un indicador visual de estados (Semáforo: Amarillo = Pendiente, Azul = En revisión, Verde = Respondida).

#### RF-23: Notificaciones en interfaz y avisos de respuesta académica
- **Prioridad:** SHOULD
- **Actores:** ACT-02 (Estudiante), ACT-03 (Docente).
- **Vinculación con Tesis:** Dimensión 1 y 3: Inmediatez comunicacional.
- **Descripción:** La plataforma incorporará un componente visual de campana de notificaciones en el encabezado de la SPA. Cuando un docente conteste una consulta, el estudiante verá una insignia numérica indicando el nuevo mensaje al momento de abrir la plataforma, evitando que deba consultar periódicamente de manera infructuosa.
- **Criterio de Aceptación:** La consulta de notificaciones no leídas se realiza mediante una petición liviana (`/api/v1/notificaciones/unread-count`) que transfiere menos de 100 bytes de datos.

---

### MÓDULO M07: PANEL DE ADMINISTRACIÓN CMS, AUDITORÍA Y TELEMETRÍA
*(Módulo de soporte técnico y gobernanza de la plataforma)*

#### RF-24: Tablero de control (Dashboard) con métricas comunicacionales
- **Prioridad:** SHOULD
- **Actores:** ACT-05 (Administrador / Dirección General).
- **Vinculación con Tesis:** Medición cuantitativa del uso de la plataforma en la fase postest.
- **Descripción:** El panel de dirección debe resumir visualmente el desempeño comunicativo institucional:
  - Número de comunicados oficiales publicados en el mes.
  - Tasa de lecturas registradas por programa de estudio.
  - Cantidad de consultas académicas canalizadas y porcentaje de resolución.
  - Volumen de accesos diarios diferenciados por tipo de dispositivo (Móvil vs. Escritorio).
- **Criterio de Aceptación:** El panel dibuja gráficos ligeros renderizados vía SVG/Canvas en el cliente, sin sobrecargar de procesamiento al servidor en producción.

#### RF-25: Registro de auditoría institucional (*Audit Logs*)
- **Prioridad:** MUST
- **Actores:** ACT-05 (Administrador).
- **Vinculación con Tesis:** Validez institucional y blindaje frente a repudiaciones (Cedillo, 2011).
- **Descripción:** La plataforma debe almacenar en una tabla de auditoría inmutable (`audit_logs`) toda acción relevante efectuada en el sistema:
  - Fecha y hora exacta (sincronizada con reloj del servidor).
  - Identificador del usuario que ejecutó la operación.
  - Tipo de acción (Creación, Modificación, Eliminación lógica, Inicio de sesión, Fallo de clave).
  - Entidad afectada (ID de comunicado, rol modificado, enlace editado).
  - Valores anteriores y valores nuevos en formato JSON.
  - Dirección IP y agente de usuario (*User-Agent*).
- **Reglas de Negocio:**
  - `RN-07.1`: Ningún usuario, ni siquiera el administrador de mayor jerarquía, puede editar o truncar la tabla de auditoría desde la interfaz web.
- **Criterio de Aceptación:** Cualquier cambio en una fecha de examen o texto de comunicado genera un registro en la bitácora visible en la vista de auditoría del sistema.

#### RF-26: Gestión y compresión automática de archivos institucionales en servidor
- **Prioridad:** MUST
- **Actores:** ACT-04 (Secretaría), ACT-05 (Administrador).
- **Descripción:** Cuando se suban archivos adjuntos para circulares o comunicados, el servidor de Laravel procesará automáticamente los documentos:
  - Generación de un nombre único aleatorio (*hash SHA-256*) para impedir sobreescrituras accidentales.
  - Sanitización de extensiones y validación de tipos MIME reales.
  - Almacenamiento fuera de la raíz pública accesible directamente, sirviéndose mediante descargas controladas o rutas simbólicas seguras.
- **Criterio de Aceptación:** La subida de un archivo no permitido (ej. `.exe`, `.bat`, `.php`, `.js`) es bloqueada inmediatamente con error 422 de validación, protegiendo al servidor contra inyecciones maliciosas.

---

### MÓDULO M08: REQUERIMIENTOS FUNCIONALES DE RENDIMIENTO Y OPTIMIZACIÓN
*(Adaptación técnica al entorno de baja conectividad en Huancavelica)*

#### RF-27: Modo de consumo mínimo de datos móviles (*Data-Saver Payload*)
- **Prioridad:** MUST
- **Actores:** Todos los actores que navegan desde teléfonos móviles.
- **Vinculación con Tesis:** Viabilidad práctica de la plataforma en la muestra de 85 participantes en Lircay.
- **Descripción:** Los endpoints de la API de Laravel implementarán recursos de transformación de API (*Eloquent API Resources*) estrictamente optimizados. Las listas de comunicados para la vista de tarjetas no transferirán el cuerpo HTML completo, sino únicamente: ID, título, categoría, fecha relativa, insignia de urgencia y slug. El cuerpo detallado se transferirá únicamente cuando el usuario haga clic para abrir el comunicado individual.
- **Criterio de Aceptación:** La carga del listado de 20 comunicados transfiere menos de 15 KB de payload JSON comprimido con Gzip/Brotli a través de la red celular.

#### RF-28: Persistencia y caché reactiva en memoria del dispositivo móvil
- **Prioridad:** SHOULD
- **Actores:** ACT-02 (Estudiante), ACT-03 (Docente).
- **Vinculación con Tesis:** Continuidad comunicativa ante caídas temporales de cobertura móvil.
- **Descripción:** La interfaz desarrollada en Vue 3 implementará almacenamiento reactivo mediante Pinia respaldado por LocalStorage / IndexedDB para el listado de noticias, cronogramas y hub de enlaces. Si el estudiante pierde momentáneamente la señal 4G mientras camina por el distrito de Lircay, la aplicación no colapsará con una pantalla en blanco; continuará mostrando la información previamente descargada con un mensaje sutil en el pie de página: "Mostrando contenidos sincronizados recientemente".
- **Criterio de Aceptación:** Al desconectar la conexión de red en el navegador (*Modo Offline* de DevTools), el usuario aún puede leer los comunicados y revisar el cronograma cargados en la sesión actual sin recibir errores de aplicación no controlados.

---

## 5. TABLA RESUMEN CONSOLIDADA DE REQUERIMIENTOS FUNCIONALES

A continuación se sintetizan los 28 requerimientos funcionales codificados, facilitando el control y seguimiento durante las fases de desarrollo (Fase 2 del cronograma) y evaluación preexperimental (Fase 3 y 4):

| Código | Denominación del Requerimiento Funcional | Módulo | Actor Principal | Prioridad | Dimensión Tesis |
| :---: | :--- | :---: | :---: | :---: | :---: |
| **RF-01** | Autenticación mediante credenciales y tokens Sanctum | M01: Autenticación | Todos | **MUST** | Soporte Transversal |
| **RF-02** | Control de acceso basado en roles (RBAC) | M01: Autenticación | Administrador | **MUST** | Formalidad Institucional |
| **RF-03** | Recuperación y cambio autónomo de credenciales | M01: Autenticación | Estudiante/Doc. | **SHOULD** | Soporte Operativo |
| **RF-04** | Gestión de perfil y revocación segura de tokens | M01: Autenticación | Todos | **MUST** | Seguridad de Datos |
| **RF-05** | Vista institucional, misión, visión y acreditación | M02: Portal Público | Visitante | **MUST** | Dimensión 2 (Centralización) |
| **RF-06** | Catálogo modular interactivo de carreras técnicas | M02: Portal Público | Visitante/Estud. | **MUST** | Dimensión 2 (Centralización) |
| **RF-07** | Publicación de convocatorias de admisión y matrícula | M02: Portal Público | Postulante | **MUST** | Dimensión 1 (Oportunidad) |
| **RF-08** | Repositorio público de documentos normativos (RI) | M02: Portal Público | Todos | **SHOULD** | Dimensión 2 (Acceso formal) |
| **RF-09** | Publicación de comunicados y directivas oficiales | M03: CMS Avisos | Secretaría | **MUST** | Dimensión 1 (Flujo/Oportunidad) |
| **RF-10** | Priorización y fijación de avisos urgentes | M03: CMS Avisos | Secretaría/Dir. | **MUST** | Dimensión 1 (Flujo/Oportunidad) |
| **RF-11** | Vigencia temporal y expiración automática | M03: CMS Avisos | Secretaría | **MUST** | Dimensión 1 (Flujo/Oportunidad) |
| **RF-12** | Motor de búsqueda reactivo y filtrado multicriterio | M03: CMS Avisos | Estudiante/Doc. | **MUST** | Dimensión 2 (Accesibilidad) |
| **RF-13** | Acuse digital y confirmación de lectura de avisos | M03: CMS Avisos | Estudiante | **SHOULD** | Dimensión 1 (Flujo/Oportunidad) |
| **RF-14** | Hub unificado de enlaces a sistemas del IESTP | M04: Hub Enlaces | Estudiante/Doc. | **MUST** | Dimensión 2 (Accesibilidad) |
| **RF-15** | Segmentación de accesos directos por perfil | M04: Hub Enlaces | Estudiante/Doc. | **SHOULD** | Dimensión 2 (Accesibilidad) |
| **RF-16** | Administración dinámica de URLs y servicios en CMS | M04: Hub Enlaces | Secretaría/Dir. | **MUST** | Dimensión 2 (Accesibilidad) |
| **RF-17** | Navegación interactiva del calendario académico | M05: Calendario | Todos | **MUST** | Dimensión 1 (Oportunidad) |
| **RF-18** | Gestión y programación de hitos académicos en CMS | M05: Calendario | Secretaría | **MUST** | Dimensión 1 (Oportunidad) |
| **RF-19** | Exportación ligera de cronogramas (.ics / PDF micro) | M05: Calendario | Estudiante | **SHOULD** | Dimensión 1 (Oportunidad) |
| **RF-20** | Formulación de consultas académicas estructuradas | M06: Interacción | Estudiante | **MUST** | Dimensión 3 (Interacción) |
| **RF-21** | Bandeja de recepción y respuesta para docentes | M06: Interacción | Docente/Secret. | **MUST** | Dimensión 3 (Interacción) |
| **RF-22** | Historial, seguimiento y estados de consulta | M06: Interacción | Estudiante | **MUST** | Dimensión 3 (Interacción) |
| **RF-23** | Campana de notificaciones de respuestas en SPA | M06: Interacción | Estudiante/Doc. | **SHOULD** | Dimensión 3 (Interacción) |
| **RF-24** | Dashboard con métricas de uso y telemetría de tesis | M07: Panel Admin | Dirección | **SHOULD** | Evaluación Postest |
| **RF-25** | Bitácora de auditoría inmutable (*Audit Logs*) | M07: Panel Admin | Administrador | **MUST** | Seguridad y Trazabilidad |
| **RF-26** | Almacenamiento seguro y compresión de archivos | M07: Panel Admin | Secretaría | **MUST** | Integridad Operativa |
| **RF-27** | Optimización de payloads JSON para planes prepago | M08: Rendimiento | Sistema | **MUST** | Rendimiento en Lircay |
| **RF-28** | Caché reactiva local para tolerancia a caídas 4G | M08: Rendimiento | Sistema | **SHOULD** | Resiliencia Técnica |

---

## 6. CONSIDERACIONES ARQUITECTÓNICAS Y VINCULACIÓN CON EL STACK TECNOLÓGICO

Para materializar los requerimientos funcionales precedentes, la implementación en el código fuente se mapea de forma coherente con la arquitectura desacoplada descrita en el Capítulo II de la tesis:

1. **Persistencia y Modelado en Base de Datos (MySQL con Laravel Eloquent):**
   - Las entidades principales concebidas para soportar los RF son: `users`, `roles`, `permissions`, `comunicados`, `comunicado_adjuntos`, `comunicado_lecturas`, `categorias`, `carreras`, `enlaces_servicios`, `eventos_calendario`, `consultas_academicas`, `respuestas_consultas` y `audit_logs`.
   - Se aplican claves foráneas con integridad referencial restrictiva, índices compuestos sobre columnas de consulta frecuente (`fecha_inicio`, `fecha_fin`, `categoria_id`, `estado`) para evitar escaneos secuenciales lentos de tablas en el servidor.

2. **Capa de Controladores y Servicios en Backend (Laravel 11):**
   - Se adopta el patrón *Service Layer* (Fowler, 2002) para separar la recepción de las peticiones HTTP (`Controllers`) de las reglas de negocio institucionales (`Services`), garantizando controladores delgados (*Slim Controllers*) y código altamente testeable mediante pruebas automatizadas con PHPUnit.
   - Las validaciones de entrada se aíslan en clases dedicadas de tipo `FormRequest` (ej. `StoreComunicadoRequest`, `StoreConsultaRequest`), impidiendo que datos corruptos alcancen los modelos.

3. **Capa de Presentación y Reactividad en Frontend (Vue 3 + Composition API):**
   - Uso de componentes reutilizables estructurados: tarjetas de avisos, modales de confirmación, tablas de datos adaptativas y botones con estados de carga (*spinners* asincrónicos para retroalimentación visual al usuario ante conexiones lentas).
   - Enrutamiento del cliente mediante `vue-router` con carga perezosa (*lazy loading*) de rutas administrativas, garantizando que el estudiante no descargue los archivos JavaScript correspondientes al panel de administración del CMS si solo desea consultar la cartelera de comunicados.

4. **Cumplimiento de Estándares de Rendimiento (ISO/IEC 25010):**
   - **Eficiencia de desempeño:** Tiempo de respuesta medio de endpoints de lectura < 350 ms; tiempo de renderizado de la SPA en smartphones de gama media-baja < 1.2 segundos.
   - **Usabilidad:** Interfaz que no requiere manuales previos de instrucción para estudiantes de primer semestre; navegación intuitiva mediante gestos táctiles estándar en dispositivos móviles.

---

## 7. REFERENCIAS CITADAS EN ESTA ESPECIFICACIÓN

- Atagua Ramirez, D. A. (2022). *Sistema web para la mejora en el seguimiento académico de la Institución Educativa 1220 San José Marello* (Tesis de pregrado, Universidad César Vallejo). Repositorio Institucional UCV. Handle: 20.500.12692/94131.
- Cedillo Crisosto, F. E. (2011). *Análisis, diseño, implementación e integración de un sistema de gestión de casos y un softphone Web para un centro de contacto virtual con múltiples medios de comunicación* (Tesis de pregrado, Pontificia Universidad Católica del Perú). Repositorio Institucional PUCP. Handle: 20.500.12404/341.
- Dávila Ordoñez, X. M., Leija Román, D. A., & Montes Rojas, M. L. (2023). Diseño de modelo para la valoración de identidad y reputación digital de información institucional de IES en entornos web. *Zincografía*, 7(13), 241-267. DOI: 10.32870/zcr.v7i13.202.
- Fowler, M. (2002). *Patterns of enterprise application architecture*. Addison-Wesley Professional.
- Gómez López, J. (2021). La comunicación institucional en los sitios web. *Visual Review. International Visual Culture Review*, 8(2), 161-172. DOI: 10.37467/gka-revvisual.v8.2917.
- IEEE Computer Society. (1998). *IEEE Recommended Practice for Software Requirements Specifications* (IEEE Std 830-1998). Institute of Electrical and Electronics Engineers.
- ISO/IEC/IEEE. (2018). *Systems and software engineering — Life cycle processes — Requirements engineering* (ISO/IEC/IEEE 29148:2018). International Organization for Standardization.
- Ley de Institutos y Escuelas de Educación Superior N° 30512. (2016). Publicada el 2 de noviembre de 2016. Diario Oficial El Peruano.
- Lupaca Martinez, D. F., & Angeles Morales, J. C. (2024). *Sistema web para la gestión de la información en la Institución Educativa Manuel Veramendi e Hidalgo, Arequipa, 2024* (Tesis de pregrado, Universidad Autónoma de Ica). Repositorio Institucional UAI. Handle: 20.500.14441/2941.
- Meza Piñas, J. E. (2024). *Sistema web basado en RUP para optimizar la gestión de marketing en la Academia Zárate, 2024* (Tesis de pregrado, Universidad Nacional del Centro del Perú). Repositorio Institucional UNCP. Handle: 20.500.12894/11427.
- Pressman, R. S., & Maxim, B. R. (2021). *Ingeniería del software: Un enfoque práctico* (9na ed.). McGraw-Hill Interamericana Editores.
- Quinto Munive, L. E. (2012). *Implementación de una aplicación web para la formulación de encuestas en línea a través de página web móvil de la UNCP aplicando UML basado en ingeniería web* (Tesis de pregrado, Universidad Nacional del Centro del Perú). Repositorio Institucional UNCP. Handle: 20.500.12894/1408.
- Sommerville, I. (2011). *Ingeniería del software* (9na ed.). Pearson Educación.
