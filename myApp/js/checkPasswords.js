
const form = document.querySelector('#signUpForm');
form.addEventListener("submit", (e)=>{
    const pass1 = document.querySelector("#signUpPassword1").value;
    const pass2 = document.querySelector("#signUpPassword2").value;
    if(pass1 == pass2){
        return true;
    }else{
        alert("Passwords do not match");
        e.preventDefault();
    }
});