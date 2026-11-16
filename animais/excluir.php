
<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/auth.php';

exigirLogin();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = (int)($_GET['id'] ?? 0);

try {
    if ($id <= 0) {
        throw new Exception('Animal inválido.');
    }

    $s = $pdo->prepare("DELETE FROM animais WHERE id = ?");
    $s->execute([$id]);

    if ($s->rowCount() > 0) {
        $_SESSION['mensagem_sucesso'] = 'Animal excluído com sucesso.';
    } else {
        $_SESSION['mensagem_erro'] = 'Animal não encontrado.';
    }

} catch (PDOException $e) {
    $_SESSION['mensagem_erro'] = 'Não foi possível excluir o animal porque existem consultas vinculadas a ele.';

} catch (Exception $e) {
    $_SESSION['mensagem_erro'] = $e->getMessage();
}

header('Location: index.php');
exit;

