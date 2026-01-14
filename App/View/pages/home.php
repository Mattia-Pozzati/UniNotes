<?php 
echo \App\View\View::getComponent('section-header', [
                      "titolo" => "Search",
                      "p" => "Trova gli appunti che ti servono tra migliaia di note condivise dagli studenti."]);

echo \App\View\View::getComponent('search-form');


$notes = $notes ?? [];

echo \App\View\View::getComponent('card-grid', [
                      'notes' => $notes]);
;?>
