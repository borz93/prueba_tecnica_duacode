# **Gestor de Equipos y Jugadores**

Este proyecto es una aplicación web para gestionar equipos y sus jugadores, permitiendo **crear, editar, eliminar y visualizar** registros de equipos y jugadores.

## **📌 Características**
* **Desarrollado en PHP (vanilla)**  
* **Arquitectura basada en MVC (Modelo-Vista-Controlador)**  
* **Autocarga de clases con `Autoload.php`**  
* **Sistema de enrutamiento propio (`Router.php`)**  
* **Persistencia en MySQL con modelos ORM (`BaseModel.php`)**  
* **Uso de Docker para el entorno de desarrollo**  
* **Validación de datos, en cliente como servidor**  
* **Mensajes informativos y de alerta**  
* **Interfaz del front basada en Bootstrap (cdn)**

## **📂 Estructura del Proyecto**

**Directorio raíz:**  

- **`app/`** → Código fuente principal
  - **`controllers/`** → Controladores (lógica de la aplicación)
      - `EquipoController.php`
      - `JugadorController.php`
  - **`models/`** → Modelos de base de datos (interaccion con BBDD)
      - `Equipo.php`
      - `Jugador.php`
  - **`core/`** → Funcionalidades centrales
    - `Autoload.php` (Carga automática de clases)
    - `ModelBase.php` (Modelo base)
    - `Controller.php` (Clase base de controladores)
    - `Database.php` (Conexión a MySQL con Singleton)
    - `Router.php` (Enrutamiento dinámico)
  - **`views/`** → Vistas (HTML + Bootstrap)
    - `equipos/` (Vistas para Equipos)
    - `jugadores/` (Vistas para Jugadores)
  - **`public/`** → Punto de entrada del proyecto
      - `index.php` (Carga el router y maneja las peticiones)
      - `.htaccess` (Redirecciones)
- **`docker-compose.yml`**
- **`Dockerfile`**
- **`readme.md`**
- **`.htaccess`**
- **`sql/`** → Directorio SQL
  - **`schema.sql`** → Script SQL para crear la base de datos  
- **`screenshots/`** → Imágenes de la aplicación

## **⚙️ Instalación y Configuración**

### **1️⃣ Clonar el repositorio**
```sh
    git clone https://github.com/borz93/prueba_tecnica_duacode.git  
```

### **2️⃣ Levantar el entorno con Docker (opcional)**
```sh
    docker-compose up -d
```

### **3️⃣ Configurar la base de datos**
Ejecutar el script `schema.sql`, y asignar usuario y password en `Database.php`

Si se usa docker, se ejecuta automaticamente.

### **4️⃣ .htcacces**
Si no se usa Docker, asegurarse que el entorno disponde de habilitar mod_rewrite y configurar AllowOverride.

Ver `Dockerfile` para mas información.

## **🛠️ Uso del Proyecto**

### **Rutas Disponibles**

Acceder a `http://localhost:8080` (depende del entorno usado)

#### **Equipos**
- `GET /equipos` → Listado de equipos (url por defecto)
- `GET /equipos/crear` → Formulario de creación
- `POST /equipos/crear` → Procesar nuevo equipo
- `GET /equipos/ver/{id}` → Ver detalles de un equipo
- `DELETE /equipos/eliminar/{id}` → Eliminar un equipo

#### **Jugadores**
- `GET /jugadores/crear/{equipo_id}` → Formulario de creación
- `POST /jugadores/crear/{equipo_id}` → Guardar nuevo jugador
- `GET /jugadores/editar/{id}` → Formulario de edición
- `POST /jugadores/editar/{id}` → Guardar edición
- `DELETE /jugadores/eliminar/{id}` → Eliminar un jugador

## **📌 Tecnologías Utilizadas**
- **PHP 8.1**
- **MySQL**
- **Docker & Docker Compose**
- **Bootstrap**
- **HTML, CSS, JavaScript**
- **PHPStorm**


