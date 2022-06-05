const form = document.querySelector('#updateForm');
form.addEventListener("submit", (e)=>{
    const pass1 = document.querySelector("#updatePassword1").value;
    const pass2 = document.querySelector("#supdatePassword2").value;
    if(pass1 == pass2){
        return true;
    }else{
        alert("Passwords do not match");
        e.preventDefault();
    }
});