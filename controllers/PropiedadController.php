<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController{
    public static function index(Router $router){

        $propiedades = Propiedad::all();

        $vendedores = Vendedor::all();

        //Muestra mensaje condicional
        $resultado = $_GET['resultado'] ?? null;

        $router->render('propiedades/admin', [ 
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }

    public static function crear(Router $router){

        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        //Arreglo con mensajes de errores
        $errores = Propiedad::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            /** Crea una nueva instancia */
            $propiedad = new Propiedad($_POST['propiedad']);
    
            /** Subida de archivos*/
            //Generar nombre único
            $nombreImagen = md5(uniqid(rand(), true ) ) . ".jpg"; 
    
            //Setear la imagen
            //Realiza un resize a la imagen con intervetion
            if($_FILES['propiedad']['tmp_name']['imagen']){
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen']) -> fit(800,600);
                $propiedad->setImagen($nombreImagen);//Se guarda el nombre de la imagen
            }
            
            //Validar
            $errores = $propiedad -> validar();
                
    
            //Revisar que el arreglo de errores esté vacio
            if (empty($errores)) {
    
                //Crear carperta
                if(!is_dir(CARPETA_IMAGENES)){
                    mkdir(CARPETA_IMAGENES);
                }
    
                //Guarda la imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);
                
                //Guarda en la base de datos
                $propiedad -> guardar();
    
                
            }
        }

        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router){
        
        $id = validarORedirecccionar('/admin');
        $propiedad = Propiedad::find($id);
        $errores = Propiedad::getErrores();
        $vendedores = Vendedor::all();

        //Ejecutar el codigo despues de que el usuario envía el formulario
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Asignar los atributos
            
            $args = $_POST['propiedad'];
            
            $propiedad->sincronizar($args);
            
            //Validación
            $errores = $propiedad -> validar();

            //Subida de archivos
            //Generar nombre único
            $nombreImagen = md5(uniqid(rand(), true ) ) . ".jpg";
            //Realiza un resize a la imagen con intervetion 
            if($_FILES['propiedad']['tmp_name']['imagen']){
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen']) -> fit(800,600);
                $propiedad->setImagen($nombreImagen);//Se guarda el nombre de la imagen
            }

            //Revisar que el arreglo de errores esté vacio
            if(empty($errores)) {
                //Almacenar la imagen
                if($_FILES['propiedad']['tmp_name']['imagen']){
                    $image ->save(CARPETA_IMAGENES.$nombreImagen);
                }
                //Guardar los datos de la propiedad
                $propiedad->guardar();
            }
        }

        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }

    public static function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Validar ID
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            
            if ($id) {

                $tipo = $_POST['tipo'];

                if (validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }
                
            }
        }
    }
}