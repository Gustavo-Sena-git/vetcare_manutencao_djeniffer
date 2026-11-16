
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
        throw new Exception('Tutor inválido.');
    }

    $s = $pdo->prepare("DELETE FROM tutores WHERE id = ?");
    $s->execute([$id]);

    $_SESSION['mensagem_sucesso'] = 'Tutor excluído com sucesso.';

} catch (PDOException $e) {
    $_SESSION['mensagem_erro'] = 'Não é possível excluir este tutor porque existem animais vinculados a ele.';

} catch (Exception $e) {
    $_SESSION['mensagem_erro'] = $e->getMessage();
}

header('Location: index.php');
exit;
