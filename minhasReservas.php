<?php include 'includes/header.php'; ?>

<main class="container minhas-reservas-page">
  <header class="minhas-reservas-header">
    <h1 class="page-title">Minhas Reservas</h1>
    <button type="button" class="btn btn-primary btn-nova-reserva abrir-modal-nova-reserva">+ Nova Reserva</button>
  </header>

  <section class="minhas-reservas-grid">
    <div class="card stat-card">
      <p class="stat-label">Minhas reservas</p>
      <p class="stat-value">12</p>
      <p class="stat-hint">últimos 30 dias</p>
    </div>
    <div class="card stat-card">
      <p class="stat-label">Salas ativas</p>
      <p class="stat-value">5</p>
      <p class="stat-hint">disponíveis para reserva</p>
    </div>
    <div class="card stat-card">
      <p class="stat-label">Próxima reserva</p>
      <p class="stat-value stat-value--text">Sala 101</p>
      <p class="stat-hint">Hoje, 14:00–16:00</p>
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
          <tr>
            <td>Sala 101</td>
            <td>24/02/2025</td>
            <td>14:00 – 16:00</td>
            <td><span class="badge badge-confirmada">Confirmada</span></td>
            <td><a href="#" class="btn-link">Detalhes</a></td>
          </tr>
          <tr>
            <td>Sala 203</td>
            <td>25/02/2025</td>
            <td>09:00 – 11:00</td>
            <td><span class="badge badge-pendente">Pendente</span></td>
            <td><a href="#" class="btn-link">Detalhes</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php include 'includes/footer.php'; ?>