
<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/auth.php';

exigirLogin();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$titulo = 'Tutores';
$busca = trim($_GET['busca'] ?? '');

$mensagemErro = $_SESSION['mensagem_erro'] ?? '';
$mensagemSucesso = $_SESSION['mensagem_sucesso'] ?? '';

unset($_SESSION['mensagem_erro']);
unset($_SESSION['mensagem_sucesso']);

$sql = "SELECT * FROM tutores WHERE nome LIKE ?";
$param = ['%'.$busca.'%'];

if ($busca === '') {
    $sql = "SELECT * FROM tutores";
    $param = [];
}

$stmt = $pdo->prepare($sql." ORDER BY nome");
$stmt->execute($param);
$rows = $stmt->fetchAll();

include __DIR__.'/../includes/header.php';
?>

<h1>Tutores</h1>
<p class="muted">Cadastro e consulta de responsáveis pelos animais.</p>

<?php if ($mensagemErro): ?>
    <div class="alert">
        <?=htmlspecialchars($mensagemErro)?>
    </div>
<?php endif; ?>

<?php if ($mensagemSucesso): ?>
    <div class="alert">
        <?=htmlspecialchars($mensagemSucesso)?>
    </div>
<?php endif; ?>

<div class="actions">
    <a class="btn" href="form.php">+ Novo tutor</a>
</div>

<section class="panel">
    <form method="get">
        <div class="field">
            <label>Buscar tutor</label>
            <input name="busca" placeholder="Digite o nome" value="<?=htmlspecialchars($busca)?>">
        </div>
        <button>Buscar</button>
    </form>
</section>

<div class="panel table-wrap">
    <table>
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>

        <?php foreach($rows as $r): ?>
        <tr>
            <td><?=htmlspecialchars($r['nome'])?></td>
            <td><?=htmlspecialchars($r['telefone'])?></td>
            <td><?=htmlspecialchars($r['email'])?></td>
            <td><?=$r['ativo']?'Ativo':'Inativo'?></td>
            <td>
                <a href="form.php?id=<?=$r['id']?>">Editar</a> |
                <a href="excluir.php?id=<?=$r['id']?>" onclick="return confirm('Deseja realmente excluir este tutor?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include __DIR__.'/../includes/footer.php'; ?>
