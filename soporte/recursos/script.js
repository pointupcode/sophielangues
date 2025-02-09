$(document).ready(function() {
    // Datos de ejemplo (esto se reemplazaría con datos del backend)
    const modules = [
        { name: 'Módulo 1: Introducción', completed: true },
        { name: 'Módulo 2: Gramática Básica', completed: true },
        { name: 'Módulo 3: Vocabulario Esencial', completed: false },
        { name: 'Módulo 4: Conversación', completed: false },
    ];

    const myCourses = [
        { name: 'Inglés para Principiantes', id: 1 },
        { name: 'Francés Intermedio', id: 2 },
    ];

    const availableCourses = [
        { name: 'Alemán para Viajeros', id: 3 },
        { name: 'Italiano Avanzado', id: 4 },
    ];
    
  //Función para cargar la documentación de cada módulo
  function cargarDocumentacion(module){
      $('#module-documentation').empty();
      if(module == 1){
           $('#module-documentation').append(`
                <li><a href="ruta/al/documento1.pdf" target="_blank">Documento 1</a></li>
                <li><a href="ruta/al/documento2.pdf" target="_blank">Documento 2</a></li>
             `);
      }else if (module == 2){
            $('#module-documentation').append(`
                <li><a href="ruta/al/documento3.pdf" target="_blank">Documento 3</a></li>
                <li><a href="ruta/al/documento4.pdf" target="_blank">Documento 4</a></li>
             `);
      }else if(module == 3){
           $('#module-documentation').append(`
                <li><a href="ruta/al/documento5.pdf" target="_blank">Documento 5</a></li>
                <li><a href="ruta/al/documento6.pdf" target="_blank">Documento 6</a></li>
             `);
       } else if(module == 4){
           $('#module-documentation').append(`
                <li><a href="ruta/al/documento7.pdf" target="_blank">Documento 7</a></li>
                <li><a href="ruta/al/documento8.pdf" target="_blank">Documento 8</a></li>
             `);
        }
  }
  

    // Insertar módulos
    modules.forEach((module, index) => {
        const statusClass = module.completed ? 'completed' : '';
        $('#module-list').append(
          `<li class="${statusClass}" data-module-id="${index + 1}">
           <span>${module.name}</span>
           <span>${module.completed ? 'Completado' : 'Pendiente'}</span>
           </li>`
          );
      });

   //Evento click para cambiar la documentación en el div de "Modulo Actual"
    $('#module-list li').click(function(){
      const moduleId = $(this).data('module-id');
       cargarDocumentacion(moduleId);
    });

    // Insertar cursos comprados
    myCourses.forEach(course => {
        $('#my-courses-list').append(`<li>${course.name}</li>`);
    });

    // Insertar cursos disponibles
    availableCourses.forEach(course => {
        $('#available-courses-list').append(`<li>${course.name}</li>`);
    });


    // Manejo del formulario de ayuda
    $('#help-form').submit(function(event) {
        event.preventDefault();
        // Aquí se podría enviar la información al backend
       $('#response-message').show();
        $('#help-form')[0].reset();
    });

    const video = document.getElementById('mainVideo');
const playPauseBtn = document.getElementById('playPauseBtn');
const seekBar = document.getElementById('seekBar');
const currentTimeSpan = document.getElementById('currentTime');
const durationSpan = document.getElementById('duration');
const muteBtn = document.getElementById('muteBtn');
const volumeBar = document.getElementById('volumeBar');
const fullScreenBtn = document.getElementById('fullScreenBtn');

let isPlaying = false;

// Funciones de tiempo (formato 00:00)
function formatTime(time) {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60).toString().padStart(2, '0');
    return `${minutes}:${seconds}`;
}

// Cargar metadata del video (duración)
video.addEventListener('loadedmetadata', () => {
  durationSpan.textContent = formatTime(video.duration);
});


// Botón de Play/Pause
playPauseBtn.addEventListener('click', () => {
    if (isPlaying) {
        video.pause();
        playPauseBtn.textContent = 'Reproducir';
    } else {
        video.play();
        playPauseBtn.textContent = 'Pausar';
    }
    isPlaying = !isPlaying;
});

// Actualizar barra de progreso y tiempo actual
video.addEventListener('timeupdate', () => {
    seekBar.value = (video.currentTime / video.duration) * 100;
    currentTimeSpan.textContent = formatTime(video.currentTime);
});

// Barra de progreso
seekBar.addEventListener('input', () => {
    video.currentTime = (seekBar.value / 100) * video.duration;
});


// Botón de Silenciar/Activar Sonido
muteBtn.addEventListener('click', () => {
    video.muted = !video.muted;
    muteBtn.textContent = video.muted ? 'Activar Sonido' : 'Silenciar';
});


// Barra de Volumen
volumeBar.addEventListener('input', () => {
    video.volume = volumeBar.value / 100;
});


// Pantalla completa
fullScreenBtn.addEventListener('click', () => {
    if (video.requestFullscreen) {
        video.requestFullscreen();
    } else if (video.mozRequestFullScreen) { // Firefox
        video.mozRequestFullScreen();
    } else if (video.webkitRequestFullscreen) { // Chrome, Safari & Opera
        video.webkitRequestFullscreen();
    } else if (video.msRequestFullscreen) { // IE/Edge
        video.msRequestFullscreen();
    }
});

// Si el video termina, resetear estado de reproduccion
video.addEventListener('ended', () =>{
  isPlaying = false;
  playPauseBtn.textContent = 'Reproducir';
})
});

