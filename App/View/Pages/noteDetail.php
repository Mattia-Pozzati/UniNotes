<?php namespace App\View; ?>

<?php
$note = $note ?? [];
$currentUserId = $currentUserId ?? null;
$isLoggedIn = $isLoggedIn ?? false;
$isAuthor = false;
?>

<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    <!-- Flash messages -->
    <?php if ($flash = \Core\Helper\SessionManager::flash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flash) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($flash = \Core\Helper\SessionManager::flash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flash) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($note['title'] ?? 'Nota') ?>
            </li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Colonna principale -->
        <div class="col-lg-8">
            <!-- Header della nota -->
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="h2 mb-3"><?= htmlspecialchars($note['title'] ?? 'Titolo Nota') ?></h1>
                    <p class="card-text"><?= htmlspecialchars($note['description'] ?? 'Descrizione nota') ?></p>

                    <!-- File icon placeholder -->
                    <div class="text-center my-4">
                        <i class="bi bi-file-earmark" style="font-size: 5rem; color: var(--bs-secondary);"></i>
                    </div>

                    <!-- Statistiche e azioni -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="text-muted small">
                            <i class="bi bi-person me-1"></i>
                            <span><?= htmlspecialchars($note['author']['name'] ?? 'Autore') ?></span>
                            <span class="mx-2">•</span>
                            <i class="bi bi-book me-1"></i>
                            <span><?= htmlspecialchars($note['course'] ?? 'Corso') ?></span>
                        </div>

                        <form action="/note/<?= $note['id'] ?>/like" method="POST" class="d-inline">
                            <button type="submit"
                                class="btn btn-sm <?= $note['user_has_liked'] ?? false ? 'btn-primary' : 'btn-outline-primary' ?>">
                                <i class="bi bi-hand-thumbs-up"></i>
                                <?= $note['likes_count'] ?? 0 ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Chatta con UninotesAI -->
            <?php if (!empty($note['files'])): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Chatta con UninotesAI</h5>
                        <p class="text-muted small mb-3">Fai domande relative alla nota e UninotesAI risponderà</p>

                        <!-- Form domanda -->
                        <form action="/note/<?= $note['id'] ?>/chat" method="POST">
                            <div class="mb-3">
                                <label for="chatQuestion" class="form-label">Domanda?</label>
                                <input type="text" class="form-control" id="chatQuestion" name="question"
                                    placeholder="Scrivi la tua domanda...">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Chiedi alla nota
                                <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </form>

                        <!-- Risposta AI -->
                        <?php if (isset($aiResponse) && !empty($aiResponse)): ?>
                            <div class="mt-4">
                                <h6 class="mb-2">Risposta</h6>
                                <div class="p-3 bg-light rounded text-dark" style="min-height: 150px;">
                                    <?= nl2br(htmlspecialchars($aiResponse)) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sezione commenti -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Commenti</h5>

                    <!-- Lista commenti -->
                    <?php if (empty($note['comments'])): ?>
                        <p class="text-muted">Nessun commento ancora. Sii il primo a commentare!</p>
                    <?php else: ?>
                        <div class="comments-list">
                            <?php foreach ($note['comments'] ?? [] as $index => $comment): ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-2">
                                            <i class="bi bi-person-circle me-2"
                                                style="font-size: 2rem; color: var(--bs-primary);"></i>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong><?= htmlspecialchars($comment['author']) ?></strong>
                                                        <?php if ($comment['is_author'] ?? false): ?>
                                                            <span class="badge bg-secondary ms-2">Autore</span>
                                                        <?php endif; ?>
                                                        <small class="text-muted ms-2"><?= htmlspecialchars($comment['created_at']) ?></small>
                                                    </div>

                                                    <div class="d-flex gap-2">
                                                        <!-- Bottone elimina (proprietario o admin o autore nota) -->
                                                        <?php if ($isLoggedIn): ?>
                                                            <?php 
                                                            $userRole = \Core\Helper\SessionManager::get('user')['role'] ?? 'student';
                                                            $isCommentOwner = isset($comment['student_id']) && ($comment['student_id'] == $currentUserId);
                                                            $isNoteAuthor = $comment['is_author'] ?? false;
                                                            $isAdmin = $userRole === 'admin';
                                                            $canDelete = $isCommentOwner || $isNoteAuthor || $isAdmin;
                                                            ?>
                                                            <?php if ($canDelete): ?>
                                                                <form action="/note/<?= $note['id'] ?>/comment/<?= $comment['id'] ?>/delete" method="POST" 
                                                                    onsubmit="return confirm('Sei sicuro di voler eliminare questo commento?')" class="d-inline">
                                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        
                                                        <!-- Bottone collapse -->
                                                        <?php if (!empty($comment['replies'])): ?>
                                                            <button class="btn btn-sm btn-link text-muted p-0" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#replies-<?= $index ?>"
                                                                aria-expanded="false">
                                                                <span class="show-text">Vedi risposte (<?= count($comment['replies']) ?>)</span>
                                                                <i class="bi bi-chevron-down"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <p class="mb-2 mt-2"><?= htmlspecialchars($comment['content']) ?></p>
                                                
                                                <!-- Bottone rispondi -->
                                                <?php if ($isLoggedIn): ?>
                                                    <button class="btn btn-sm btn-link p-0" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#reply-form-<?= $comment['id'] ?>">
                                                        <i class="bi bi-reply"></i> Rispondi
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <!-- Form risposta -->
                                                <?php if ($isLoggedIn): ?>
                                                    <div class="collapse mt-2" id="reply-form-<?= $comment['id'] ?>">
                                                        <form action="/note/<?= $note['id'] ?>/comment" method="POST">
                                                            <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
                                                            <div class="mb-2">
                                                                <textarea class="form-control form-control-sm" name="content" rows="2"
                                                                    placeholder="Scrivi una risposta..." required></textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-sm btn-primary">Invia</button>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Risposte collassabili -->
                                        <?php if (!empty($comment['replies'])): ?>
                                            <div class="collapse mt-3" id="replies-<?= $index ?>">
                                                <div class="border-start border-3 border-primary ps-3 ms-4">
                                                    <?php foreach ($comment['replies'] as $reply): ?>
                                                        <div class="mb-3">
                                                            <div class="d-flex align-items-start justify-content-between">
                                                                <div class="d-flex align-items-start flex-grow-1">
                                                                    <i class="bi bi-person-circle me-2"
                                                                        style="font-size: 1.5rem; color: var(--bs-secondary);"></i>
                                                                    <div>
                                                                        <strong class="small"><?= htmlspecialchars($reply['author']) ?></strong>
                                                                        <small class="text-muted ms-2"><?= htmlspecialchars($reply['created_at']) ?></small>
                                                                        <p class="mb-0 small"><?= htmlspecialchars($reply['content']) ?></p>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Bottone elimina risposta -->
                                                                <?php if ($isLoggedIn): ?>
                                                                    <?php 
                                                                    $userRole = \Core\Helper\SessionManager::get('user')['role'] ?? 'student';
                                                                    $isReplyOwner = isset($reply['student_id']) && ($reply['student_id'] == $currentUserId);
                                                                    $isAdmin = $userRole === 'admin';
                                                                    $isNoteAuthor = ($note['student_id'] ?? null) == $currentUserId;
                                                                    $canDelete = $isReplyOwner || $isAdmin || $isNoteAuthor;
                                                                    ?>
                                                                    <?php if ($canDelete): ?>
                                                                        <form action="/note/<?= $note['id'] ?>/comment/<?= $reply['id'] ?>/delete" method="POST" 
                                                                            onsubmit="return confirm('Eliminare questa risposta?')" class="d-inline">
                                                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                                                <i class="bi bi-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form nuovo commento -->
                    <?php if ($isLoggedIn): ?>
                        <form action="/note/<?= $note['id'] ?>/comment" method="POST" class="mt-4">
                            <div class="mb-3">
                                <label for="commentContent" class="form-label">Aggiungi un commento</label>
                                <textarea class="form-control" id="commentContent" name="content" rows="3"
                                    placeholder="Scrivi un commento..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>
                                Invia commento
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info mt-4">
                            <a href="/login">Accedi</a> per lasciare un commento
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Info autore -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informazioni autore</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
                    </div>
                    <h5><?= htmlspecialchars($note['author']['name'] ?? 'Autore') ?></h5>
                    <div class="mt-3">
                        <span class="badge bg-primary fs-6">
                            <i class="bi bi-star-fill me-1"></i>
                            <?= $note['author']['reputation'] ?? 0 ?> reputazione
                        </span>
                    </div>
                </div>
            </div>
              
            <!-- File allegati -->
            <?php if (!empty($note['files'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            File allegati
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($note['files'] ?? [] as $file): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <i class="bi bi-file-pdf text-danger me-2"></i>
                                    <small><?= htmlspecialchars($file['filename']) ?></small>
                                </div>
                                <a href="/file/<?= $file['id'] ?>/download" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tags -->
            <?php if (!empty($note['tags'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Tags</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($note['tags'] ?? [] as $tag): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>