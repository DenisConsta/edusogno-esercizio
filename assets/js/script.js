
window.onload = function() {
  
  if(document.getElementById("email")) 
  document.getElementById("email").value = "";
  
  if(document.getElementById("password")){
    document.getElementById("password").type = 'password';
    document.getElementById("password").value = "";
    pswStatus = false;
  }

    if(document.getElementById("name")) 
    document.getElementById("name").value = "";

    if(document.getElementById("lastname")) 
    document.getElementById("lastname").value = "";
  
}
let pswStatus = false;

document.getElementById("eye").addEventListener("click", function(e) {
  pswStatus = !pswStatus;
  if(pswStatus){
    document.getElementById("password").type = 'text';
  }else{
    document.getElementById("password").type = 'password';
  }
});