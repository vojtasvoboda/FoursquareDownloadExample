# Stahujeme data z Foursquare přes API

Foursquare je skvělá geolokační služba, která nám dokáže vyhledat zajímavé místa po celém světě nebo pouze v našem okolí. Může se jednat například o restaurace, když máme hlad, nebo o významné památky, pokud zrovna cestujeme. Uživatelé k těmto místům doplnňují různé fotky, komentáře a hodnocení, takže se jedná o velmi cenný zdroj dat. Podívejme se tedy, ja bychom mohli tato data stahovat pro vlastní použití.

Stručná příručka pro vývojáře najdeme na adrese [https://developer.foursquare.com/](https://developer.foursquare.com/), detailní příručka pak na [https://developer.foursquare.com/overview/](https://developer.foursquare.com/overview/)

## Komunikace s Foursquare

Komunikace s Foursquare probíhá pomocí API s REST architekturou a datový formát je JSON. Můžeme si zobrazit i seznam všech koncových bodů [https://developer.foursquare.com/docs/](https://developer.foursquare.com/docs/) Přistupovat můžeme buď z prohlížeče pomocí JavaScriptu, nebo ze strany serveru například pomocí PHP. Pro testování našich dotazů můžeme použít testovací rozhraní [https://developer.foursquare.com/docs/explore](https://developer.foursquare.com/docs/explore)

Autentifikace uživatele, který používá naší aplikaci, je řešeno přes OAuth2. Pro přístup naší aplikaci k API je také potřeba si vygenerovat ID a klíč (Secret). To získáme po přihlášení na stránce s našima aplikacema [https://foursquare.com/developers/apps](https://foursquare.com/developers/apps)

Foursqaure poskytuje čtyři možnosti jak získávat data skrz jejich platformu. Jedná se o:

- **Core API** - poskytuje všechny možnost jako mají uživatelé na webových stránkách a v mobilní aplikaci (je dost pravděpodobné, že tyto rozhraní komunikují přávě přes Core API). Můžeme tak vidět historii nějakého uživatelé, oznámit jeho polohu, vidět jeho přátele, vyhledávat lokace a další. Každý z objektů (místo, tip, list, fotka, atd) má v rámci REST API svojí unikátní koncovou URL např [https://api.foursquare.com/v2/venues/40a55d80f964a52020f31ee3](https://api.foursquare.com/v2/venues/40a55d80f964a52020f31ee3)

- **Real-time API** - pouze pro případ, že potřebujeme vědět, že se nějaký uživatel ohlásil v lokaci, kterou máme ve správě (např restaurace, nebo obchod s oblečením). V případě, že se uživatel ohlásí, nám Foursquare ihned pošle ping notifikaci. Vyžaduje od našeho serveru HTTPS na portu 443.

- **Venues Platform** - podmnožina koncových bodů _venues/*_, která poskytuje databázi míst. Tyto koncové body jako jediné nevyžadují autentifikaci uživatele přes OAuth2. Hledání míst je definováno buď přesným místem (středobodem), nebo městem. Omezením je 5000 volání za hodinu a pokud budeme používat tyto data pro naší aplikaci, musíme uvést že [pocházejí z Foursquare](https://developer.foursquare.com/overview/attribution). Také jsou zde omezení ohledně [uchovávání dat](https://developer.foursquare.com/overview/community) (např cachování).
Oznámení o poloze mohou být uložena maximálně 24 hodin, uživatelská data maximálně 3 hodiny a ostatní data ne více jak 30 dní.

- **Merchant Platform** - přístup pro manažery míst, pro správu speciálních bonusů, kampaní, tipů a informací o spravovaném místě. Umožňuje přístup pouze k lokacím, které máme ve správě.

Protože nespravujeme žádné místo, pojďme zkusit stáhout seznam nějakých míst v okolí našeho bydliště.

## Stahování míst z Foursqaure

Pro hlednání míst tedy využijeme koncové body _venues/*_. Dostupné koncové body jsou:

- venues/search - vhodné pro hledání nejbližších míst, kde specifikujeme zaměření, nebo název lokace
- venues/explore - vhodné pro hledání populárních míst bez specifikace názvu
- venues/<VENUE_ID> - přístup ke konkrétní lokaci dle VENUE_ID
- venues/categories - kategorie míst
- a další které pro nás zatím nejsou zajímavé (venues/managed, venues/suggestcompletion, venues/timeseries, venues/trending)

Například pro hledání nejbližší sushi restaurace máme k dispozici koncový bod _venues/search_, takže cílová URL je:

https://api.foursquare.com/v2/venues/search
  ?client_id=CLIENT_ID
  &client_secret=CLIENT_SECRET
  &v=20130815
  &ll=40.7,-74
  &query=sushi

Kde _client_id_ a _client_secret_ jsou vygenerované přístupové údaje pro naší aplikaci, parametr 'v' je datum kdy jsme naposled testovali aplikaci (podle tohoto data nám bude vrácena verze API), 'll' je střed vyhledávání a 'query' je vyhledávací dotaz. Volitelný parametr je locale určující jazyk vrácených výsledků. Ve výchozím nastavení je locale=en a další možnosti jsou (es, fr, de, it, ja, th, tr, ko, ru, pt, and id).

Zkusíme použít koncový bod venues/explore a vyhledat tak restaurace v okolí místa Praha, Anděl, která má souřadnice 50.071726, 14.402497. Dotaz na API bude:

https://api.foursquare.com/v2/venues/explore?ll=50.071726,14.402497&section=food (plus navíc parametr oauth_token, kterým získáme doporučená místa přímo pro uživatele, kterému OAuth token patří). Tento dotaz lze přímo vyzkoušet v (testovacím rozhraní)[https://developer.foursquare.com/docs/explore#req=venues/explore%3Fll%3D50.071726,14.402497%26section%3Dfood]. Místo parametru ll můžeme použít parametr near, který nastavíme na hodnotu 'Smíchov, Prague' a výsledek bude stejný. Dále můžeme měnit tyto atributy:

- radius - rozsah hledání v metrech, výchozí hodnota je 250 metrů
- section - specifikace kategorie, možnosti jsou food, drinks, coffee, shops, arts, outdoors, sights, trending or specials, nextVenues or topPicks
- query - dotaz kterým upřesníme dotaz, např sushi
- limit - maximum vrácených výsledků
- offset - stránkování v rámci vrácených výsledků
- a další

Protože chceme data stahovat strojově, hodila by se nějaká knihovna, která by se nám postarala o komunikaci a získávání dat. Foursquare přímo zveřejňuje [seznam dostupných knihoven](https://developer.foursquare.com/resources/libraries) Zkusíme použít tu první. Otevřeme terminál a hurá na to. Vytvoříme si složku dostupnou z našeho webového serveru, inicializuje Git, Composer a přidáme závislost na vybranou knihovnu podle poslední dostupné verze na Githubu:

```
mkdir Foursquare
cd Foursquare
git init
composer init
composer require thetwelvelabs/foursquare:'0.2.*'
touch index.php
```

Dále si vytvořím soubor index.php, kde budu psát logiku aplikace. Rovnou si také přidám doplněk tracy/tracy pro lepší ladění kódu:

```
composer require tracy/tracy:'2.2.*'
```

Podle návodu na GitHubu si zkusíme zavolat koncový bod venues/explore (venues/search není ještě v knihovně implementován). Je potřeba trošku upravit soubor composer.json, aby se nám stáhnul také certifikát, který potřeba pro komunikaci přes _curl_.

Získané místa dostaneme už přímo jako pole objektů stdClass, nemusíme tak parsovat JSON.

## Dobré vědět
ID místa se může měnit, ale staré ID vždy přesměruje na nové.
