<?php
/**
 * View Minhas Reservas - Aula 2
 *
 * Só exibe os dados. A variável $reservas vem do MinhasReservasController,
 * com sala_nome, data_formatada, horario_formatado, badge_texto e badge_classe.
 */
// Se o controller não enviar $reservas, usamos array vazio para não dar erro no foreach.
if (!isset($reservas)) {
    $reservas = [];
}
?>
<main class="container minhas-reservas-page">
  <header class="minhas-reservas-header">
    <h1 class="page-title">Minhas Reservas</h1>
    <button type="button" class="btn btn-primary btn-nova-reserva abrir-modal-nova-reserva">+ Nova Reserva</button>
  </header>

  <section class="minhas-reservas-grid">
    <div class="card stat-card">
      <p class="stat-label">Minhas reservas</p>
      <p class="stat-value"><?= count($reservas) ?></p>
      <p class="stat-hint">no total</p>
    </div>
    <div class="card stat-card">
      <p class="stat-label">Salas ativas</p>
      <p class="stat-value">5</p>
      <p class="stat-hint">disponíveis para reserva</p>
    </div>
    <div class="card stat-card">
      <p class="stat-label">Próxima reserva</p>
      <p class="stat-value stat-value--text"><?= !empty($reservas) ? htmlspecialchars($reservas[0]['sala_nome']) : '—' ?></p>
      <p class="stat-hint"><?= !empty($reservas) ? $reservas[0]['data_formatada'] . ', ' . $reservas[0]['horario_formatado'] : 'Nenhuma' ?></p>
    </div>
  </section>

  <section class="minhas-reservas-table-section">
    <div class="table-wrapper card">
      <table class="minhas-reservas-table">
        <thead>
          <tr>
            <th>Sala</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          // htmlspecialchars() (PHP): escapa < > " ' & para evitar XSS. Cada $reserva tem os dados já preparados pelo controller.
          foreach ($reservas as $reserva):
          ?>
            <tr>
              <td><?= htmlspecialchars($reserva['sala_nome']) ?></td>
              <td><?= htmlspecialchars($reserva['data_formatada']) ?></td>
              <td><?= htmlspecialchars($reserva['horario_formatado']) ?></td>
              <td><span class="badge <?= $reserva['badge_classe'] ?? '' ?>"><?= htmlspecialchars($reserva['badge_texto'] ?? '') ?></span></td>
              <td><a href="#" class="btn-link">Detalhes</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
