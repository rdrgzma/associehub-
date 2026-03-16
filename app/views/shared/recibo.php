<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Pagamento - AssocieHub</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: 'Inter', sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 0; }
        .receipt-container { max-width: 800px; margin: 0 auto; border: 2px solid #eee; padding: 40px; border-radius: 8px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #indigo-600; padding-bottom: 20px; margin-bottom: 30px; }
        .logo-box { background: #4f46e5; color: white; padding: 10px 20px; border-radius: 4px; font-weight: 800; font-size: 20px; }
        .receipt-title { text-align: right; }
        .receipt-title h1 { margin: 0; color: #4f46e5; text-transform: uppercase; font-size: 24px; }
        .receipt-number { color: #666; font-size: 14px; margin-top: 5px; }
        
        .info-grid { display: grid; grid-template-cols: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .info-section h3 { font-size: 12px; font-weight: 700; color: #999; text-transform: uppercase; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .info-content p { margin: 5px 0; font-size: 14px; }
        .info-content strong { color: #111; }

        .payment-summary { background: #f9fafb; padding: 30px; border-radius: 12px; margin-bottom: 40px; }
        .payment-summary table { width: 100%; border-collapse: collapse; }
        .payment-summary th { text-align: left; font-size: 12px; color: #666; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        .payment-summary td { padding: 15px 0; border-bottom: 1px solid #eee; font-size: 15px; }
        .total-row { font-size: 20px; font-weight: 800; color: #4f46e5; }

        .footer { margin-top: 60px; text-align: center; font-size: 12px; color: #999; }
        .signature-box { margin-top: 50px; display: flex; flex-direction: column; align-items: center; }
        .signature-line { width: 300px; border-top: 1px solid #000; margin-bottom: 10px; }
        
        @media print {
            .no-print { display: none; }
            .receipt-container { border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; padding: 20px; background: #f0f0f0; margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">🖨️ Imprimir Recibo</button>
    </div>

    <div class="receipt-container">
        <div class="header">
            <div class="logo-box">AssocieHub</div>
            <div class="receipt-title">
                <h1>Recibo</h1>
                <div class="receipt-number">Nº #<?= str_pad($pagamento['id'], 6, '0', STR_PAD_LEFT) ?></div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-section">
                <h3>Emitente</h3>
                <div class="info-content">
                    <p><strong><?= htmlspecialchars($associacao['nome'] ?? '') ?></strong></p>
                    <p>CNPJ: <?= htmlspecialchars($associacao['cnpj'] ?? '') ?></p>
                    <p><?= htmlspecialchars($associacao['cidade'] ?? '') ?><?php if(!empty($associacao['cidade']) && !empty($associacao['estado'])) echo ' - '; ?><?= htmlspecialchars($associacao['estado'] ?? '') ?></p>
                </div>
            </div>
            <div class="info-section">
                <h3>Pagador</h3>
                <div class="info-content">
                    <p><strong><?= htmlspecialchars($pagamento['nome'] ?? '') ?></strong></p>
                    <p>Documento: <?= htmlspecialchars($pagamento['documento'] ?? '') ?></p>
                    <p><?= htmlspecialchars($pagamento['email'] ?? '') ?></p>
                </div>
            </div>
        </div>

        <div class="payment-summary">
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Data do Pagamento</th>
                        <th style="text-align: right;">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Contribuição / Taxa de Associado<br>
                            <small style="color: #666; font-style: italic;">Referente à cobrança de <?= ucfirst('taxa de contribuíção associativa') ?></small>
                        </td>
                        <td><?= date('d/m/Y', strtotime($pagamento['data_pagamento'])) ?></td>
                        <td style="text-align: right;" class="total-row">R$ <?= number_format($pagamento['valor'], 2, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p style="font-size: 14px; text-align: justify;">
            Declaramos para os devidos fins que recebemos de <strong><?= htmlspecialchars($pagamento['nome'] ?? '') ?></strong> o valor de <strong>R$ <?= number_format($pagamento['valor'] ?? 0, 2, ',', '.') ?></strong> correspondente à taxa de contribuição associativa, quitando plenamente a referida obrigação financeira nesta data.
        </p>

        <div class="signature-box">
            <br><br>
            <div class="signature-line"></div>
            <p><strong><?= htmlspecialchars($associacao['nome'] ?? '') ?></strong></p>
            <p style="font-size: 10px;">Assinatura do Responsável / Carimbo</p>
        </div>

        <div class="footer">
            <p>Este recibo é um documento gerado automaticamente por AssocieHub em <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>
</body>
</html>
