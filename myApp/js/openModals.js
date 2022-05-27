
function openProductModal(){
    let buttons = document.querySelectorAll(".open-modal");
    let modals = document.querySelectorAll('.products-modal');
    let spans = document.querySelectorAll('.close-modal');

    let closeFunction = function(){
      
             for(let i = 0; i<modals.length;i++){
                   modals[i].classList.remove("show");
               }
              }
            for(let i = 0; i<spans.length;i++){
                 spans[i].addEventListener('click', closeFunction);
                    
             }
    
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener("click", function (event) {
          let modal = (document.getElementById(
            event.target.getAttribute("data-target")
          ).classList.toggle("show"));
        });
}
        
}

// // Feel free to use any other way to iterate over buttons
// for (let i = 0; i < buttons.length; i++) {
//   buttons[i].addEventListener("click", function (event) {
//     let modal = (document.getElementById(
//       event.target.getAttribute("data-target")
//     ).style.display = "block");
//   });
// }