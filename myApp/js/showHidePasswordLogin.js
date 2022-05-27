function showHidePassLogin() {
    const eye = document.querySelector("#toggleLoginPassword");
    let x = document.querySelector("#loginPassword");
    if (x.type === "password") {
      x.type = "text";
      eye.classList.remove("bi bi-eye-fill showpwd");
      eye.classList.add("bi bi-eye-slash-fill showpwd");
      
    } else {
      x.type = "password";
      eye.classList.remove("bi bi-eye-fill showpwd");
      eye.classList.add("bi bi-eye-fill showpwd");
      
    }
  }  