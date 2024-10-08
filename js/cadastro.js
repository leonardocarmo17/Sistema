const chk = document.getElementById('chk');
chk.addEventListener('change', () => {
    document.body.classList.toggle('dark');
});

document.addEventListener('DOMContentLoaded', function() {
    const data_nascimento = document.getElementById('data_nascimento');
    const hoje = new Date();
    const dia = String(hoje.getDate()).padStart(2, '0');
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const ano = hoje.getFullYear();
    const dataAtual = `${ano}-${mes}-${dia}`; // Formato: YYYY-MM-DD
    data_nascimento.max = dataAtual;
});
    calendarIcon.addEventListener('click', function() {
    dateInput.showPicker();
});
function updateFileName() {
    const input = document.getElementById('imagem');
    const label = document.getElementById('file-label');
    const file = input.files[0];
    
    if (file) {
        const fileName = file.name;
        const maximo = 7;
    
        if (fileName.length > maximo) {
            label.textContent = fileName.substring(0, maximo) + '...';
        } 
        else {
            label.textContent = fileName;
        }
    } 
    else {
        label.textContent = 'Selecionar';
    }
}
function previewImagem() {
    var imagem = document.querySelector('input[name=imagem]').files[0];
    var preview = document.querySelector('img#preview');
    var reader = new FileReader();
    
    reader.onloadend = function() {
        preview.src = reader.result;
        preview.style.display = 'block'; // Ensure the preview is visible
    }
    if (imagem) {
        reader.readAsDataURL(imagem);
    } 
    else {
        preview.src = "";
        preview.style.display = 'none'; // Hide preview if no file is selected
    }
}
function deleteArquivo() {
    document.getElementById('imagem').value = '';
    document.getElementById('file-label').textContent = 'Selecionar';
    document.getElementById('arquivo_excluido').value = '1';
    document.getElementById('delete_image').value = '1';
    
    var preview = document.getElementById('preview');
    if (preview) {
        preview.src = '';
        preview.style.display = 'none';
    }
}