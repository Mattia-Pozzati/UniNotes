<?php

$courses = $courses ?? [];

?>

<section class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="/note/create" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Titolo *</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="course" class="form-label">Corso *</label>
                    <select class="form-select" id="course" name="course" required>
                        <option value="">Seleziona un corso</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course['id']) ?>">
                                <?= htmlspecialchars($course['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrizione *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="university" class="form-label">Università *</label>
                    <input type="text" class="form-control" id="university" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="format" class="form-label">Formato *</label>
                    <select class="form-select" id="course" name="format" required>
                        <option value="">Seleziona un Formato</option>
                        <option value="">PDF</option>
                        <option value="">MD</option>
                        <option value="">TEX</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">File *</label>
                    <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx" required>
                    <small class="form-text text-muted">Formati supportati: PDF, MD, TEX</small>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="chatEnabled" name="chatEnabled" value="1">
                    <label class="form-check-label" for="chatEnabled">
                        Abilita chat AI per questa nota
                    </label>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>
                        Pubblica nota
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>