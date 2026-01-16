<?php namespace App\View; ?>

<?php

$courses = $courses ?? [];
$action = $action ?? "";
?>

<section class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="<?= htmlspecialchars($action) ?>" enctype="multipart/form-data">
                
                <!-- Titolo -->
                <div class="mb-3">
                    <label for="title" class="form-label">Titolo *</label>
                    <input type="text" class="form-control" id="title" name="title" required aria-required="true"
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
                            <option value="<?= htmlspecialchars($course['id']) ?>">
                                <?= htmlspecialchars($course['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small id="courseHelp" class="form-text text-muted">
                        Scegli il corso di riferimento
                    </small>
                </div>

                <!-- Descrizione -->
                <div class="mb-3">
                    <label for="description" class="form-label">Descrizione *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required aria-required="true"
                              aria-describedby="descriptionHelp"></textarea>
                    <small id="descriptionHelp" class="form-text text-muted">
                        Inserisci una breve descrizione della nota
                    </small>
                </div>

                <!-- Università -->
                <div class="mb-3">
                    <label for="university" class="form-label">Università *</label>
                    <input type="text" class="form-control" id="university" name="university" required aria-required="true"
                           aria-describedby="universityHelp">
                    <small id="universityHelp" class="form-text text-muted">
                        Indica l'università di riferimento
                    </small>
                </div>

                <!-- Formato -->
                <div class="mb-3">
                    <label for="format" class="form-label">Formato *</label>
                    <select class="form-select" id="format" name="format" required aria-required="true"
                            aria-describedby="formatHelp">
                        <option value="">Seleziona un formato</option>
                        <option value="pdf">PDF</option>
                        <option value="md">MD</option>
                        <option value="tex">TEX</option>
                    </select>
                    <small id="formatHelp" class="form-text text-muted">
                        Seleziona il formato del file
                    </small>
                </div>

                <!-- File -->
                <div class="mb-3">
                    <label for="file" class="form-label">File *</label>
                    <input type="file" class="form-control" id="file" name="file"
                           accept=".pdf,.md,.tex" required aria-required="true"
                           aria-describedby="fileHelp">
                    <small id="fileHelp" class="form-text text-muted">
                        Formati supportati: PDF, MD, TEX
                    </small>
                </div>

                <!-- Opzioni avanzate -->
                <fieldset class="mb-3">
                    <legend class="form-label">Opzioni avanzate</legend>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="chatEnabled" name="chatEnabled" value="1">
                        <label class="form-check-label" for="chatEnabled">
                            Abilita chat AI per questa nota
                        </label>
                    </div>
                </fieldset>

                <!-- Bottoni -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1" aria-hidden="true"></i>
                        Pubblica nota
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>
