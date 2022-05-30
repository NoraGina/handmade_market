

function showModal(){
    let modal = document.querySelector('#addressModal');
    modal.classList.remove("d-none");
    modal.classList.add("d-block");
}
function toggleAddressModal(){
    let button = document.querySelector("#addressBtn");
    button.addEventListener('click', showModal)
    console.log("I working");
}