$(function(){
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });

            $(window).resize(function(e) {
              if($(window).width()<=768){
                $("#wrapper").removeClass("toggled");
              }else{
                $("#wrapper").addClass("toggled");
              }
            });
    
              if($(window).width()<=768){
                $("#wrapper").removeClass("toggled");
              }
	
	

       const toggleSubmenus = document.querySelectorAll('.toggle-submenu');

  // Agrega un evento click a cada elemento
  toggleSubmenus.forEach(function(toggleSubmenu) {
    toggleSubmenu.addEventListener('click', function() {
      // Selecciona el siguiente elemento hermano, que es el submenú
      const submenu = this.nextElementSibling;
	vandera=0;	
	if(submenu.classList.contains('active')){
	vandera=1;	
	}	
		
	   // Remueve la clase 'active' de todos los elementos principales
      document.querySelectorAll('.sidebar-nav li ul').forEach(function(item) {
        item.classList.remove('active');
      });
		
	  document.querySelectorAll('.sidebar-nav li').forEach(function(item) {
        item.classList.remove('active');
      });

      // Alterna la clase 'active' para mostrar u ocultar el submenú
	  if(vandera==0){
	    submenu.classList.toggle('active');
		 // Añade la clase 'active' al elemento principal seleccionado
      this.parentElement.classList.toggle('active');  
	  }	
      
    });
  });   
             
          });

function clave(id){
	$("#iduser").val(id);
}

$("#formclave").on("submit",function(e)//e = variable que contiene el objeto
    {
    e.preventDefault(); //No se activará la acción predeterminada del evento
	if(validaPwds()){
	  var formData = new FormData($("#formclave")[0]);
      $.ajax({
        url: "../Control/ColaboradorControl.php?op=editarclave",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
            document.getElementById("formclave").reset();
			$('#modal-clave').modal('hide');   
            
        }
    });
    
	}
   
 });


function validaPwds() {
    var c1=$("#clave").val();
    var c2=$("#clave2").val();
        if (c2 === c1) {
            return true;
        }else{
            bootbox.alert("La clave no es igual");
            $("#clave").val('');
            $("#clave2").val('');
            return false;
        }
    
    
}
