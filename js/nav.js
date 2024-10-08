function menuShow(){
    let menuMobile = document.querySelector('.mobile-menu');
    if (menuMobile.classList.contains('open')){
        menuMobile.classList.remove('open');
        document.querySelector('.icon').scr = "/img/menu_white_36dp.svg";
    } 
    else{
        menuMobile.classList.add('open');
        document.querySelector('.icon').scr = "/img/close_white_36dp.svg";
    }
}
const chk = document.getElementById('chk');

chk.addEventListener('change', () => {
    document.body.classList.toggle('dark');
}) 
const chk2 = document.getElementById('chk2');

chk.addEventListener('change', () => {
    document.body.classList.toggle('dark');
}) 
function abrirOpc(){
    let opcoes = document.querySelector('.nav-list');
    if(opcoes.classList.contains('open')){
        opcoes.classList.remove('open');
        document
    }
}