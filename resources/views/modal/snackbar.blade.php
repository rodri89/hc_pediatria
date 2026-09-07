<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
#snackbar {
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  padding: 16px;
  position: fixed;
  z-index: 1;
  left: 50%;
  bottom: 30px;
  font-size: 17px;
}

#snackbar.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;} 
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;} 
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}
</style>

<div id="snackbar"><input class="input_snackbar_300px" id="text_snackbar" name="text_snackbar"></input></div>

<div hidden id="snackbar_large"><input class="input_snackbar_500px" id="text_snackbar_large" name="text_snackbar_large"></input></div>

<script type="text/javascript">
  
  function mostrarSnackbar(text) {        
    document.getElementById("text_snackbar").value = text;
    var x = document.getElementById("snackbar");
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }

  function mostrarSnackbarLarge(text) {        
    document.getElementById("text_snackbar_large").value = text;
    var x = document.getElementById("snackbar_large");
    x.hidden = false;
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);    
  }  

</script>