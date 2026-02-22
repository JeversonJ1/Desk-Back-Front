<div>Sou o create</div>
<form action="/backend/perfil/salvar" method="post">
    <label for="telefone_perfil">Telefone:</label>
    <input type="tel" id="telefone_perfil" name="telefone_perfil" required>
    <br>

    <label for="endereco_perfil">Endereço:</label>
    <input type="text" id="endereco_perfil" name="endereco_perfil" required>
    <br>

    <label for="data_cadastro">Data de Cadastro:</label>
    <input type="date" id="data_cadastro" name="data_cadastro" required>
    <br>

    <button type="submit">Salvar</button>
</form>
