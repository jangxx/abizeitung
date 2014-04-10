<h1>abizeitung Online</h1>

Abizeitungen leicht gemacht. Jedes Stufenmitglied bekommt einen persönlichen Login und kann damit seinen Steckbrief anfertigen, an Wahlen über Mitschüler und Lehrer teilnehmen, ein Stufenpärchen wählen, Kommentare über Personen schreiben und Zitate aus dem Unterreicht beisteuern.

Zum extrahieren dieser Informationen liegen im Ordner tools Skripte bereit.

<h2>Installation</h2>

Projekt runterladen und die install.php mit php-cli ausführen. Diese bereitet den MySQL Server vor und erstellt die benötigte constants.php.
Danach kann der Ordner page unverändert in den www Ordner einer apache Installation verschoben werden.
Der Tools Order muss ebenfalls in einem für apache zugänglichem Pfad liegen.

<h2>Tools</h2>

<h3>comment_export.php</h3>
Exportiert die Kommentare in Dateien in der Form "Nachname, Vorname,txt" in den Ordner export/comment-&gt;Uhrzeit&lt; und zeigt die Zahl der exportierten Kommentare an. Die Dateien sind im Windows Format mit \r\n als Newline-character. <i>Benötigt Schreibberechtigungen im tools Ordner.</i>

<h3>description_export.php</h3>
Exportiert die Steckbriefe aller Benutzer in die Datei desc-&gt;Uhrzeit&lt;.cvs im Ordner export. Die csv-Datei ist Tab-seperated. Dies muss beim Import in zB InDesign oder LibreOffice eingestellt werden. Das Encoding ist UTF-8.

<h3>names.php</h3>
Gibt alle Namen im html-Format nach Nachnamen geordnet aus.

<h3>preview.php</h3>
Zeigt eine Vorschau der Seite eines gewählten Nutzers an. Diese enthält alle Steckbriefeinträge und Kommentare in selbstgewählter Ordnung.

<h3>quotefiles-gen.php</h3>
Ermöglicht den Export einer beliebigen Anzahl von Zitaten im txt-Format. <i>Benötigt Schreibberechtigungen im tools Ordner.</i>

<h3>quotes.php</h3>
Zeigt alle eingesendeten Zitate im html-Format an.

<h3>stats.php</h3>
Zeigt die Anzahl aller Kommentare, den Durchschnitt und die Zahl der Kommentare eines jeden Benutzers an.

<h3>teachers.php</h3>
Ermöglicht das scrapen der Seite <i>www.arnoldinum.de</i> um eine alphabetische Liste der Lehrer in verschiedenen Formaten zu erstellen. Das Format kann durch den Parameter <i>print</i> geändert werden. Mögliche Werte sind "plain", "json", "html" und MySQL, wobei MySQL automatisch die Tabelle teachers befüllt sofern diese existiert.

<h2>TODO</h2>
<i>bzw Features die wahrscheinlich niemals implementiert werden</i>

* UI um die Administratorposition von Nutzern zu ändern
* Mehr Anpassung bzw die Möglichkeit Features zu deaktivieren/auszublenden
* Erweitertes Permission-System
* Live-Kommentarfeed mit WebSockets

<h2>Informationen</h2>

<strong>Diese Seite ist nicht für den Internet Explorer optimiert und läuft auf machen Versionen dieses garnicht

<strong>Bugs bitte hier bei GitHub unter Issues melden. Vielleicht gucke ich dann nochmal rein. Ansonsten gerne selbst fixen ;)</strong>
