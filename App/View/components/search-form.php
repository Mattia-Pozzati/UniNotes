<form class="row px-3 d-flex justify-content-center">
    <section class="row">
        <div class="col-10 mb-3">
            <label for="searchbar" class="form-label" value="Analisi-1">Cerca</label>
            <input type="text" class="form-control" id="searchbar" aria-describedby="searchbar">
        </div>
        <button type="submit" class="btn col-2">
            <i class="bi bi-search"></i>
        </button>
    </section>
    <section class="row">
        <header class="col-6 col-md-2">
            <label for="resetBtn" class="form-label d-flex align-items-center gap-2">
                <i class="bi bi-filter"></i>
                <span>Filter:</span>
            </label>
            <button id="resetBtn" type="reset" class="btn border">
                Reset
            </button>
        </header>

        <div class="col-6 col-md-10 row">
            <div class="col-12 col-md-4 mb-3">
                <label for="corso" class="form-label">Corso</label>
                <select id="corso" class="form-select">
                    <option>Corso</option>
                </select>
            </div>

            <div class="col-12 col-md-4 mb-3">
                <label for="file" class="form-label">File</label>
                <select id="file" class="form-select">
                    <option>PDF</option>
                    <option>MD</option>
                    <option>TEX</option>
                </select>
            </div>

            <div class="col-12 col-md-4 mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" class="form-select">
                    <option>Riassunto</option>
                    <option>Schema</option>
                    <option>Esercizi</option>
                </select>
            </div>
        </div>
    </section>

</form>