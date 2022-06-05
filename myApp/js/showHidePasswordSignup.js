
    function showHidePassSignup() {
        let x = document.querySelector("#signupPassword1");
        if (x.type === "password") {
          x.type = "text";
          
        } else {
          x.type = "password";
         
        }
      }  
