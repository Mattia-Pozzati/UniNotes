#!/bin/bash
# Creates sample files with the note title inside.
BASE_DIR="/Applications/XAMPP/xamppfiles/htdocs/Uninotes/Storage/upload"
mkdir -p "$BASE_DIR/note_1"
mkdir -p "$BASE_DIR/note_2"
mkdir -p "$BASE_DIR/note_3"

printf "Riassunto Analisi 1\n" > "$BASE_DIR/note_1/file_1.txt"
printf "Esercizi Fisica\n" > "$BASE_DIR/note_2/file_2.txt"
printf "Formulario Matematica\n" > "$BASE_DIR/note_3/file_3.txt"

chmod 644 "$BASE_DIR/note_"*"/file_"*.txt

echo "Sample files created in $BASE_DIR"