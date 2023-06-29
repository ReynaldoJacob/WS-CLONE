$WS-CLONE

# Ejecución de la aplicación Quasar para el front-end

Este es un breve tutorial sobre cómo ejecutar una aplicación Quasar. Sigue estos pasos para poner en marcha la aplicación en tu entorno local.

## Requisitos previos

Asegúrate de tener instalados los siguientes requisitos antes de continuar:

- Node.js: [Descargar e instalar Node.js](https://nodejs.org)
- npm (Administrador de paquetes de Node.js): Normalmente se instala junto con Node.js.

## Pasos para ejecutar la aplicación de lado del front-end

1. Una vez instalado el administrador de paquetes, debes ejecutar el siguiete comando:
```
npm install -g @quasar/cli
```

2. una vez clonado el proyecto, ingresa el la carpeta llamada "frontend-project" y ejecuta la instalacion de dependencias

  ```
  npm install
  ```

3. Ejecuta el servidor de quasar

  ```
  quasar dev
  ```
4. Automaticamente se abrira el navegador y tendras listo el proyecto del lado del front-end.

## Pasos para ejecutar la aplicación de lado del back-end

## Requisitos previos

Para el back-end se esta usando laravel para atender las peticiones de las API RestFul.

Antes de comenzar, asegúrate de tener instalado lo siguiente en tu sistema:

PHP (versión 7.4 o superior)
Composer
MySQL (u otro sistema de gestión de bases de datos compatible)



1. Para empezar necesitamos acceder a la carpeta llamada "backend-project" y ejecutar la instalacion de las dependencias del proyecto utilizando Composer. 
```
  composer install
  ```
2. Crea un archivo de configuración .env basado en el archivo .env.example.
   
3. Genera una clave de aplicación.
```
  php artisan key:generate
  ```
4. Configura los detalles de la base de datos en el archivo .env. Asegúrate de proporcionar los valores correctos para DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME y DB_PASSWORD.

5. Ejecuta las migraciones de la base de datos para crear las tablas necesarias.

```
  php artisan migrate
  ```
6. Inicia el servidor de desarrollo de Laravel.
```
  php artisan serve
  ```





