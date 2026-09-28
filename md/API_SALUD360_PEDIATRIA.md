| `app/Http/Controllers/Api/Salud360/*` | `Auth`, `Paciente`, `Consulta`, `Foto` || `app/Services/Salud360/RegistrosService.php` | Las cuatro listas: exámenes complementarios, interconsultas, screenings e internaciones |
| `app/Services/Salud360/FotosService.php` | Las galerías de fotos y archivos adjuntos |
# API Salud 360 — Historia clínica de pediatría

API REST que consume la app **Salud 360** para leer y escribir **la misma base que la web de pediatría**.
Lo que el médico carga en la app lo ve en `pediatria.hclinicadigital.com`, y al revés. Una sola historia clínica.

- Prefijo: `/api/salud360/`
- Formato JSON. Fechas `AAAA-MM-DD`.
- Todas las respuestas traen `ok: true|false`. Los errores traen `mensaje` y `codigo`.

## Identidad: no hay login acá

**turnosonlinebb es el único proveedor de identidad.** Esta API no tiene `auth/login` ni emite tokens.
La app inicia sesión en turnosonlinebb y manda ese mismo token; acá se valida contra
`GET {tobb_url}/api/salud360/auth/perfil` y se ubica al médico local por `users.medico_id_tobb`.

Token en `Authorization: Bearer <token>` o, si el hosting descarta ese encabezado, `X-Salud360-Token`.

Para que un médico entre hacen falta **dos cosas independientes**, y cada una falla con su propio código:

| HTTP | `codigo` | Qué falta |
|---|---|---|
| 401 | `sin_token` | No mandó token |
| 401 | `token` | turnosonlinebb rechazó el token (sesión vencida) |
| 403 | `hc_no_habilitada` | El administrador no le habilitó `pediatria` en turnosonlinebb (`salud360_medico_hc`) |
| 403 | `medico_no_vinculado` | Falta cargar `users.medico_id_tobb` en pediatría. Devuelve el número esperado |
| 403 | `sin_permiso` | El usuario no es médico (por ahora solo médicos) |
| 503 | `tobb_caido` | No se pudo validar y no hay sesión en caché utilizable |

Cada rechazo por falta de vínculo deja una fila en `salud360_vinculos_pendientes`: es la lista de trabajo
del administrador, en vez de un fallo mudo.

**Si turnosonlinebb no responde**, una sesión ya validada se sigue aceptando hasta 24 horas
(`salud360.gracia_horas`). Una caída de turnos no puede dejar al pediatra sin historia clínica.

## Código

| Archivo | Qué hace |
|---|---|
| `app/Services/Salud360/TobbAuthService.php` | Valida el token contra turnosonlinebb, cachea la sesión y resuelve el médico local |
| `app/Services/Salud360/PacienteVinculoService.php` | Decide a qué paciente de pediatría corresponde el de la app |
| `app/Services/Salud360/SeccionesService.php` | Traduce el modelo genérico de la app a las tablas de pediatría |
| `app/Services/Salud360/RegistrosService.php` | Las cuatro listas: exámenes complementarios, interconsultas, screenings e internaciones |
| `app/Services/Salud360/FotosService.php` | Las galerías de fotos y archivos adjuntos |
| `app/Http/Middleware/Salud360Api.php` | Autenticación (alias `salud360`) |
| `app/Http/Middleware/Salud360Cors.php` | CORS global, contesta el preflight |
| `app/Http/Controllers/Api/Salud360/*` | `Auth`, `Paciente`, `Consulta`, `Foto` |
| `config/salud360.php` | URL de turnos, caché, gracia, autoprovisión |

Las tablas `salud360_token_cache`, `salud360_vinculos_pendientes` y la columna `pacientes.paciente_id_tobb`
**se crean solas** si no existen, porque el hosting no corre migraciones.

> **Regla que no se toca:** la API nunca lee ni escribe `medico_infos`. Esa tabla guarda qué paciente y
> qué consulta está mirando el médico **en la web**; si la API la pisa, al pediatra se le mueve la pantalla
> mientras atiende. La API recibe siempre los identificadores de forma explícita.

## Endpoints

### Sesión

| Método | Ruta | Devuelve |
|---|---|---|
| GET | `auth/perfil` | usuario local, rol, `medico_id_tobb` y lo que informa turnosonlinebb |

### Pacientes

| Método | Ruta | Cuerpo |
|---|---|---|
| POST | `pacientes/resolver` | ficha completa del paciente |
| GET | `pacientes/{id}` | |

`pacientes/resolver` decide a qué paciente de pediatría corresponde, creándolo si no existe, y lo suma a la
cartera del médico. Orden: por `paciente_id_tobb`, por elección explícita (`pediatria_paciente_id`), o por
documento. Con varios candidatos desempata por fecha de nacimiento y apellido.

Respuesta: `{ok, paciente, creado, vinculado_por}` con `vinculado_por` en `tobb_id | elegido | dni | nuevo`.

| HTTP | `codigo` | Cuándo |
|---|---|---|
| 409 | `paciente_ambiguo` | Varios pacientes con ese documento y no se pudo desempatar. Devuelve `candidatos` |
| 409 | `paciente_ya_vinculado` | Ese paciente ya está vinculado a otro paciente de turnos |
| 422 | `dni_invalido` | Documento vacío, no numérico o fuera de rango |

**Nunca adivina**: ante la duda devuelve los candidatos para que elija el médico. Vincular al hermano
equivocado cruzaría dos historias clínicas.

**Nunca pisa datos con vacíos**: solo completa campos que estén vacíos en pediatría. La ficha de acá tiene
padre, madre, sexo y hermanos que turnos no conoce.

### Consultas

| Método | Ruta | Cuerpo / Query |
|---|---|---|
| GET | `consultas` | `paciente_id`, `[limite=50]` |
| POST | `consultas/abrir` | `paciente_id`, `tipo`, `[fecha]`, `[edad_mostrar]` |
| GET | `consultas/{id}` | |
| PUT | `consultas/{id}/secciones` | `secciones: { "<id>": {"<campo>": "<valor>"} }` |
| PUT | `consultas/{id}/examen` | `examen_fisico: {...}` |
| PUT | `consultas/{id}/registros` | `registros: [ {ref, tipo, [id], [fecha], campos, [borrado]} ]` |
| POST | `consultas/{id}/cerrar` | `[fecha]`, `[edad_mostrar]` |
| POST | `consultas/{id}/reabrir` | |
| DELETE | `consultas/{id}` | baja lógica |

**Permisos:** se escribe solo sobre consultas propias (`403 consulta_ajena`); se lee cualquier consulta de un
paciente de la cartera del médico, igual que hace la web.

`consultas/abrir` **reutiliza** la consulta abierta del mismo médico, paciente y tipo si ya existe. Así un
reintento de la app tras quedarse sin señal no genera duplicados.

Una sección ausente en el cuerpo **no se toca**, para que la app mande solo lo que cambió. Las desconocidas
se ignoran y se informan en `desconocidas`, para que la app no las dé por guardadas.

### Fotos y archivos adjuntos

| Método | Ruta | Cuerpo / Query |
|---|---|---|
| GET | `consultas/{id}/fotos` | |
| POST | `consultas/{id}/fotos` | **multiparte**: `tipo`, `ref`, `[padre_id]`, `archivo` |
| GET | `fotos/{tipo}/{id}/archivo` | `consulta_id` |
| DELETE | `fotos/{tipo}/{id}` | `consulta_id` |

`tipo` dice a qué galería va, y cada una es una tabla distinta de pediatría:

| `tipo` | Tabla | Cuelga de |
|---|---|---|
| `consulta` | `consulta_fotos` | la consulta |
| `neonatales` | `antecedentes_neonatales_fotos` | la ficha de antecedentes neonatales del paciente |
| `examen_complementario` | `examenes_complementarios_fotos` | la fila de la lista, que va en `padre_id` |
| `internacion` | `internaciones_fotos` | la fila de la lista, que va en `padre_id` |
| `familigrama` | `familigramas` | el paciente |

Cinco cosas para entenderlas:

- **Un archivo por pedido.** El médico saca la foto en el consultorio, donde la señal es mala; si se
  corta a la mitad se reintenta esa sola y no las diez de la consulta. La respuesta trae
  `{ok, id, ids: {ref => id}, url}`: `ref` es el id que el archivo tiene en el dispositivo, e `id` con
  el que quedó acá. **Sin ese par, el segundo envío sube la misma foto de nuevo.**
- **Los dos que cuelgan de una lista necesitan `padre_id`**, que es el id de la fila en pediatría, el
  que devolvió `consultas/{id}/registros`. Si falta o no es de ese paciente: `422 padre_no_encontrado`.
- **Se aceptan imágenes y PDF**, hasta 12 MB (`422 tipo_de_archivo`, `422 archivo_grande`). Las
  imágenes se achican a 1980×1920 **conservando la proporción**, y nunca se agrandan. La web llama a
  `resize(1980, 1920)` a secas, que deforma; acá no. Los PDF se guardan tal cual: la app los deja
  adjuntar y la web todavía no los muestra.
- **El archivo va a `public/img/<usuario>/<carpeta>/`**, donde `<usuario>` es lo que va antes de la
  arroba del mail del médico, igual que en la web, y la columna `foto` guarda
  `<usuario>/<carpeta>/<nombre>`, sin el `img/` de adelante. El nombre se sortea: dos madres que
  mandan `ecografia.jpg` no se pisan. A diferencia de la web, **no** se escribe además una copia en
  `storage/app` que nadie lee.
- **Borrar es `activo = 0` y el archivo queda en el disco.** Una foto de una historia clínica no se
  tira porque alguien tocó el botón equivocado en el teléfono. Es lo que hace hoy la web, que
  directamente no tiene borrado.

`fotos/{tipo}/{id}/archivo` devuelve el archivo, no JSON, y comprueba que el paciente sea de la cartera
del médico. Se expone porque `public/img/` lo abre cualquiera que tenga el enlace, y la app ya manda el
token en cada pedido. La URL pública igual viene en `url`, porque es la que usa la web. Si la fila está
pero el archivo no —pasa con fotos viejas subidas a otro hosting—: `404 archivo_ausente`, con la `url`
para que la app no lo confunda con un error de red.

### Mapeo

**Consulta**: el estado va a `consultas.activo` (`2` abierta, `1` cerrada, `0` borrada) y el tipo al número de
la web (`control` 1, `enfermedad` 2, `foto` 3, `telemedicina` 4, `prenatal` 5, `lactancia` 6). **La fecha
clínica es `created_at`**: no hay columna de fecha, igual que en `establecerActivo` de la web. La edad en
meses la calcula la API.

**Secciones de texto**: quince secciones de la app contra quince tablas con la forma
`{consulta_id, paciente_id, descripcion, activo}`, con la misma regla de escritura que
`MedicoController::guardarMotivoConsulta`.

| App | Pediatría | App | Pediatría |
|---|---|---|---|
| `motivo_consulta` | `motivo_consultas` | `pantallas` | `pantallas` |
| `observaciones` | `observaciones` | `habitos` | `habitos` |
| `nota` | `notas` | `menarca` | `menarcas` |
| `conductas` | `conductas` | `somnia` | `somnias` |
| `datos_subjetivos` | `datos_subjetivos` | `diuresis_catarsis` | `catarses` |
| `datos_objetivos` | `datos_objetivos` | `actividades` | `actividades_extra_escolares` |
| `escolaridad` | `escolaridads` | `vacunas` | `vacunas_dos` |
| `lactancia_previa` | `lactancia_embarazo_previos` | | |

**Secciones con tabla propia**: cada una tiene sus columnas, así que el mapeo es campo a campo y está
declarado en `SeccionesService::FORMULARIOS`. Agregar una sección es agregar una entrada ahí.

| App | Pediatría | Fila |
|---|---|---|
| `alimentacion` | `alimentacions` | una por consulta |
| `perinatales` | `antecedentes_perinatales` | una por paciente |
| `neonatales` | `antecedentes_neonatales` | una por paciente |
| `desarrollo` | `desarrollo_madurativo_pacientes` | una por hito y consulta |

Tres cosas para entenderlas:

- **Las columnas de opciones tienen tres estados**: `1` y `0` son las dos respuestas y **`2` es "sin
  cargar"**, que es con lo que nacen. Un campo que la app no manda queda en 2: devolver 0 sería
  inventar una respuesta ("no", "-", "anormal") que el médico nunca dio. Al leer, el 2 vuelve vacío.
- **El detalle solo se escribe si viene con algo**, así una casilla sin detalle no le borra al
  pediatra el que escribió en la web. `vdrl_detalle` y `chagas_detalle` ni se mapean: la app no tiene
  dónde cargarlos.
- **Las de paciente se escriben en la fila que la web lee** (por paciente, la primera activa), no en
  una nueva por consulta. Es lo que hace `existeAntecedentePerinatal`: los antecedentes son una ficha
  sola que se arrastra entre consultas.

**Desarrollo madurativo**: no son columnas sino una fila por hito. La app manda la sección `desarrollo`
con un campo `dm_<id>` por hito (`1` logrado / `0` no logrado), donde `<id>` es el mismo
`desarrollo_madurativos.id` de la web, más un campo `observacion`. La observación viaja del otro lado
como un hito más, el único de tipo `Observacion`, con `checked = 2` y el texto en su columna.

**Listas** (`RegistrosService`): las secciones con varias filas. En la app son `registro_clinico`, con
id propio; acá cada una tiene su tabla.

| App | Pediatría | Lista de | |
|---|---|---|---|
| `examen_complementario` | `examenes_complementarios` | la consulta | se confirma al cerrar |
| `interconsulta` | `interconsultas` | la consulta | se confirma al cerrar |
| `screening` | `screenings` | el paciente | sin `consulta_id` |
| `internacion` | `internaciones` | el paciente | cuelga de `antecedentes_personales` |

Cuatro cosas para entenderlas:

- **Cada fila viaja con su `ref`**, que es el id que tiene en el dispositivo, y la respuesta devuelve
  `ids: {ref => id de pediatría}`. La app guarda ese id y lo manda como `id` a partir de la segunda
  vez. Sin ese par, cada envío duplicaría la fila.
- **"Se confirma al cerrar"**: exámenes complementarios e interconsultas nacen con `activo = 2`
  ("cargado en la consulta abierta") y pasan a `1` cuando se cierra la consulta, igual que hace
  `establecerActivo` de la web. Sin esa vuelta la web no los vuelve a mostrar nunca.
- **`numero`** es la posición dentro del paciente, la que mueven los botones "anterior" y "siguiente"
  de la web. La asigna la API al crear y no se toca más.
- **La internación cuelga de la ficha de antecedentes personales** de la consulta por clave foránea,
  porque en la web se la carga desde ahí. Si no existe, la API la crea vacía.

**Examen físico**: `peso`, `talla`, `imc` y sus percentilos, `perimetro_cefalico` → `pc`,
`tension_arterial` → `ta`, `ipd` y `nota`. Frecuencia cardíaca, temperatura, saturación y circunferencia
abdominal no existen en pediatría y se ignoran sin pérdida: la app no los muestra para esta especialidad.

## Configuración

En `.env`:

```
SALUD360_TOBB_URL=https://turnosonlinebb.com    # en desarrollo, el MAMP local
SALUD360_CACHE_MINUTOS=10
SALUD360_GRACIA_HORAS=24
SALUD360_AUTOPROVISION=false
```

Al agregar o cambiar estas claves hay que correr `php artisan config:clear`, porque la configuración
cacheada no incluye archivos nuevos.

## Pendiente

- **Fase 2: terminada.** Andan las secciones de texto, el examen físico, los formularios
  (alimentación, antecedentes perinatales y neonatales, antecedentes personales y familiares y las
  tres de la consulta prenatal), el desarrollo madurativo y las cuatro listas.
- **Fase 3: terminada.** Andan las fotos y los archivos adjuntos de las cinco galerías.
- **Falta de la fase 3:** las vacunas en grilla siguen como texto libre, y los PDF se guardan pero la
  web todavía no los muestra.
- **Fase 4:** pendientes, secretarias y aviso de edición simultánea.
