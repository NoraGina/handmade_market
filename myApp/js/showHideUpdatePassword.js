
    function showHidePassUpdate() {
        let x = document.querySelector("#updatePassword1");
        if (x.type === "password") {
          x.type = "text";
          //this.className='bi bi-eye-slash-fill showpwd';
        } else {
          x.type = "password";
          //this.className = 'bi bi-eye-fill showpwd';
        }
      }  
