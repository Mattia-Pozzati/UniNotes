# Cose da fare?

## Chiamate al database

### Note

**Note scaricate da {student_id}**

```sql
SELECT note.* user.name user.surname user.university
FROM note
INNER JOIN note_download
ON note.id = note_download.note_id
Join user on user.id == note.student_id 
WHERE note_download.student_id = {student_id};
```

**Note scritte da {student_id}**

Parametri:

- student_id -> id studente


```sql
select note.* from NOTE
where note.student_id = {student_id}
```

**Ricerca Note**
Parametri:
- Text -> Title e course
- University -> University
- Type -> Type
- Format -> Format

```sql
select note.* 
from note
join user 
    on user.id == note.student_id
where {text} LIKE note.title or
where {text} LIKE note.course or
where {university} == user.university or
where note.type == {Type}
where note.format == {Format}
```

**Singola Nota**

```sql
select note.*
from note
where note.id = {id}
```

Oltre a insert delete e update ma quelle sono scontate

#### Notification

**Notifiche di un User**

```sql
select notification.*
from  notification
where notification.receiver_id = {student_id}
```

Oltre agli inserimenti (send) delete e update


#### Comment

**Commenti ad una nota**

```sql
select c.*
from comment as c
where c.note_id = {note_id}
where c.parent_id = null
```

**Commenti ad un commento**

```sql
select c.*
from comment as c
where c.parent_id = {comment_id}
```

Inserimento 

#### Like

Invio (inserimento) cancellazione

```sql


```

#### User

**Classifiche**

Utenti con più note caricate

```sql
select user.*
    Count(*) as c
from user
join note
    on note.student_id = user.id
order by c desc
limit 3
```

Utenti con più like sulle note

```sql
SELECT
    u.id,
    u.name,
    u.email,
    COUNT(l.id) AS like_count
FROM user u
LEFT JOIN note n
    ON n.student_id = u.id
LEFT JOIN `like` l
    ON l.note_id = n.id
GROUP BY u.id, u.name, u.email
ORDER BY like_count DESC
LIMIT 3;
```
- Cambiare i mockUp in modo che siano coerenti alla grafica



