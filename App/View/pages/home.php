
<?php echo \App\View\View::getComponent('section-header', ["titolo" => "Search", "p" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quibusdam voluptatum assumenda aperiam possimus at est tempore expedita iure, harum cupiditate, dolorum vitae nulla aliquam voluptatibus reiciendis minima temporibus omnis repellendus."]); ?>
<?php echo \App\View\View::getComponent('search-form'); ?>
<?php
// Esempio con dati mock - nella realtà li passerai dal controller
$notes = [
    [
        'titolo' => 'Appunti di Analisi',
        'autore' => 'Mario Rossi',
        'corso' => 'Analisi 1',
        'desc' => 'Appunti completi del corso di Analisi Matematica',
        'chatEnabled' => true,
        'tags' => ['PDF', 'Note', 'Salva']
    ],
    [
        'titolo' => 'Fisica Generale',
        'autore' => 'Luigi Bianchi',
        'corso' => 'Fisica 1',
        'desc' => 'Riassunto delle lezioni di fisica',
        'chatEnabled' => false,
        'tags' => ['PDF']
    ],
    [
        'titolo' => 'Programmazione',
        'autore' => 'Anna Verdi',
        'corso' => 'Informatica',
        'desc' => 'Esercizi e soluzioni di programmazione',
        'chatEnabled' => true,
        'tags' => ['Code', 'Esercizi']
    ]
];
?>

<?php echo \App\View\View::getComponent('card-grid', ['notes' => $notes]); ?>