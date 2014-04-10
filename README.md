<h1>abizeitung Online</h1>

Abizeitungen leicht gemacht. Jedes Stufenmitglied bekommt einen persönlichen Login und kann damit seinen Steckbrief anfertigen, an Wahlen über Mitschüler und Lehrer teilnehmen, ein Stufenpärchen wählen, Kommentare über Personen schreiben und Zitate aus dem Unterreicht beisteuern.

Zum extrahieren dieser Informationen liegen im Ordner tools Skripte bereit.

<h2>Installation</h2>

Projekt runterladen und die install.php mit php-cli ausführen. Diese bereitet den MySQL Server vor und erstellt die benötigte constants.php.
Danach kann der Ordner page unverändert in den <i>www</i> Ordner einer apache Installation verschoben werden.
Der <i>tools</i> Order muss ebenfalls in einem für apache zugänglichem Pfad liegen.

<h2>Tools</h2>
Zur Benutzung aller Tools wird ein Sessioncookie eines Adminlogins benötigt. Diese sollten daher sinnvollerweise auf dem gleichen V-Server (apache) wie die Hauptseite liegen, oder die <i>login.php</i> muss in den <i>tools</i> Ordner kopiert werden.

<h3>comment_export.php</h3>
Exportiert die Kommentare in Dateien in der Form <i>Nachname, Vorname,txt</i> in den Ordner <i>export/comment-&lt;Datum+Uhrzeit&gt;</i> und zeigt die Zahl der exportierten Kommentare an. Die Dateien sind im Windows Format mit \r\n als Newline-character. <strong>Benötigt Schreibberechtigungen im <i>tools</i> Ordner.</strong>

<h3>description_export.php</h3>
Exportiert die Steckbriefe aller Benutzer in die Datei <i>desc-&lt;Datum+Uhrzeit&gt;.csv</i> im Ordner <i>export</i>. Die csv-Datei ist Tab-seperated. Dies muss beim Import in z.B. InDesign oder LibreOffice eingestellt werden. Das Encoding ist UTF-8. <strong>Benötigt Schreibberechtigungen im <i>tools</i> Ordner.</strong>

<h3>names.php</h3>
Gibt alle Namen im html-Format nach Nachnamen geordnet aus.

<h3>preview.php</h3>
Zeigt eine Vorschau der Seite eines gewählten Nutzers an. Diese enthält alle Steckbriefeinträge und Kommentare in selbstgewählter Ordnung.

<h3>quotefiles-gen.php</h3>
Ermöglicht den Export einer beliebigen Anzahl von Zitaten im txt-Format. <strong>Benötigt Schreibberechtigungen im <i>tools</i> Ordner.</strong>

<h3>quotes.php</h3>
Zeigt alle eingesendeten Zitate im html-Format an.

<h3>stats.php</h3>
Zeigt die Anzahl aller Kommentare, den Durchschnitt und die Zahl der Kommentare eines jeden Benutzers an.

<h3>teachers.php</h3>
Ermöglicht das scrapen der Seite www.arnoldinum.de um eine alphabetische Liste der Lehrer in verschiedenen Formaten zu erstellen. Das Format kann durch den Parameter <i>print</i> geändert werden. Mögliche Werte sind "plain", "json", "html" und "mysql", wobei letzterer automatisch die MySQL-Table <i>teachers</i> befüllt sofern diese existiert.

<h2>TODO</h2>
<i>bzw Features die wahrscheinlich niemals implementiert werden</i>

* UI um die Administratorposition von Nutzern zu ändern
* Mehr Anpassung bzw die Möglichkeit Features zu deaktivieren/auszublenden
* Erweitertes Permission-System
* Live-Kommentarfeed mit WebSockets (Ansätze sind in page/api/wsbridge.php zu erkennen)
* Schöneres Design
* Logo das nicht mein ehemaliges Gymnasium beinhaltet

<h2>Informationen</h2>

<strong>Diese Seite ist nicht für den Internet Explorer optimiert und läuft auf machen Versionen dieses garnicht

<strong>Bugs bitte hier bei GitHub unter Issues melden. Vielleicht gucke ich dann nochmal rein. Ansonsten gerne selbst fixen ;)</strong>

<strong>Die Benutzung und Installation dieser Webapplikation ist abolut kostenlos. Der Verkauf ist ausgeschlossen</strong>
