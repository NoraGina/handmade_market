


function closeModal(){
    let modal = document.querySelector('#addressModal');
    modal.classList.remove("d-block");
    modal.classList.add("d-none");
}
function removeAddressModal(){
    let button = document.querySelector("#spanBtn");
    button.addEventListener('click', closeModal);
    console.log("Close");
}