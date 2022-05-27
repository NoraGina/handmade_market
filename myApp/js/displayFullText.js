


function displayAll(){
  let buttons = document.querySelectorAll(".read-more-btn");
  
 for (let i = 0; i<buttons.length; i++){
   buttons[i].addEventListener('click', function(event){
     
      let text = document.getElementById(event.target.getAttribute("data-target"));
      text.classList.toggle('show-text');
     let moreButton= document.getElementById(event.target.id);
     let value = document.getElementById(event.target.id).innerHTML;
     moreButton.innerHTML = moreButton.innerHTML === ' Citește mai puțin' ? ' Citește mai mult' : ' Citește mai puțin';
    // moreButton.classList.add("d-none");
     console.log(value);
     console.log(event.target.id)
    
    
   })
 }
}


  
             
