const chk = document.getElementById('chk');
chk.addEventListener('change', () => {
    document.body.classList.toggle('dark');
});
function updateFileName() {
    const input = document.getElementById('arquivo');
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
    var imagem = document.querySelector('input[name=arquivo]').files[0];
    var preview = document.getElementById('preview');
    var previewExistente = document.getElementById('preview-existente');

    var reader = new FileReader();

    reader.onloadend = function() {
        preview.src = reader.result;
        preview.style.display = 'block'; // Ensure the preview is visible
    }

    if (previewExistente) {
        previewExistente.style.display = 'none'; // Hide existing preview if a new file is selected
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
    document.getElementById('arquivo').value = '';
    document.getElementById('file-label').textContent = 'Selecionar';
    document.getElementById('arquivo_excluido').value = '1';
    document.getElementById('delete_image').value = '1';

    var preview = document.getElementById('preview');
    if (preview) {
        preview.src = '';
        preview.style.display = 'none';
    }

    var previewExistente = document.getElementById('preview-existente');
    if (previewExistente) {
        previewExistente.src = '';
        previewExistente.style.display = 'none';
    }
}
