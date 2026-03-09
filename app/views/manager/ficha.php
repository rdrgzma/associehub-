<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Inscrição - <?= htmlspecialchars($membro['nome']) ?></title>
    <style>
        /* Base styling for printable layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6; /* Gray background for screen */
            color: #111827;
        }

        .document-container {
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            margin: 20mm auto;
            background: white;
            padding: 20mm 15mm;
            box-sizing: border-box;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #374151;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #4b5563;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 8px 10px;
            margin-bottom: 15px;
            border-left: 4px solid #4f46e5; /* Indigo brand color */
        }

        .data-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .full-width {
            grid-column: span 2;
        }

        .data-item {
            margin-bottom: 5px;
        }

        .data-label {
            font-size: 12px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .data-value {
            font-size: 15px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 2px;
            min-height: 20px;
        }

        .footer-signature {
            margin-top: 50px;
            text-align: center;
        }

        .signature-line {
            width: 60%;
            margin: 0 auto;
            border-top: 1px solid #111827;
            padding-top: 5px;
        }

        .docs-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .docs-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        /* Print Specific Styles */
        @media print {
            body {
                background-color: white;
            }
            .document-container {
                margin: 0;
                padding: 10mm;
                width: 100%;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }

        /* Utility buttons for screen */
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .print-btn:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn no-print">🖨️ Imprimir Ficha</button>

    <div class="document-container">
        <div class="header">
            <h1>Ficha de Registro de Associado</h1>
            <p>AssocieHub - Sistema de Gestão de Associações</p>
            <p style="font-size: 12px; margin-top: 10px;">ID do Associado: <strong>#<?= str_pad($membro['id'], 6, '0', STR_PAD_LEFT) ?></strong> | Data de Registro: <strong><?= date('d/m/Y H:i', strtotime($membro['created_at'])) ?></strong></p>
        </div>

        <div class="section">
            <div class="section-title">DADOS PESSOAIS E IDENTIFICAÇÃO</div>
            <div class="data-grid">
                <div class="data-item full-width">
                    <div class="data-label">Nome Completo</div>
                    <div class="data-value"><?= htmlspecialchars($membro['nome']) ?></div>
                </div>
                
                <div class="data-item">
                    <div class="data-label">Nacionalidade</div>
                    <div class="data-value"><?= htmlspecialchars($membro['nacionalidade'] ?? '---') ?></div>
                </div>

                <div class="data-item">
                    <div class="data-label">Naturalidade</div>
                    <div class="data-value"><?= htmlspecialchars($membro['naturalidade'] ?? '---') ?></div>
                </div>

                <div class="data-item">
                    <div class="data-label">Data de Nascimento</div>
                    <div class="data-value"><?= $membro['data_nascimento'] ? date('d/m/Y', strtotime($membro['data_nascimento'])) : '---' ?></div>
                </div>

                <div class="data-item">
                    <div class="data-label">Idade</div>
                    <div class="data-value"><?= $membro['idade'] ?? '---' ?> anos</div>
                </div>

                <div class="data-item">
                    <div class="data-label">CPF</div>
                    <div class="data-value"><?= htmlspecialchars($membro['cpf']) ?></div>
                </div>

                <div class="data-item">
                    <div class="data-label">RG / Órgão Emissor</div>
                    <div class="data-value"><?= htmlspecialchars($membro['rg'] ?? '---') ?> / <?= htmlspecialchars($membro['rg_orgao_emissor'] ?? '---') ?></div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">ESTRUTURA FAMILIAR E ESTADO CIVIL</div>
            <div class="data-grid">
                <div class="data-item">
                    <div class="data-label">Filiação (1)</div>
                    <div class="data-value"><?= htmlspecialchars($membro['filiacao_1_nome'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">CPF Filiação (1)</div>
                    <div class="data-value"><?= htmlspecialchars($membro['filiacao_1_cpf'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">Filiação (2)</div>
                    <div class="data-value"><?= htmlspecialchars($membro['filiacao_2_nome'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">CPF Filiação (2)</div>
                    <div class="data-value"><?= htmlspecialchars($membro['filiacao_2_cpf'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">Estado Civil</div>
                    <div class="data-value"><?= htmlspecialchars($membro['estado_civil'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">Regime de Bens</div>
                    <div class="data-value"><?= htmlspecialchars($membro['forma_comunhao'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">Nome do Cônjuge</div>
                    <div class="data-value"><?= htmlspecialchars($membro['conjuge_nome'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">CPF do Cônjuge</div>
                    <div class="data-value"><?= htmlspecialchars($membro['conjuge_cpf'] ?? '---') ?></div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">DADOS PROFISSIONAIS</div>
            <div class="data-grid">
                <div class="data-item">
                    <div class="data-label">Profissão (Principal)</div>
                    <div class="data-value"><?= htmlspecialchars($membro['profissao_1'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">Registro / Órgão</div>
                    <div class="data-value"><?= htmlspecialchars($membro['profissao_1_registro'] ?? '') ?> <?= $membro['profissao_1_orgao'] ? '(' . htmlspecialchars($membro['profissao_1_orgao']) . ')' : '' ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">Profissão (Secundária)</div>
                    <div class="data-value"><?= htmlspecialchars($membro['profissao_2'] ?? '---') ?></div>
                </div>
                <div class="data-item">
                    <div class="data-label">Registro / Órgão</div>
                    <div class="data-value"><?= htmlspecialchars($membro['profissao_2_registro'] ?? '') ?> <?= $membro['profissao_2_orgao'] ? '(' . htmlspecialchars($membro['profissao_2_orgao']) . ')' : '' ?></div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">ENDEREÇO E CONTATO</div>
            <div class="data-grid">
                <div class="data-item full-width">
                    <div class="data-label">Logradouro / Número / Complemento</div>
                    <div class="data-value">
                        <?= htmlspecialchars($membro['endereco'] ?? '') ?>, <?= htmlspecialchars($membro['numero'] ?? 'S/N') ?> 
                        <?= $membro['complemento'] ? ' - ' . htmlspecialchars($membro['complemento']) : '' ?>
                    </div>
                </div>

                <div class="data-item">
                    <div class="data-label">Bairro</div>
                    <div class="data-value"><?= htmlspecialchars($membro['bairro'] ?? '---') ?></div>
                </div>

                <div class="data-item">
                    <div class="data-label">CEP</div>
                    <div class="data-value"><?= htmlspecialchars($membro['cep'] ?? '---') ?></div>
                </div>

                <div class="data-item">
                    <div class="data-label">Município / Estado</div>
                    <div class="data-value"><?= htmlspecialchars($membro['cidade']) ?> - <?= htmlspecialchars($membro['estado']) ?></div>
                </div>

                <div class="data-item">
                    <div class="data-label">Telefone / E-mail</div>
                    <div class="data-value"><?= htmlspecialchars($membro['telefone']) ?> | <?= htmlspecialchars($membro['email']) ?></div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">STATUS E VALIDADE DA DOCUMENTAÇÃO</div>
            <ul class="docs-list">
                <?php
                $documentos = [
                    'doc_identidade' => 'Cópia do Documento de Identificação',
                    'doc_quitacao_eleitoral' => 'Comprovante de Quitação Eleitoral',
                    'doc_fiscal_federal' => 'Certidão de Situação Fiscal Federal',
                    'doc_fiscal_estadual' => 'Certidão de Situação Fiscal Estadual',
                    'doc_fiscal_municipal' => 'Certidão de Situação Fiscal Municipal',
                    'doc_situacao_cpf' => 'Comprovante de Situação Cadastral no CPF'
                ];
                
                foreach($documentos as $key => $label): 
                    $is_valid = $membro[$key.'_status'] ?? 0;
                    $validade = !empty($membro[$key.'_validade']) ? date('d/m/Y', strtotime($membro[$key.'_validade'])) : '-';
                    $status_icon = $is_valid ? "✔️" : "❌";
                ?>
                    <li style="align-items: center;">
                        <div style="flex: 1; font-weight: 500;">
                            <span style="display: inline-block; width: 20px; font-size: 16px;"><?= $status_icon ?></span> <?= $label ?>
                        </div>
                        <div style="text-align: right; font-size: 13px; color: #4b5563;">
                            Validade: <strong style="color: #111827;"><?= $validade ?></strong>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer-signature">
            <p style="text-align: left; margin-bottom: 40px; font-size: 14px;">Declaro que as informações constantes nesta ficha são verdadeiras e assumo inteira responsabilidade pelas mesmas.</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px; font-weight: bold;"><?= htmlspecialchars($membro['nome']) ?></p>
            <p style="font-size: 12px; color: #6b7280;">Assinatura do Associado</p>
            <p style="font-size: 12px; margin-top: 20px; color: #6b7280;">Documento gerado em <?= date('d/m/Y') ?></p>
        </div>
    </div>

    <!-- Auto trigger print dialog when opened -->
    <script>
        window.addEventListener('load', function() {
            // Optional: automatically prompt print dialog on load
            // window.print();
        });
    </script>
</body>
</html>
