<?php
namespace App\View; ?>

<div class="container py-4">
    <div class="row justify-content-center">
            <?=  $getComponent('Base/sectionHeader', ['title' => $title ?? 'Modifica Nota']) ?>
            <?= $getComponent('Forms/newNotesForm', [
                'action' => $action ?? '/note/create',
                'courses' => $courses ?? [],
                'title' => $title_value ?? '',
                'description' => $description_value ?? '',
                'university' => $university_value ?? '',
                'note_type' => $note_type_value ?? '',
                'format' => $format_value ?? '',
                'selected_course_id' => $selected_course_id ?? null,
                'visibility' => $visibility_value ?? 'public',
                'is_edit' => $is_edit ?? false,
                'existing_file' => $existing_file ?? null
            ]) ?>
    </div>
</div>