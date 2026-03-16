# Registration Refactor Plan

## Overview
Refactor the member registration process to simplify document requirements. New members will only need to provide an Identity Document and either a Birth or Marriage certificate based on their marital status. Manager validation will be updated accordingly.

## Project Type
WEB (PHP/SQLite)

## Success Criteria
- [ ] Database schema updated with new document fields.
- [ ] Public registration form reflects simplified requirements.
- [ ] Dynamic certificate requirement logic works based on marital status.
- [ ] Manager and Admin interfaces support validating the new document.
- [ ] Registration flow is simplified and user-friendly.

## Tech Stack
- Backend: PHP
- Database: SQLite
- Frontend: Tailwind CSS, JavaScript (jQuery-like patterns)

## File Structure
- `migrate_new_docs.php`: Database migration [NEW]
- `app/models/Associado.php`: Model updates [MODIFY]
- `app/controllers/AssociadoController.php`: Public registration logic [MODIFY]
- `app/controllers/ManagerController.php`: Management logic [MODIFY]
- `app/controllers/AdminController.php`: Admin logic [MODIFY]
- `app/views/associado/formulario.php`: Public form [MODIFY]
- `app/views/manager/membro.php`: Validation UI [MODIFY]
- `app/views/manager/editar.php`: Edit form [MODIFY]

## Task Breakdown

### Phase 1: Foundation (Database & Model)
- **T1.1**: Create and run `migrate_new_docs.php`.
  - Agent: `database-architect`
  - Verify: Check `associados` schema for new columns.
- **T1.2**: Update `Associado` model.
  - Agent: `backend-specialist`
  - Verify: Test `create` and `findById` with new field.

### Phase 2: Registration Flow
- **T2.1**: Refactor `formulario.php` UI.
  - Agent: `frontend-specialist`
  - Verify: Check form rendering for different marital statuses.
- **T2.2**: Update `AssociadoController` registrar logic.
  - Agent: `backend-specialist`
  - Verify: Successful registration with new document fields.

### Phase 3: Management Interface
- **T3.1**: Update `ManagerController` and `AdminController`.
  - Agent: `backend-specialist`
  - Verify: Document status updates work for the new field.
- **T3.2**: Update `membro.php` and `editar.php` views.
  - Agent: `frontend-specialist`
  - Verify: New document visible and editable in internal views.

## Phase X: Verification
- [ ] Run security scan: `python .agent/skills/vulnerability-scanner/scripts/security_scan.py .`
- [ ] Run UX audit: `python .agent/skills/frontend-design/scripts/ux_audit.py .`
- [ ] Manual registration test (Single/Widowed).
- [ ] Manual registration test (Married/Union).
- [ ] Manual validation test by Manager.
