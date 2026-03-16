# AssocieHub ⚡

Plataforma de gestão completa para associações, focada na organização de membros, controle financeiro e conformidade documental.

## 🚀 Funcionalidades

### Gestão de Associações
- **Portal do Administrador (SuperAdmin):** Controle total sobre as associações cadastradas, aprovação de novas solicitações e visão global do sistema.
- **Portal do Manager (Gestor da Associação):** Painel exclusivo para que cada associação gerencie seus próprios membros e finanças.
- **Cadastro de Novas Associações:** Link público para solicitação de registro com envio de comprovante de pagamento de taxa de adesão.

### Gestão de Membros (Associados)
- **Formulário de Inscrição Inteligente:** Links exclusivos de convite gerados para cada associação.
- **Dossiê Documental:** Upload e conferência de múltiplos documentos (Identidade, CPF, Quitação Eleitoral, Certidões Fiscais, ficha assinada).
- **Suporte a Cônjuges:** Coleta completa de dados e documentos para cônjuges, com campos dedicados em conformidade com estatutos.
- **Impressão de Fichas/Nominata:** Geração de arquivos formatados para impressão de fichas cadastrais e listas de presença (Nominata).

### Controle Financeiro
- **Pagamentos PIX:** Configuração global de chaves PIX e instruções de pagamento.
- **Fluxo de Comprovantes:** Membros anexam comprovantes que são validados pelos gestores.
- **Relatórios:** Geração de relatórios financeiros em tempo real (HTML e CLI) com status de valores pagos e pendentes.

---

## 🛠️ Tecnologias

- **Linguagem:** PHP 8.x (Vanilla/MVC)
- **Banco de Dados:** SQLite (leve e portátil)
- **Estilização:** Tailwind CSS (Modern UI)
- **Arquitetura:** MVC simplificado (Router, Controller, Model)

---

## 📂 Estrutura do Projeto

```text
├── app/
│   ├── controllers/    # Lógica de controle do sistema
│   ├── core/           # Motor do framework (Router, MVC Base)
│   ├── models/         # Interação com o banco de dados
│   └── views/          # Templates HTML/PHP (UI)
├── config/             # Configurações de banco e ambiente
├── database/           # Banco SQLite e scripts SQL de schema
├── docs/               # Documentações técnicas e guias de refatoração
├── maintenance/        # Scripts de utilidade e emergência (CLI)
├── public/             # Raiz pública (index.php, CSS, JS, Uploads)
├── routes/             # Definição de rotas do sistema
└── reset_db.php        # Script de emergência para reset (deve ser movido/protegido)
```

---

## 🔧 Instalação e Configuração

1. **Requisitos:**
   - PHP 8.1 ou superior com extensão `pdo_sqlite` habilitada.
   - Servidor Apache ou Nginx.

2. **Configuração de Rotas:**
   Certifique-se de que o `.htaccess` na raiz está redirecionando corretamente para a pasta `public/`.

3. **Sincronização do Banco:**
   Ao instalar o projeto, execute o script de sincronização para garantir que todas as colunas e tabelas estejam criadas:
   ```bash
   php maintenance/migrations/sync.php
   ```

4. **Timezone:**
   O projeto está configurado para `America/Sao_Paulo` em `public/index.php`.

---

## 🛡️ Manutenção e Emergência

A pasta `maintenance/` contém ferramentas críticas protegidas por `.htaccess`:

- **Emergência Admin:** `php maintenance/create_admin.php "Nome" "email@exemplo.com" "senha"`
- **Sincronizar Banco:** `php maintenance/migrations/sync.php`
- **Relatório CLI:** `php maintenance/report_registrations.php`
- **Gerar Relatório Financeiro HTML:** `php maintenance/generate_financial_html.php`

---

## 📄 Licença

Projeto desenvolvido para gestão de associações corporativas e civis. Todos os direitos reservados.
