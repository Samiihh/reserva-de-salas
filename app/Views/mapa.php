<?php
/**
 * View do Mapa de Salas - Aula 2
 *
 * Este arquivo só exibe os dados. A variável $salas vem do MapaController,
 * já com badge_texto e badge_classe preenchidos (lógica feita no controller).
 *
 * Cada item de $salas tem: nome, capacidade, andar, recursos, status, badge_texto, badge_classe.
 */
// Se o controller não enviar $salas (ex.: erro ou rota diferente), usamos array vazio
// para evitar erro "undefined variable" ou "invalid argument" no foreach abaixo.
if (!isset($salas)) {
    $salas = [];
}
?>
<main class="container mapa-page">
  <header class="mapa-header">
    <h1 class="page-title">Mapa de Salas</h1>
    <p class="mapa-intro">Todas as salas disponíveis para reserva. Faça <a href="<?= APP_PATH ?>/auth/login.php">login</a> para reservar.</p>
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
          <?php
          // Percorre cada sala enviada pelo controller; $sala é um array com os dados de uma sala.
          // htmlspecialchars() (função nativa PHP): escapa < > " ' & para o navegador não interpretar como HTML/JS, evitando XSS. UTF-8 no header só define encoding; a segurança na saída é com esse escape.
          foreach ($salas as $sala):
          ?>
            <tr>
              <td><strong><?= htmlspecialchars($sala['nome']) ?></strong></td>
              <td><?= (int) $sala['capacidade'] ?> pessoas</td>
              <td><?= htmlspecialchars($sala['andar']) ?></td>
              <td><?= htmlspecialchars($sala['recursos'] ?? '') ?></td>
              <td><span class="badge <?= $sala['badge_classe'] ?? '' ?>"><?= htmlspecialchars($sala['badge_texto'] ?? '') ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
