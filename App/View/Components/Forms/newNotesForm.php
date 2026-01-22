<?php namespace App\View; ?>

<?php

$courses = $courses ?? [];
$action = $action ?? '/note/create';

// Prefill: prefer explicit *_value keys passed by the caller to avoid collision with page-level vars
$titleValue = $title_value ?? $title ?? '';
$descriptionValue = $description_value ?? $description ?? '';
$universityValue = $university_value ?? $university ?? '';
$noteTypeValue = $note_type_value ?? $note_type ?? '';
$formatValue = $format_value ?? $format ?? '';
$selectedCourseId = $selected_course_id ?? ($selectedCourseId ?? null);
$visibilityValue = $visibility_value ?? $visibility ?? 'public';
$isEdit = !empty($is_edit) || (is_string($action) && preg_match('#/update/?$#', $action));

?>

<section class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="<?= htmlspecialchars($action) ?>" enctype="multipart/form-data">
                
                <!-- Titolo -->
                <div class="mb-3">
                    <label for="title" class="form-label">Titolo *</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($titleValue) ?>" required aria-required="true"
                           aria-describedby="titleHelp">
                    <small id="titleHelp" class="form-text text-muted">
                        Inserisci un titolo descrittivo
                    </small>
                </div>

                <!-- Corso -->
                <div class="mb-3">
                    <label for="course" class="form-label">Corso *</label>
                    <select class="form-select" id="course" name="course" required aria-required="true"
                            aria-describedby="courseHelp">
                        <option value="">Seleziona un corso</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course['id']) ?>" <?= $selectedCourseId == $course['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($course['name']) ?>
                            </option>
                        <?php endforeach; ?>
                            <option value="__new" <?= ($selectedCourseId === '__new') ? 'selected' : '' ?>>Aggiungi nuovo corso</option>
                    </select>
                    <small id="courseHelp" class="form-text text-muted">
                        Scegli il corso di riferimento
                    </small>
                    <!-- Nuovo corso (hidden until selected) -->
                    <div id="newCourseWrapper" class="mt-2" style="display:<?= ($selectedCourseId === '__new' ? 'block' : 'none') ?>;">
                        <label for="new_course" class="form-label">Nuovo corso</label>
                        <input type="text" class="form-control" id="new_course" name="new_course"
                               placeholder="Inserisci il nome del nuovo corso">
                        <small id="newCourseHelp" class="form-text text-muted">
                            Inserisci il nome del corso se vuoi crearne uno nuovo
                        </small>
                    </div>
                </div>

                <!-- Descrizione -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descrizione *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required aria-required="true"
                              aria-describedby="descriptionHelp"><?= htmlspecialchars($descriptionValue) ?></textarea>
                    <small id="descriptionHelp" class="form-text text-muted">
                        Inserisci una breve descrizione della nota
                    </small>
                </div>

                <!-- Università -->
                <div class="mb-3">
                    <label for="university" class="form-label">Università *</label>
                    <input type="text" class="form-control" id="university" name="university" value="<?= htmlspecialchars($universityValue) ?>" required aria-required="true"
                           aria-describedby="universityHelp">
                    <small id="universityHelp" class="form-text text-muted">
                        Indica l'università di riferimento
                    </small>
                </div>

                <!-- Tipo Nota -->
                <div class="mb-3">
                    <label for="note_type" class="form-label">Tipo Nota</label>
                    <select class="form-select" id="note_type" name="note_type" aria-describedby="noteTypeHelp">
                        <option value="">Seleziona tipo</option>
                        <option value="riassunto" <?= $noteTypeValue === 'riassunto' ? 'selected' : '' ?>>Riassunto</option>
                        <option value="formulario" <?= $noteTypeValue === 'formulario' ? 'selected' : '' ?>>Formulario</option>
                        <option value="esercizi" <?= $noteTypeValue === 'esercizi' ? 'selected' : '' ?>>Esercizi</option>
                        <option value="note" <?= $noteTypeValue === 'note' ? 'selected' : '' ?>>Note</option>
                        <option value="altro" <?= $noteTypeValue === 'altro' ? 'selected' : '' ?>>Altro</option>
                    </select>
                    <small id="noteTypeHelp" class="form-text text-muted">
                        Tipo di contenuto della nota
                    </small>
                </div>

                <!-- Formato -->
                <div class="mb-3">
                    <label for="format" class="form-label">Formato *</label>
                    <select class="form-select" id="format" name="format" required aria-required="true"
                            aria-describedby="formatHelp">
                        <option value="">Seleziona un formato</option>
                        <option value="pdf" <?= $formatValue === 'pdf' ? 'selected' : '' ?>>PDF</option>
                        <option value="md" <?= $formatValue === 'md' ? 'selected' : '' ?>>Markdown</option>
                        <option value="tex" <?= $formatValue === 'tex' ? 'selected' : '' ?>>LaTeX</option>
                    </select>
                    <small id="formatHelp" class="form-text text-muted">
                        Seleziona il formato del file
                    </small>
                </div>

                <!-- File -->
                <div class="mb-3">
                    <label for="file" class="form-label">File <?= $isEdit ? '(opzionale)' : '*' ?></label>
                    <input type="file" class="form-control" id="file" name="file"
                           accept=".pdf,.md,.tex" <?= $isEdit ? '' : 'required aria-required="true"' ?>
                           aria-describedby="fileHelp">
                    <small id="fileHelp" class="form-text text-muted">
                        Formati supportati: PDF, MD, TEX (max 2MB)
                    </small>
                </div>

                <!-- Visibilità -->
                <div class="mb-3">
                    <label for="visibility" class="form-label">Visibilità *</label>
                    <select class="form-select" id="visibility" name="visibility" required aria-required="true">
                        <option value="public" <?= $visibilityValue === 'public' ? 'selected' : '' ?>>Pubblica</option>
                        <option value="private" <?= $visibilityValue === 'private' ? 'selected' : '' ?>>Privata</option>
                    </select>
                </div>

                <!-- Bottoni -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1" aria-hidden="true"></i>
                        <?= $isEdit ? 'Aggiorna nota' : 'Pubblica nota' ?>
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>
<script src="/js/newCourseToggle.js" defer></script>