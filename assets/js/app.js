(function () {
  function initAuth() {
    // O menu logado é controlado pelo PHP (sessão): o body já vem com class="user-logado" quando logado.
    // Não remover essa classe aqui, senão o menu some após o carregamento.
    if (sessionStorage.getItem('logado')) {
      document.body.classList.add('user-logado');
    }
  }

  function initModal() {
    var modal = document.getElementById('modal-nova-reserva');
    var abrirBtns = document.querySelectorAll('.abrir-modal-nova-reserva');
    var fecharOverlay = document.getElementById('fechar-modal-nova-reserva');
    var fecharBtn = document.getElementById('fechar-modal-nova-reserva-btn');
    var cancelarBtn = document.getElementById('cancelar-modal-nova-reserva');
    var form = document.getElementById('form-nova-reserva');

    if (!modal || !abrirBtns.length) return;

    function abrirModal() {
      modal.classList.add('modal--aberto');
      modal.setAttribute('aria-hidden', 'false');
      abrirBtns.forEach(function (btn) { btn.setAttribute('aria-expanded', 'true'); });
      document.body.style.overflow = 'hidden';
    }

    function fecharModal() {
      modal.classList.remove('modal--aberto');
      modal.setAttribute('aria-hidden', 'true');
      abrirBtns.forEach(function (btn) { btn.setAttribute('aria-expanded', 'false'); });
      document.body.style.overflow = '';
    }

    abrirBtns.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        abrirModal();
      });
    });

    fecharOverlay.addEventListener('click', fecharModal);
    if (fecharBtn) fecharBtn.addEventListener('click', fecharModal);
    if (cancelarBtn) cancelarBtn.addEventListener('click', fecharModal);

    modal.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') fecharModal();
    });

    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        fecharModal();
        form.reset();
      });
    }
  }

  function init() {
    initAuth();
    initModal();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
