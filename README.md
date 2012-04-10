Contao Search and Replace
=========================

Funktionsumfang
---------------

Mit dieser Erweiterung lassen sich Such- und Ersetzungsregeln für Seiten, Artikel und Inhaltselemente definieren und zusammenfassen. Ein Ersetzungsset wird auf definierte Seiten der Seitenstruktur angewendet (bei Bedarf inkl. aller Unterseiten) und kann veliebig viele Regeln enthalten. Die Regeln selbst können reine Ersetzungen des Inhalts oder Such- und Ersetzungsregeln sein (auch als reguläre Ausdrücke). Dabei können auch serialisierte Felder wie Überschriften (headline aus tl_content) behandelt werden.

Beim Ersetzen des Inhalts mit den vordefinierten Regeln wird automatisch eine neue Version der Seite, des Artikels oder Inhaltselement angelegt, sodass sich einzelne Aktionen wieder zurücksetzen lassen. Trotzdem sollten Sie vor der Verwendung unbedingt ein Backup erstellen, da Fehler beim Ersetzen schnell mal hunderte Datensätze betreffen können und damit der Aufwand ennorm wäre, dies von Hand wieder zurückzusetzen.