function campoVacio(campo){
  if (campo.length == 0){
    return true;
  }
  return false;
}



function validarBuscador () {
	var titulo, autor;
	titulo = document.getElementById("Titulo").value;
	autor = document.getElementById("Autor").value;


	if (campoVacio(titulo) && campoVacio(autor)){
		alert ("Debes ingresar un título y/o un autor!");
		return false;
	}
}



function validarRegistro(){

  	var nombre , apellido, email, archivo, cont, conf, expresion, extension;
  	extensiones_permitidas = new Array(".gif", ".jpeg", ".jpg", ".png"); 

  	nombre = document.getElementById("nombre").value;
  	apellido = document.getElementById("apellido").value;
  	archivo = document.getElementById("foto").value;
  	email = document.getElementById("mail").value;
  	cont = document.getElementById("cont").value;
  	conf = document.getElementById("conf").value;

  	expresion = /\w+@+\w+\.+[a-z]/;

  	extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
  	permitida = false;
  	for (var i = 0 ; i < 4 ; i++){
  		if (extensiones_permitidas[i] == extension){
  			permitida = true;
  			break;
  		}
  	}
    password = /^((?=.*\d)|(?=.*[\u0021-\u002b\u003c-\u0040]))(?=.*[A-Z])(?=.*[a-z])\S{6,16}$/;

    soloLetras = /^[a-zA-z\s]*$/;
  	if (campoVacio(nombre) || campoVacio(apellido) || campoVacio(email) || campoVacio(cont) || campoVacio(conf)){
  		alert ("Debe completar todos los campos.");
  		return false
  	}
    else if (!nombre.match(soloLetras) || !apellido.match(soloLetras)){
      alert("EL nombre y el apellido solo puede contener letras");
      return false;

    }
    else if (!permitida){
  		alert ("El archivo a subir no es una imágen ! Verifique que su extensión sea \".jpg\", \".jpeg\" , \".png\" o \".gif\" .");
  		return false;
  	} 
  	else if (!expresion.test(email)){
  		alert ("Debe ingresar un mail respetando el formato, por ejemplo usuario@gmail.com");
  		return false;
  	}
  	else if (cont !== conf){
  		alert ("Las contraseñas no coinciden!");
  		return false;
  	}
    else if (cont.length < 6){
      alert("La contraseña debe contener al menos 6 caracteres.");
      return false;
    }
    else if (!password.test(cont)) {
    	alert("La contraseña debe tener al menos una mayuscula, una minuscula y un caracter especial o numero");
    	return false;
    } 
}



function validarIniciar(){

  var email = document.getElementById("mailIniciar").value;
  var cont = document.getElementById("contIniciar").value;

  if (campoVacio(email) || campoVacio(cont)){
    alert ("Debes completar todos los campos!");
    return false;
  }
}

