
    function showHidePassSignup() {
        let x = document.querySelector("#signupPassword1");
        if (x.type === "password") {
          x.type = "text";
          this.className='bi bi-eye-slash-fill showpwd';
        } else {
          x.type = "password";
          this.className = 'bi bi-eye-fill showpwd';
        }
      }  
