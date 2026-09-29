<?php
/**
 * Script de processamento de Leads da PrimeSys Tecnologia com PHPMailer
 * PHP Version 8.0+
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclusão manual das classes do PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. SANITIZAÇÃO E VALIDAÇÃO DOS DADOS
    $nome     = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
    $negocio  = filter_input(INPUT_POST, 'negocio', FILTER_SANITIZE_SPECIAL_CHARS);
    $area     = filter_input(INPUT_POST, 'area', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validação básica de campos obrigatórios
    if (!$nome || !$telefone || !$negocio || !$area) {
        die("Erro: Todos os campos do formulário são obrigatórios.");
    }

    $mail = new PHPMailer(true);

    try {
        /* ==========================================================================
           CONFIGURAÇÕES DO SERVIDOR SMTP
           ========================================================================== */
        $mail->SMTPDebug = 0;                                       // 0 em produção, 2 para testes
        $mail->isSMTP();                                            // Envio via SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Servidor SMTP
        $mail->SMTPAuth   = true;                                   // Habilita autenticação
        $mail->Username   = 'primesystecnologia01@gmail.com';       // Seu e-mail SMTP
        $mail->Password   = 'qdyb orfi qguc bmbv';                  // Senha de aplicativo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // TLS
        $mail->Port       = 587;                                    // Porta TLS
        $mail->CharSet    = 'UTF-8';

        // Destinatários e Remetente
        $mail->setFrom('jorgefds1995@gmail.com', 'Contato - PrimeSys');
        $mail->addAddress('jorgefds1995@gmail.com');                // E-mail que receberá a mensagem
        
        // Conteúdo do E-mail
        $mail->isHTML(true);
        $mail->Subject = "Novo Contato via Site: " . htmlspecialchars($nome);
        
        // Corpo do E-mail formatado
        $mailBody = "
        <h2>Novo Contato Recebido pelo Site - PrimeSys</h2>
        <p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>
        <p><strong>Telefone / WhatsApp:</strong> " . htmlspecialchars($telefone) . "</p>
        <p><strong>Nome do Negócio:</strong> " . htmlspecialchars($negocio) . "</p>
        <p><strong>Área de Atuação:</strong> " . htmlspecialchars($area) . "</p>
        <br>
        <hr>
        <p><small>Este e-mail foi enviado automaticamente através do formulário de contato do site PrimeSys Tecnologia.</small></p>
        ";

        $mail->Body = $mailBody;

        // Dispara o e-mail
        $mail->send();

        // Exibe a tela de sucesso
        renderSucesso();

    } catch (Exception $e) {
        error_log("Erro no disparo do PHPMailer: {$mail->ErrorInfo}");
        echo "Desculpe, ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente ou entre em contato via WhatsApp.";
    }
} else {
    // Redireciona de volta caso acessem o script diretamente
    header("Location: ../index.html");
    exit();
}

/**
 * Renderização da página de Sucesso personalizada com as cores da PrimeSys
 */
function renderSucesso() {
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagem Enviada | PrimeSys Tecnologia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            light: '#f4efec',
                            slate: '#445476',
                            primary: '#3f3bb3',
                            accent: '#544cdf',
                            dark: '#252d40',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-light text-brand-dark font-sans min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-2xl p-8 text-center shadow-xl border border-brand-slate/10">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 fill-none stroke-current stroke-2 stroke-linecap-round stroke-linejoin-round" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-brand-dark mb-3">Mensagem Recebida!</h1>
        <p class="text-brand-slate mb-8 leading-relaxed">Obrigado pelo contato. Recebemos seus dados e nossa equipe retornará em breve.</p>
        <a href="../index.html" class="inline-block w-full bg-brand-primary hover:bg-brand-accent text-white font-semibold py-3.5 rounded-lg transition shadow-md">
            Voltar para o site
        </a>
    </div>
</body>
</html>
<?php
    exit();
}
?>