const update = document.querySelector('#updateForm');
update.addEventListener("submit", (e)=>{
    const pass1 = document.querySelector("#updatePassword1").value;
    const pass2 = document.querySelector("#updatePassword2").value;
    if(pass1 == pass2){
        return true;
    }else{
        alert("Passwords do not match");
        e.preventDefault();
    }
    console.log("Hello");
});