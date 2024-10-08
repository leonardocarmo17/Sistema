const navLinks = document.querySelector('.nav-links');
    const menuIcon = document.getElementById('menu-icon');

function onToggleMenu(e) {
    e.name = e.name === 'menu' ? 'close' : 'menu';
    navLinks.classList.toggle('top-[5%]');
    navLinks.classList.toggle('menu-azul'); // Adiciona ou remove a classe 'menu-azul'
}
function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('hidden');
}
// Close dropdown if clicked outside
window.onclick = function(event) {
    const dropdown = document.getElementById('dropdown');
    if (!event.target.closest('.relative') && !event.target.closest('#notHidden')) {
        if (!dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    }
}
const chk = document.getElementById('chk');
chk.addEventListener('change', () => {
    document.body.classList.toggle('dark');
});
