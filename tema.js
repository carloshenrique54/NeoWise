window.addEventListener('load', () => {
  const page = window.location.pathname.split("/").pop();
  const home = document.getElementById('home');
  const cursos = document.getElementById('cursos');
  const sobre = document.getElementById('sobre');
  const contato = document.getElementById('contato');
  const forum = document.getElementById('forum')
    
    if (page === 'index.php') {
      home.classList.add('active');
    }
    else if (page === 'cursos.php') {
      cursos.classList.add('active');
    }
    else if (page === 'sobre.php') {
      sobre.classList.add('active');
    }
    else if (page === 'contato.php') {
      contato.classList.add('active');
    }
    else if (page == 'forum.php'){
      forum.classList.add('active')
    }
});

const button = document.getElementById('toggle');

function atualizarTextoBotao() {
  if (document.body.classList.contains('light')) {
    button.textContent = 'Modo Escuro';
  } else {
    button.textContent = 'Modo Claro';
  }
}

button.addEventListener('click', () => {
  document.body.classList.toggle('light');
  
  const tema = document.body.classList.contains('light') ? 'light' : 'dark';
  localStorage.setItem('tema', JSON.stringify(tema));

  atualizarTextoBotao();
});

window.onload = () => {
  const tema = JSON.parse(localStorage.getItem('tema'));
  if (tema === 'light') {
    document.body.classList.toggle('light');
    console.log("Tema atual:", tema);
  } else {
    document.body.classList.remove('light');
    console.log("Tema atual:", tema);
  }
  atualizarTextoBotao();
};