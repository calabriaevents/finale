# Report di Analisi Vulnerabilità - Progetto Passione Calabria

Questo documento contiene un'analisi dettagliata delle vulnerabilità di sicurezza identificate nel codice sorgente del progetto "Passione Calabria". Ogni vulnerabilità è descritta con il suo livello di criticità, il rischio associato, i file interessati e le azioni correttive consigliate.

**Data Analisi:** 2025-10-03
**Analista:** Jules

---

*Inizializzazione del report. Le sezioni dettagliate verranno aggiunte di seguito.*

---

## 1. Mancanza di Autenticazione nell'Admin (Livello: CRITICO)

### Descrizione del Rischio
L'intera area amministrativa del sito (`/admin/`) è accessibile a chiunque su Internet senza necessità di effettuare un login. La linea di codice responsabile per la verifica dell'autenticazione è stata commentata in tutti i file principali dell'area amministrativa.

Questo espone l'intero sistema a una compromissione totale. Un utente malintenzionato può accedere a tutte le funzionalità di gestione, tra cui:
- Creare, modificare ed eliminare articoli, categorie, città, ecc.
- Visualizzare dati sensibili degli utenti e delle attività commerciali.
- Alterare le impostazioni del sito.
- Eseguire il backup del database, ottenendo una copia completa di tutti i dati.

### File Interessati
La vulnerabilità è presente in quasi tutti i file `.php` nella directory `admin/`, dove il controllo di accesso è stato disattivato.
- `admin/index.php` (riga 5: `// requireLogin();`)
- `admin/articoli.php` (riga 5: `// requireLogin();`)
- E molti altri file nella stessa directory.

### Soluzione Consigliata
È imperativo riattivare immediatamente il controllo di autenticazione su **tutte** le pagine dell'area amministrativa.

1.  **Decommentare la funzione `requireLogin()`:** In ogni file all'interno della directory `admin/`, rimuovere il commento `//` dalla riga `requireLogin();`.

    **Codice vulnerabile:**
    ```php
    <?php
    require_once '../includes/config.php';
    require_once '../includes/database_mysql.php';

    // Controlla autenticazione (da implementare)
    // requireLogin();
    ```

    **Codice corretto:**
    ```php
    <?php
    require_once '../includes/config.php';
    require_once '../includes/database_mysql.php';

    // Controlla autenticazione
    requireLogin();
    ```

2.  **Creare una pagina di Login:** Assicurarsi che esista una pagina `admin/login.php` funzionante a cui la funzione `requireLogin()` possa reindirizzare. Questa pagina dovrebbe contenere un form per inserire email/username e password.

3.  **Proteggere la pagina di Login:** La pagina di login stessa deve essere protetta contro attacchi di forza bruta (ad esempio, implementando un limite di tentativi di login falliti).

---

## 2. Cross-Site Request Forgery (CSRF) (Livello: CRITICO)

### Descrizione del Rischio
Le azioni che modificano lo stato dei dati, come l'eliminazione di un articolo, vengono eseguite tramite richieste GET dirette e non sono protette da un token anti-CSRF. Un token anti-CSRF è un valore segreto e unico che il server invia al client per assicurarsi che una richiesta provenga effettivamente dall'interfaccia utente dell'applicazione e non da una fonte esterna.

Senza questa protezione, un utente malintenzionato può creare un link o uno script malevolo su un sito esterno e indurre un amministratore (che ha una sessione attiva sul pannello di amministrazione) a cliccarci sopra. Questo può portare all'esecuzione di azioni indesiderate a nome dell'amministratore, come la cancellazione di tutti gli articoli, senza che lui se ne accorga.

### File Interessati
- `admin/articoli.php` (righe 175-178): Il link per l'eliminazione è una semplice richiesta GET.
  ```html
  <a href="articoli.php?action=delete&id=<?php echo $article['id']; ?>"
     class="..."
     onclick="return confirm('Sei sicuro di voler eliminare questo articolo?');">
      Elimina
  </a>
  ```
- Qualsiasi altra pagina che esegue azioni di modifica/eliminazione con un meccanismo simile.

### Soluzione Consigliata
Per mitigare questa vulnerabilità, è necessario implementare un sistema di token anti-CSRF per tutte le richieste che modificano dati (tipicamente, tutte le richieste non-GET).

1.  **Generare e Salvare un Token in Sessione:** All'inizio di una sessione utente, generare un token casuale e memorizzarlo in `$_SESSION`.
    ```php
    // Da aggiungere in un file di inizializzazione o nella logica di login
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ```

2.  **Aggiungere il Token ai Form:** Includere il token come campo nascosto in tutti i form che eseguono azioni di modifica/eliminazione.
    ```html
    <form action="articoli.php?action=delete&id=<?php echo $article['id']; ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit" onclick="return confirm('Sei sicuro?');">Elimina</button>
    </form>
    ```
    **Nota:** L'azione di eliminazione dovrebbe essere gestita tramite una richiesta `POST`, non `GET`.

3.  **Verificare il Token sul Server:** Prima di eseguire qualsiasi azione, verificare che il token inviato corrisponda a quello salvato in sessione.
    ```php
    // All'inizio del blocco che gestisce le richieste POST o l'azione di eliminazione
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            // Token non valido o mancante: blocca l'operazione
            die('Errore CSRF: Richiesta non valida.');
        }
        // ... procedi con l'azione sicura ...
    }
    ```

---

## 3. Credenziali Hardcoded e Password di Esempio (Livello: CRITICO)

### Descrizione del Rischio
Il codice sorgente contiene credenziali sensibili scritte in chiaro (hardcoded), tra cui la password del database e un hash di esempio per la password dell'amministratore.

1.  **Password del Database:** La password del database di produzione (`'Barboncino692@@'`) è visibile nel file di configurazione. Se il codice sorgente venisse esposto (ad esempio, tramite un repository Git pubblico, un backup errato o una vulnerabilità del server), un malintenzionato avrebbe accesso completo al database.
2.  **Password dell'Amministratore:** L'hash della password dell'amministratore è un valore di esempio (`'$2y$10$example'`). Questo tipo di hash di default è spesso associato a password deboli e note (come "password" o "example"), rendendo estremamente facile per un aggressore indovinare la password e ottenere l'accesso amministrativo, anche se la pagina di login fosse attiva.

### File Interessati
- `includes/db_config.php` (riga 16):
  ```php
  $password = 'Barboncino692@@'; // TODO: Move to environment variable immediately!
  ```
- `includes/config.php` (riga 20):
  ```php
  define('ADMIN_PASSWORD_HASH', '$2y$10$example'); // Cambiare con hash reale
  ```

### Soluzione Consigliata
Tutte le credenziali e le informazioni sensibili devono essere rimosse dal codice sorgente e gestite tramite variabili d'ambiente.

1.  **Utilizzare Variabili d'Ambiente per il Database:** Rimuovere completamente il blocco `else` che contiene le credenziali hardcoded in `includes/db_config.php`. L'applicazione deve fare affidamento *esclusivamente* sulle variabili d'ambiente per caricare la configurazione del database.

    **Codice da rimuovere in `includes/db_config.php`:**
    ```php
    // If environment variables are not set, use default configuration
    // IMPORTANT: Change these values and move to environment variables in production
    if (!$host) {
        $host = 'db5018301966.hosting-data.io';
        $dbname = 'dbs14504718';
        $username = 'dbu1167357';
        $password = 'Barboncino692@@'; // TODO: Move to environment variable immediately!
    }
    ```
    Il server di produzione dovrà essere configurato con le seguenti variabili d'ambiente: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`.

2.  **Generare un Hash Sicuro per la Password Admin:** Creare uno script a parte (da non caricare in produzione) per generare un hash forte per una nuova password sicura.

    **Script di esempio (`generate_hash.php`):**
    ```php
    <?php
    // Scegliere una password forte e sicura
    $password = 'LaMiaNuovaPasswordSuperSicura123!';
    $hash = password_hash($password, PASSWORD_ARGON2ID);
    echo "Il tuo nuovo hash è: " . $hash;
    ?>
    ```
    Eseguire questo script e copiare l'hash generato nella variabile d'ambiente `ADMIN_PASSWORD_HASH` sul server. Il file `config.php` dovrebbe quindi caricare l'hash da lì.

    **Codice corretto in `includes/config.php`:**
    ```php
    // Carica l'hash della password dalla variabile d'ambiente
    define('ADMIN_PASSWORD_HASH', getenv('ADMIN_PASSWORD_HASH'));
    ```

---

## 4. Esposizione della Password del Database durante i Backup (Livello: ALTO)

### Descrizione del Rischio
La funzionalità di backup, presente nel file `includes/database_mysql.php`, utilizza la funzione `exec()` per eseguire il comando `mysqldump`. La password del database viene passata direttamente come argomento sulla riga di comando.

Sulla maggior parte dei sistemi operativi, specialmente in ambienti di hosting condiviso, l'elenco dei processi in esecuzione può essere visualizzato da altri utenti sullo stesso server (ad esempio, tramite il comando `ps aux`). Questo significa che il comando `mysqldump`, completo di password del database in chiaro, sarebbe visibile a chiunque, portando a una potenziale compromissione totale del database.

### File Interessati
- `includes/database_mysql.php` (nella funzione `createBackup()`):
  ```php
  $command = sprintf('mysqldump --host=%s --user=%s --password=%s %s > %s', escapeshellarg($this->host), escapeshellarg($this->username), escapeshellarg($this->password), escapeshellarg($this->dbname), escapeshellarg($backupFile));
  @exec($command, $output, $return_var);
  ```

### Soluzione Consigliata
La password non dovrebbe mai essere passata come argomento da riga di comando. L'approccio standard e sicuro consiste nell'utilizzare un file di configurazione MySQL (es. `~/.my.cnf`) che `mysqldump` può leggere automaticamente.

1.  **Creare un File di Configurazione `.my.cnf`:** Sul server, nella home directory dell'utente che esegue lo script PHP, creare un file chiamato `.my.cnf` con permessi restrittivi (es. `600`, leggibile e scrivibile solo dal proprietario).

    **Contenuto del file `~/.my.cnf`:**
    ```ini
    [mysqldump]
    user = il_tuo_username_db
    password = "la_tua_password_db"
    host = il_tuo_host_db

    [client]
    user = il_tuo_username_db
    password = "la_tua_password_db"
    host = il_tuo_host_db
    ```

2.  **Modificare la Funzione `createBackup()`:** Aggiornare il codice PHP per eseguire `mysqldump` senza includere le credenziali nel comando. `mysqldump` utilizzerà automaticamente il file `.my.cnf`.

    **Codice corretto in `includes/database_mysql.php`:**
    ```php
    public function createBackup() {
        // ... (controlli iniziali e setup directory)
        $backupFile = $backupDir . "/passione_calabria_mysql_backup_$timestamp.sql";

        // Comando senza credenziali
        $command = sprintf('mysqldump --host=%s %s > %s',
            escapeshellarg($this->host),
            escapeshellarg($this->dbname),
            escapeshellarg($backupFile)
        );

        // Eseguire il comando senza soppressione degli errori
        exec($command, $output, $return_var);

        return ($return_var === 0 && file_exists($backupFile) && filesize($backupFile) > 0) ? $backupFile : false;
    }
    ```
    Si raccomanda anche di **rimuovere il carattere `@`** prima di `exec()` per garantire che eventuali errori restituiti da `mysqldump` vengano registrati e possano essere diagnosticati.

---

## 5. Upload di File non Sicuro (Livello: ALTO)

### Descrizione del Rischio
La funzionalità di upload dei file, utilizzata per le immagini (featured, hero, gallery) e altri documenti (PDF del menu), si basa unicamente sul controllo dell'estensione del file per validarne il tipo. Questo approccio è insicuro perché un utente malintenzionato può rinominare un file eseguibile (ad esempio, uno script PHP) con un'estensione consentita (es. `.jpg`).

Se questo file malevolo viene caricato sul server in una directory accessibile pubblicamente, l'attaccante potrebbe tentare di eseguirlo visitando il suo URL diretto. Se il server è configurato per eseguire script anche in quella directory, questo potrebbe portare a una Remote Code Execution (RCE), una delle vulnerabilità più gravi, che concede all'attaccante il controllo completo del server.

### File Interessati
- `admin/articoli.php` (funzione helper `uploadSingleFile` e logica di upload della galleria):
  ```php
  // Esempio di controllo vulnerabile
  $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  if (in_array($fileExtension, $allowedExtensions)) {
      // ... procede con l'upload ...
  }
  ```

### Soluzione Consigliata
È necessario implementare un processo di validazione dei file a più livelli, che non si fidi mai dell'estensione fornita dall'utente.

1.  **Verificare il Tipo MIME Reale del File:** Utilizzare le funzioni PHP `finfo` per determinare il vero tipo MIME del file basandosi sul suo contenuto, ignorando l'estensione.

    **Codice di esempio per la validazione:**
    ```php
    // Dentro la funzione di upload
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);

    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

    if (in_array($mime_type, $allowed_mime_types)) {
        // Il tipo MIME è valido, procedere con l'upload
    } else {
        // Tipo di file non consentito, bloccare l'operazione
        return null;
    }
    ```

2.  **Rinominare i File Caricati:** Assegnare un nome casuale e univoco ai file caricati, come il codice già fa (`uniqid()`). Questo è un buon primo passo per impedire a un utente di indovinare il nome del file e tentare di eseguirlo.

3.  **Disabilitare l'Esecuzione di Script nella Directory di Upload:** La misura di sicurezza più importante è configurare il server web per **non eseguire mai script PHP** (o altri linguaggi server-side) nella directory `uploads/`. Questo può essere fatto tramite un file `.htaccess` posizionato all'interno della directory `uploads/`.

    **Contenuto del file `uploads/.htaccess`:**
    ```apache
    # Disabilita l'esecuzione di script PHP
    <FilesMatch "\.(php|phtml|php3|php4|php5|php7|phps)$">
        Order Deny,Allow
        Deny from All
    </FilesMatch>

    # In alternativa, per Apache 2.4+
    <IfModule mod_php7.c>
        php_flag engine off
    </IfModule>
    ```

4.  **Servire i File da un Sottodominio "Dumb":** Per una sicurezza ancora maggiore, configurare un sottodominio separato (es. `static.passionecalabria.it`) che punta alla directory di upload e non ha alcun motore PHP abilitato, per servire tutti i contenuti caricati dagli utenti.

---

## 6. Accesso Pubblico ai Backup del Database (Livello: ALTO)

### Descrizione del Rischio
La funzione `createBackup()` salva i backup completi del database in una directory `backups/` situata all'interno della directory principale del progetto. Se questa directory è servita dal web server (come è probabile per impostazione predefinita), chiunque su Internet potrebbe scaricare l'intero backup del database semplicemente indovinando l'URL (es. `https://www.tuosito.it/backups/passione_calabria_mysql_backup_2025-10-03_12-30-00.sql`).

Un backup del database contiene tutti i dati del sito, incluse informazioni sugli utenti, contenuti privati, dati delle attività commerciali e potenzialmente altre informazioni sensibili. L'accesso a questi dati rappresenta una violazione della privacy gravissima e una compromissione totale delle informazioni del sito.

### File Interessati
- `includes/database_mysql.php` (nella funzione `createBackup()`):
  ```php
  $backupDir = dirname(__DIR__) . '/backups';
  if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);
  // ...
  $backupFile = $backupDir . "/passione_calabria_mysql_backup_$timestamp.sql";
  ```

### Soluzione Consigliata
La directory dei backup non deve **mai** essere accessibile pubblicamente dal web.

1.  **Spostare la Directory dei Backup:** La soluzione più sicura è spostare la directory dei backup al di fuori della root del server web (la cartella `public_html`, `www`, o simile).

    **Esempio di struttura sicura:**
    ```
    /var/www/
    ├── backups/  <-- Directory dei backup (NON accessibile dal web)
    └── passione_calabria/ <-- Root del progetto (accessibile dal web)
        ├── admin/
        ├── includes/
        └── index.php
    ```
    Il codice PHP dovrà essere aggiornato per puntare a questa nuova posizione sicura.

    **Codice corretto in `includes/database_mysql.php`:**
    ```php
    // Puntare a una directory sopra la web root
    $backupDir = dirname(__DIR__, 2) . '/backups';
    ```

2.  **Proteggere la Directory con `.htaccess` (se non è possibile spostarla):** Se lo spostamento non è un'opzione, è assolutamente necessario proteggere la directory con un file `.htaccess` che neghi l'accesso a tutti.

    **Contenuto del file `backups/.htaccess`:**
    ```apache
    # Nega l'accesso a tutti gli utenti
    Deny from all

    # Per Apache 2.4+
    # Require all denied
    ```
    Inoltre, è buona norma aggiungere un file `index.html` vuoto nella directory per impedire il "directory listing" se il server è configurato in modo insicuro.

---

## 7. Cross-Site Scripting (XSS) (Livello: ALTO)

### Descrizione del Rischio
Il Cross-Site Scripting (XSS) è una vulnerabilità che consente a un utente malintenzionato di iniettare script malevoli (solitamente JavaScript) nelle pagine web visualizzate da altri utenti. Nel pannello di amministrazione, questo è particolarmente pericoloso: se un attaccante riesce a far eseguire uno script nel browser di un amministratore, può rubare il suo cookie di sessione, eseguire azioni a suo nome o reindirizzarlo a siti di phishing.

Sebbene il codice utilizzi correttamente la funzione `htmlspecialchars()` in molte parti per visualizzare i dati (es. titoli degli articoli), questa pratica non è applicata in modo coerente. Ad esempio, i messaggi di errore del database vengono stampati direttamente nell'HTML, creando un potenziale punto di iniezione.

### File Interessati
- `admin/index.php` (riga 103): Il messaggio di errore del database viene mostrato senza sanificazione.
  ```php
  <p><?php echo $dbError; ?></p>
  ```
- Qualsiasi altra pagina in cui variabili (specialmente quelle che potrebbero essere influenzate da input, come i parametri URL) vengono stampate direttamente nell'HTML senza `htmlspecialchars()`.

### Soluzione Consigliata
La regola fondamentale per prevenire l'XSS è: **sanificare sempre l'output prima di inserirlo in una pagina HTML**.

1.  **Utilizzare `htmlspecialchars()` su Tutti i Dati in Output:** Applicare `htmlspecialchars()` a qualsiasi variabile che viene stampata nel corpo HTML, a meno che non si sia assolutamente certi che contenga solo HTML sicuro e intenzionale.

    **Codice vulnerabile in `admin/index.php`:**
    ```php
    <p><?php echo $dbError; ?></p>
    ```

    **Codice corretto:**
    ```php
    <p><?php echo htmlspecialchars($dbError); ?></p>
    ```

2.  **Utilizzare la Funzione `sanitize()` Esistente:** Il progetto ha già una funzione `sanitize()` in `includes/config.php` che wrappa `htmlspecialchars()`. Questa funzione dovrebbe essere utilizzata per coerenza.

    **Codice corretto (alternativa):**
    ```php
    <p><?php echo sanitize($dbError); ?></p>
    ```

3.  **Audit del Codice:** Eseguire una revisione completa di tutti i file `.php` del progetto (sia nell'area pubblica che in quella amministrativa) per identificare ogni istanza di `echo $variabile;` o `<?= $variabile ?>` e assicurarsi che l'output sia correttamente sanificato con `htmlspecialchars()` o una funzione equivalente.

---

## 8. Information Disclosure tramite Messaggi di Errore (Livello: MEDIO)

### Descrizione del Rischio
Quando l'applicazione incontra un errore (ad esempio, un errore di connessione al database), i messaggi di errore tecnici di PDO vengono mostrati direttamente all'utente. Questi messaggi, sebbene utili per il debug, possono rivelare a un potenziale attaccante informazioni sensibili sulla struttura interna dell'applicazione.

Le informazioni esposte possono includere:
- Nomi di tabelle o colonne del database.
- Percorsi completi dei file sul server.
- Indirizzi IP del server del database.
- Versioni del software (PHP, MySQL).

Un aggressore può utilizzare queste informazioni per mappare la struttura dell'applicazione e pianificare attacchi più mirati.

### File Interessati
- `admin/index.php` (righe 101-104):
  ```php
  <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
      <p class="font-bold">Errore di Connessione al Database</p>
      <p><?php echo $dbError; ?></p>
  </div>
  ```
- `includes/database_mysql.php` (costruttore della classe `Database`):
  ```php
  $this->error_message = 'Errore connessione database MySQL: ' . $e->getMessage();
  error_log($this->error_message);
  ```
  Sebbene l'errore venga registrato (`error_log`), la variabile `$dbError` che contiene il messaggio viene poi utilizzata per l'output in `index.php`.

### Soluzione Consigliata
Gli errori dettagliati devono essere registrati in un file di log sicuro sul server e mai mostrati all'utente finale. All'utente dovrebbe essere presentato solo un messaggio di errore generico.

1.  **Configurare la Gestione degli Errori per la Produzione:** Assicurarsi che le impostazioni di PHP in un ambiente di produzione siano configurate per non mostrare errori.

    **Impostazioni PHP raccomandate per la produzione:**
    ```php
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '/percorso/sicuro/del/server/php_errors.log'); // Un percorso non accessibile dal web
    ```
    Il file `includes/config.php` tenta già di fare questo, ma si basa su una variabile d'ambiente (`$_ENV['ENVIRONMENT']`) che potrebbe non essere impostata. È buona norma impostare questi valori direttamente nel file `php.ini` del server.

2.  **Mostrare Messaggi Generici:** Modificare il codice per mostrare sempre un messaggio generico all'utente, indipendentemente dall'errore specifico.

    **Codice corretto in `admin/index.php`:**
    ```php
    <?php if ($dbError): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
        <p class="font-bold">Errore di Connessione al Database</p>
        <p>Si è verificato un problema tecnico. Si prega di riprovare più tardi.</p>
        <?php
            // In un ambiente di sviluppo, si può ancora mostrare l'errore
            if (($_ENV['ENVIRONMENT'] ?? 'production') === 'development') {
                echo '<p>' . htmlspecialchars($dbError) . '</p>';
            }
        ?>
    </div>
    <?php endif; ?>
    ```
    Questo approccio permette di avere ancora i dettagli dell'errore in fase di sviluppo, ma li nasconde in produzione.