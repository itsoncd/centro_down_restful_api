<p align="center">
  <img src="./public/images/laravel.png" width="200" />
</p>

# Proyecto Laravel 🚀  
Este es un proyecto desarrollado en Laravel con Docker para facilitar el despliegue y desarrollo.  

## 📌 Requisitos  
- [Docker](https://www.docker.com/) y [Docker Compose](https://docs.docker.com/compose/)  
- [Composer](https://getcomposer.org/) (Gestor para Laravel)  
- [Node.js](https://nodejs.org/) (opcional, si usas frontend con Laravel Mix o Vite)  

## 🚀 Instalación  
### 1 - Clonar el repositorio  
```bash  
git clone https://github.com/itsoncd/centro_down_restful_api.git  
cd centro_down_restful_api
```

### 2 - Copia el archivo env.example y renombralo a .env
```bash  
cp .env.example .env
```

### 3 - Configura el archivo env
```bash  
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nombre_base_de_datos
DB_USERNAME=root
DB_PASSWORD=contraseña

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tuCorreoElectronico
MAIL_PASSWORD=tuClave

```

### 4 - Instalar dependencias
```
composer install
npm install
```

### 5 - Ejecutar comando Docker para inicializar el contenedor
```
docker-compose up -d
```

### 6 - Ejecutar las migraciones de la base de datos
```
php artisan migrate
```

### 7 - Levantar servidor
```
php artisan serve
```
