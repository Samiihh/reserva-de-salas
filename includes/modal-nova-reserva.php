<!-- Modal Nova Reserva -->
<div id="modal-nova-reserva" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-nova-reserva-titulo" aria-hidden="true">
  <div class="modal__overlay" id="fechar-modal-nova-reserva"></div>
  <div class="modal__box">
    <div class="modal__header">
      <h2 id="modal-nova-reserva-titulo" class="modal__titulo">Nova Reserva</h2>
      <button type="button" class="modal__fechar" id="fechar-modal-nova-reserva-btn" aria-label="Fechar">×</button>
    </div>
    <form class="modal__body" id="form-nova-reserva">
      <div class="form-group">
        <label for="nova-reserva-sala">Sala</label>
        <select id="nova-reserva-sala" name="sala" required>
          <option value="">Selecione a sala</option>
          <option value="101">Sala 101</option>
          <option value="102">Sala 102</option>
          <option value="201">Sala 201</option>
          <option value="202">Sala 202</option>
          <option value="203">Sala 203</option>
          <option value="auditorio">Auditório A</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="nova-reserva-data">Data</label>
          <input type="date" id="nova-reserva-data" name="data" required>
        </div>
        <div class="form-group">
          <label for="nova-reserva-hora-inicio">Horário início</label>
          <input type="time" id="nova-reserva-hora-inicio" name="hora_inicio" required>
        </div>
        <div class="form-group">
          <label for="nova-reserva-hora-fim">Horário fim</label>
          <input type="time" id="nova-reserva-hora-fim" name="hora_fim" required>
        </div>
      </div>
      <div class="form-group">
        <label for="nova-reserva-obs">Observações (opcional)</label>
        <textarea id="nova-reserva-obs" name="observacoes" rows="2" placeholder="Ex: reunião de planejamento"></textarea>
      </div>
    </form>
    <div class="modal__footer">
      <button type="button" class="btn btn-outline modal__cancelar" id="cancelar-modal-nova-reserva">Cancelar</button>
      <button type="submit" form="form-nova-reserva" class="btn btn-primary">Reservar</button>
    </div>
  </div>
</div>

<script src="./assets/js/app.js"></script>
