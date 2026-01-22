<?php namespace App\View; 

$courses;

?>
<?php $courses = $courses ?? []; ?>

<form method="GET" action="<?= htmlspecialchars($action) ?>" class="row px-3 d-flex justify-content-center">
    <section class="row">
        <div class="col-10 mb-3">
                 <label for="searchbar" class="form-label">Cerca</label>
                 <input type="text" class="form-control" id="searchbar" name="q"
                     aria-describedby="searchHelp">
            <small id="searchHelp" class="form-text text-muted">
                Inserisci parole chiave per la ricerca
            </small>
        </div>
        <button type="submit" class="btn col-2" aria-label="Cerca">
            <i class="bi bi-search" aria-hidden="true"></i>
        </button>
    </section>

    <section class="row mt-3">
        <fieldset class="col-6 col-md-2">
            <legend class="form-label d-flex align-items-center gap-2">
                <i class="bi bi-filter" aria-hidden="true"></i>
                <span>Filter:</span>
            </legend>
            <button id="resetBtn" type="reset" class="btn border" aria-label="Reset filtri">
                Reset
            </button>
        </fieldset>

        <div class="col-6 col-md-10 row">
            <div class="col-12 col-md-4 mb-3">
                <label for="corso" class="form-label">Corso</label>
                 <select id="corso" name="course" class="form-select">
                    <option value="">Seleziona corso</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['name']) ?>"><?= htmlspecialchars($course['name']) ?></option>
                    <?php endforeach; ?>
                    
                </select>
            </div>

            <div class="col-12 col-md-4 mb-3">
                <label for="file" class="form-label">File</label>
                <select id="file" name="format" class="form-select">
                    <option value="">Seleziona formato</option>
                    <option value="pdf">PDF</option>
                    <option value="md">MD</option>
                    <option value="tex">TEX</option>
                </select>
            </div>

            <div class="col-12 col-md-4 mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" name="note_type" class="form-select">
                    <option value="">Seleziona tipo</option>
                    <option value="riassunto">Riassunto</option>
                    <option value="schema">Schema</option>
                    <option value="esercizi">Esercizi</option>
                </select>
            </div>
        </div>
    </section>
</form>
