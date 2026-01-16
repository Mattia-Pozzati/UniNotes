<form class="container px-3">
    <section class="row">
        <div class="col-10 mb-3">
            <label for="searchbar" class="form-label"></label>
            <input type="text" class="form-control" id="searchbar" aria-describedby="searchbar" aria-label="searchbar">
        </div>
        <button type="submit" class="btn col-2">
            <i class="bi bi-search"></i>
        </button>
    </section>
    <section class="row">
        <div class="col-10 row ">
            <div class="mb-3 col-6">
                <label for="corso" class="form-label">Corso</label>
                <select id="corso" class="form-select">
                    <option>Corso</option>
                </select>
            </div>
            <div class="mb-3 col-6">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" class="form-select" value="Tipo">
                    <option>Riassunto</option>
                    <option>Schema</option>
                    <option>Esercizi</option>
                </select>
            </div>
        </div>
        <header class="col-2">
            <div>
                
            </div>
            <label for="resetBtn" class = "d-block mb-2">
                <i class="bi bi-filter"></i>
            </label>
            <button id="resetBtn" type="reset" class="btn border">
                Reset
            </button>
        </header>
    </section>
</form>
