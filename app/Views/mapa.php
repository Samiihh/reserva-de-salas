<?php $logado = isset($_SESSION['usuario_id']); ?>
<main class="container mapa-page">
  <header class="mapa-header">
    <h1 class="page-title">Mapa de Salas</h1>
    <p class="mapa-intro">
      Todas as salas disponíveis para reserva.
      <?php if (!$logado): ?>
        Faça <a href="<?= (BASE_URL === '' ? '' : BASE_URL . '/') ?>index.php?c=auth&a=login">login</a> para reservar.
      <?php endif; ?>
    </p>
  </header>

  <section class="mapa-table-section">
    <div class="table-wrapper card">
      <table class="mapa-table">
        <thead>
          <tr>
            <th>Sala</th>
            <th>Capacidade</th>
            <th>Andar</th>
            <th>Recursos</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Sala 101</strong></td>
            <td>10 pessoas</td>
            <td>1º andar</td>
            <td>Projetor, Quadro branco</td>
            <td><span class="badge badge-disponivel">Disponível</span></td>
          </tr>
          <tr>
            <td><strong>Sala 102</strong></td>
            <td>8 pessoas</td>
            <td>1º andar</td>
            <td>TV, Videoconferência</td>
            <td><span class="badge badge-disponivel">Disponível</span></td>
          </tr>
          <tr>
            <td><strong>Sala 201</strong></td>
            <td>20 pessoas</td>
            <td>2º andar</td>
            <td>Projetor, Quadro, Ar condicionado</td>
            <td><span class="badge badge-em-uso">Em uso</span></td>
          </tr>
          <tr>
            <td><strong>Sala 202</strong></td>
            <td>12 pessoas</td>
            <td>2º andar</td>
            <td>Quadro branco</td>
            <td><span class="badge badge-disponivel">Disponível</span></td>
          </tr>
          <tr>
            <td><strong>Sala 203</strong></td>
            <td>6 pessoas</td>
            <td>2º andar</td>
            <td>Videoconferência</td>
            <td><span class="badge badge-disponivel">Disponível</span></td>
          </tr>
          <tr>
            <td><strong>Auditório A</strong></td>
            <td>50 pessoas</td>
            <td>Térreo</td>
            <td>Projetor, Som, Ar condicionado</td>
            <td><span class="badge badge-disponivel">Disponível</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
