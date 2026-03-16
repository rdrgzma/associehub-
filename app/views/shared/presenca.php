<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Presença - <?= htmlspecialchars($associacao['nome']) ?></title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h1 { margin: 0; font-size: 18pt; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10pt; color: #666; }
        
        .nominata-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .nominata-section h2 {
            font-size: 12pt;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .nominata-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .nominata-item {
            font-size: 9pt;
            padding: 5px;
            background: #f9f9f9;
            border: 1px solid #eee;
        }
        .token-label { font-weight: bold; color: #555; display: block; margin-bottom: 2px; }

        .membros-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .membros-table th, .membros-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }
        .membros-table th {
            background-color: #eee;
            text-transform: uppercase;
            font-size: 9pt;
        }
        .col-nome { width: 40%; }
        .col-cpf { width: 15%; }
        .col-assinatura { width: 45%; }
        
        .signature-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            width: 90%;
            height: 14pt;
        }

        .no-print-controls {
            background: #fdf2f2;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        @media print {
            .no-print-controls { display: none; }
            .header { border-bottom-color: #000; }
        }
        
        .footer {
            margin-top: 30px;
            font-size: 8pt;
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="no-print-controls">
        <div style="font-size: 9pt; color: #b91c1c; font-weight: bold;">
            MODO DE IMPRESSÃO - Use Ctrl+P para imprimir ou salvar em PDF
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" style="background: #1e40af; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">ImprimirAgora</button>
            <button onclick="window.close()" style="background: #ef4444; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">Fechar</button>
        </div>
    </div>

    <div class="header">
        <h1>Lista de Presença</h1>
        <p><?= htmlspecialchars($associacao['nome']) ?> &bull; CNPJ: <?= htmlspecialchars($associacao['cnpj']) ?></p>
        <p>Data: ____/____/________ &bull; Finalidade: ________________________________________________</p>
    </div>

    <?php if ($show_nominata && !empty($nominata)): ?>
    <div class="nominata-section">
        <h2>Diretoria Resumo</h2>
        <div class="nominata-grid">
            <?php foreach ($nominata as $pos): ?>
            <div class="nominata-item">
                <span class="token-label"><?= htmlspecialchars($pos['cargo']) ?>:</span>
                <?= $pos['associado_nome'] ? htmlspecialchars($pos['associado_nome']) : '<em>Não atribuído</em>' ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <table class="membros-table">
        <thead>
            <tr>
                <th class="col-nome">Nome do Associado</th>
                <th class="col-cpf">CPF</th>
                <th class="col-assinatura">Assinatura / Presença</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($membros as $membro): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($membro['nome']) ?>
                    <?php if(!empty($membro['cargo_nominata'])): ?>
                        <div style="font-size: 8pt; font-style: italic; color: #666; margin-top: 2px;">
                            Cargo: <?= htmlspecialchars($membro['cargo_nominata']) ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($membro['cpf']) ?></td>
                <td><div class="signature-line"></div></td>
            </tr>
            <?php endforeach; ?>
            
            <!-- Extra blank rows for walk-ins if needed -->
            <?php for ($i = 0; $i < 5; $i++): ?>
            <tr>
                <td style="height: 25px;"></td>
                <td></td>
                <td><div class="signature-line"></div></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <div class="footer">
        Gerado pelo sistema AssocieHub em <?= date('d/m/Y H:i') ?> &bull; Página 1
    </div>

    <script>
        // Auto-print if param is set
        if (window.location.search.includes('autoprint=true')) {
            window.print();
        }
    </script>
</body>
</html>
